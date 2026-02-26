<?php
require_once 'config.php';

$message = '';
$messageType = 'success';
$editingItem = null;
$menuItems = [];
$hasDescription = false;
$hasId = false;

function isPdoConnection($conn)
{
    return $conn instanceof PDO;
}

function tableExists($conn, $tableName)
{
    if (isPdoConnection($conn)) {
        $stmt = $conn->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$tableName]);
        return (bool) $stmt->fetchColumn();
    }

    $safeTable = $conn->real_escape_string($tableName);
    $result = $conn->query("SHOW TABLES LIKE '{$safeTable}'");
    return $result && $result->num_rows > 0;
}

function columnExists($conn, $tableName, $columnName)
{
    if (isPdoConnection($conn)) {
        $stmt = $conn->prepare("SHOW COLUMNS FROM `{$tableName}` LIKE ?");
        $stmt->execute([$columnName]);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    $safeColumn = $conn->real_escape_string($columnName);
    $result = $conn->query("SHOW COLUMNS FROM `{$tableName}` LIKE '{$safeColumn}'");
    return $result && $result->num_rows > 0;
}

function runStatement($conn, $sql, $params = [])
{
    if (isPdoConnection($conn)) {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }

    if (!empty($params)) {
        $types = '';
        $values = [];

        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
            $values[] = $param;
        }

        $bindArgs = [];
        $bindArgs[] = $types;
        for ($i = 0; $i < count($values); $i++) {
            $bindArgs[] = &$values[$i];
        }

        call_user_func_array([$stmt, 'bind_param'], $bindArgs);
    }

    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }

    return $stmt;
}

function ensureMenuTable($conn)
{
    if (!tableExists($conn, 'menu')) {
        $createSql = "CREATE TABLE `menu` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `price` DECIMAL(10,2) NOT NULL,
            `category` VARCHAR(120) NOT NULL,
            `image_url` VARCHAR(500) NOT NULL,
            `description` TEXT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        if (isPdoConnection($conn)) {
            $conn->exec($createSql);
        } else {
            if (!$conn->query($createSql)) {
                throw new Exception('Table creation failed: ' . $conn->error);
            }
        }
        return;
    }

    $columnStatements = [
        'name' => "ALTER TABLE `menu` ADD COLUMN `name` VARCHAR(255) NOT NULL DEFAULT ''",
        'price' => "ALTER TABLE `menu` ADD COLUMN `price` DECIMAL(10,2) NOT NULL DEFAULT 0",
        'category' => "ALTER TABLE `menu` ADD COLUMN `category` VARCHAR(120) NOT NULL DEFAULT ''",
        'image_url' => "ALTER TABLE `menu` ADD COLUMN `image_url` VARCHAR(500) NOT NULL DEFAULT ''",
        'description' => "ALTER TABLE `menu` ADD COLUMN `description` TEXT NULL"
    ];

    foreach ($columnStatements as $column => $sql) {
        if (!columnExists($conn, 'menu', $column)) {
            if (isPdoConnection($conn)) {
                $conn->exec($sql);
            } else {
                if (!$conn->query($sql)) {
                    throw new Exception('Could not add column ' . $column . ': ' . $conn->error);
                }
            }
        }
    }

    if (!columnExists($conn, 'menu', 'id')) {
        $addIdSql = "ALTER TABLE `menu` ADD COLUMN `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
        if (isPdoConnection($conn)) {
            $conn->exec($addIdSql);
        } else {
            if (!$conn->query($addIdSql)) {
                throw new Exception('Could not add id column: ' . $conn->error);
            }
        }
    }
}

if (empty($conn)) {
    $message = isset($db_error) ? $db_error : 'Database connection is not available.';
    $messageType = 'danger';
} else {
    try {
        ensureMenuTable($conn);
        $hasDescription = columnExists($conn, 'menu', 'description');
        $hasId = columnExists($conn, 'menu', 'id');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = isset($_POST['action']) ? trim($_POST['action']) : '';

            if ($action === 'add' || $action === 'update') {
                $itemId = isset($_POST['id']) ? (int) $_POST['id'] : 0;
                $name = isset($_POST['name']) ? trim($_POST['name']) : '';
                $price = isset($_POST['price']) ? trim($_POST['price']) : '';
                $category = isset($_POST['category']) ? trim($_POST['category']) : '';
                $imageUrl = isset($_POST['image_url']) ? trim($_POST['image_url']) : '';
                $description = isset($_POST['description']) ? trim($_POST['description']) : '';

                if ($name === '' || $price === '' || $category === '' || $imageUrl === '') {
                    throw new Exception('Please fill out all required fields.');
                }

                if (!is_numeric($price) || (float) $price < 0) {
                    throw new Exception('Price must be a valid number greater than or equal to 0.');
                }

                if ($action === 'add') {
                    $insertSql = $hasDescription
                        ? "INSERT INTO menu (name, price, category, image_url, description) VALUES (?, ?, ?, ?, ?)"
                        : "INSERT INTO menu (name, price, category, image_url) VALUES (?, ?, ?, ?)";

                    $params = $hasDescription
                        ? [$name, (float) $price, $category, $imageUrl, $description]
                        : [$name, (float) $price, $category, $imageUrl];

                    runStatement($conn, $insertSql, $params);
                    $message = 'Menu item added successfully.';
                    $messageType = 'success';
                } else {
                    if (!$hasId || $itemId <= 0) {
                        throw new Exception('Cannot update item because a valid ID was not provided.');
                    }

                    $updateSql = $hasDescription
                        ? "UPDATE menu SET name = ?, price = ?, category = ?, image_url = ?, description = ? WHERE id = ?"
                        : "UPDATE menu SET name = ?, price = ?, category = ?, image_url = ? WHERE id = ?";

                    $params = $hasDescription
                        ? [$name, (float) $price, $category, $imageUrl, $description, $itemId]
                        : [$name, (float) $price, $category, $imageUrl, $itemId];

                    runStatement($conn, $updateSql, $params);
                    $message = 'Menu item updated successfully.';
                    $messageType = 'success';
                }
            }

            if ($action === 'delete') {
                if (!$hasId) {
                    throw new Exception('Cannot delete item because the menu table has no ID column.');
                }

                $itemId = isset($_POST['id']) ? (int) $_POST['id'] : 0;
                if ($itemId <= 0) {
                    throw new Exception('Invalid item ID for delete action.');
                }

                runStatement($conn, "DELETE FROM menu WHERE id = ?", [$itemId]);
                $message = 'Menu item deleted successfully.';
                $messageType = 'success';
            }
        }

        if (isset($_GET['edit']) && $hasId) {
            $editId = (int) $_GET['edit'];
            if ($editId > 0) {
                $editSelect = $hasDescription
                    ? "SELECT id, name, price, category, image_url, description FROM menu WHERE id = ?"
                    : "SELECT id, name, price, category, image_url FROM menu WHERE id = ?";
                $stmt = runStatement($conn, $editSelect, [$editId]);

                if (isPdoConnection($conn)) {
                    $editingItem = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $result = $stmt->get_result();
                    $editingItem = $result ? $result->fetch_assoc() : null;
                }
            }
        }

        $selectSql = $hasDescription
            ? "SELECT id, name, price, category, image_url, description FROM menu ORDER BY id DESC"
            : "SELECT id, name, price, category, image_url FROM menu ORDER BY id DESC";
        $stmt = runStatement($conn, $selectSql);

        if (isPdoConnection($conn)) {
            $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $result = $stmt->get_result();
            $menuItems = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Menu CRUD</title>
    <link rel="stylesheet" href="CSS/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0">Menu Admin Panel</h1>
            <a href="menu.php" class="btn btn-outline-secondary btn-sm">View Menu Page</a>
        </div>

        <?php if ($message !== ''): ?>
            <div class="alert alert-<?php echo htmlspecialchars($messageType); ?>" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="h5 mb-3"><?php echo $editingItem ? 'Edit Menu Item' : 'Add Menu Item'; ?></h2>
                <form method="POST" action="admin.php">
                    <input type="hidden" name="action" value="<?php echo $editingItem ? 'update' : 'add'; ?>">
                    <input type="hidden" name="id" value="<?php echo $editingItem ? (int) $editingItem['id'] : 0; ?>">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Item Name</label>
                            <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($editingItem['name'] ?? ''); ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="price">Price</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" required value="<?php echo htmlspecialchars($editingItem['price'] ?? ''); ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="category">Category</label>
                            <input type="text" class="form-control" id="category" name="category" required value="<?php echo htmlspecialchars($editingItem['category'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image_url">Image URL / Path</label>
                        <input type="text" class="form-control" id="image_url" name="image_url" required value="<?php echo htmlspecialchars($editingItem['image_url'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($editingItem['description'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary"><?php echo $editingItem ? 'Update Item' : 'Add Item'; ?></button>
                    <?php if ($editingItem): ?>
                        <a href="admin.php" class="btn btn-outline-secondary">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h2 class="h5 mb-3">Current Menu Items</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Image</th>
                                <th>Description</th>
                                <th style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($menuItems)): ?>
                                <tr>
                                    <td colspan="7" class="text-center">No menu items found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($menuItems as $item): ?>
                                    <tr>
                                        <td><?php echo isset($item['id']) ? (int) $item['id'] : 0; ?></td>
                                        <td><?php echo htmlspecialchars($item['name'] ?? ''); ?></td>
                                        <td>₱<?php echo number_format((float) ($item['price'] ?? 0), 2); ?></td>
                                        <td><?php echo htmlspecialchars($item['category'] ?? ''); ?></td>
                                        <td>
                                            <span class="small d-block text-truncate" style="max-width: 180px;">
                                                <?php echo htmlspecialchars($item['image_url'] ?? ''); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="small d-block text-truncate" style="max-width: 220px;">
                                                <?php echo htmlspecialchars($item['description'] ?? ''); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($hasId && isset($item['id'])): ?>
                                                <a href="admin.php?edit=<?php echo (int) $item['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                                <form method="POST" action="admin.php" style="display:inline-block;" onsubmit="return confirm('Delete this menu item?');">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo (int) $item['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted small">ID required for edit/delete</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>