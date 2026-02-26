<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/transitions.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome fallback (CSS) so icons show if kit JS hasn't loaded -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header>
        <nav class="navbar navbar-dark navbar-expand-lg bg-red">
            <div class="container">
                <a href="index.html" class="navbar-brand"><img src="img/LOGO.png" alt="Logo" title="Logo"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a href="index.html" class="nav-link"><i class="fa-solid fa-house"></i> Home</a></li>
                        <li class="nav-item"><a href="menu.php" class="nav-link active"><i class="fa-solid fa-utensils"></i> Menu</a></li>
                        <li class="nav-item"><a href="about.html" class="nav-link"><i class="fa-solid fa-magnifying-glass"></i> About</a></li>
                        <li class="nav-item"><a href="service.html" class="nav-link"><i class="fa-solid fa-truck"></i> Service</a></li>
                        <li class="nav-item"><a href="contact.html" class="nav-link"><i class="fa-regular fa-address-card"></i> Contact Us</a></li>
                        <li class="nav-item"><a href="https://forms.gle/FN3Df82HiEh8cGQLA" class="nav-link"><i class="fa-regular fa-star"></i> Rate Us</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <section class="container my-5">
        <div class="menu-heading">
            <h1>BibiQue</h1>
            <h3>&mdash; MENU &mdash;</h3>
        </div>

        <!-- Early filterBy stub: ensures inline onclick handlers find a function immediately -->
        <script>
            (function(){
                function norm(s){ return (s||'').toString().trim().toLowerCase(); }
                window.filterBy = function(raw){
                    try{
                        var wanted = (raw||'all').toString().trim().toLowerCase();
                        var cards = Array.prototype.slice.call(document.querySelectorAll('.menu-card'));
                        var buttons = Array.prototype.slice.call(document.querySelectorAll('.btn-filter'));
                        var visible = 0;
                        function setVisible(card, show){
                            if(show){
                                // show with animation
                                card.style.display = '';
                                // ensure starting hidden state
                                requestAnimationFrame(function(){
                                    card.classList.remove('hidden');
                                });
                            } else {
                                // hide with animation then set display none after transition
                                card.classList.add('hidden');
                                var onEnd = function(){ card.style.display = 'none'; card.removeEventListener('transitionend', onEnd); };
                                card.addEventListener('transitionend', onEnd);
                            }
                        }
                        cards.forEach(function(card){
                            var cat = norm(card.getAttribute('data-category'));
                            var title = norm((card.querySelector('.card-title')||{}).textContent || '');
                            var catText = norm((card.querySelector('.card-cat')||{}).textContent || '');
                            var match = (wanted === 'all') || cat === wanted || title.indexOf(wanted) !== -1 || catText === wanted;
                            setVisible(card, match);
                            if(match) visible++;
                        });
                        // update buttons active state
                        buttons.forEach(function(b){ b.classList.toggle('active', norm(b.getAttribute('data-filter')) === wanted); });
                        // debug panel removed in production
                    } catch(e){ console.error('filterBy early error', e); }
                };
            })();
        </script>

        <!-- Filters -->
        <div class="filters" role="tablist" aria-label="Menu categories">
            <button type="button" class="btn-filter active" data-filter="all" onclick="filterBy('all')">All</button>
            <button type="button" class="btn-filter" data-filter="Combo Meal" onclick="filterBy('Combo Meal')">Combo Meal</button>
            <button type="button" class="btn-filter" data-filter="Family Meal" onclick="filterBy('Family Meal')">Family Meal</button>
            <button type="button" class="btn-filter" data-filter="Affordable Meal" onclick="filterBy('Affordable Meal')">Affordable Meal</button>
            <button type="button" class="btn-filter" data-filter="Basic Combo Meal" onclick="filterBy('Basic Combo Meal')">Basic Combo Meal</button>
        </div>

    <!-- Category: Combo Meal (heading removed for cleaner layout) -->
        <div class="menu-grid mb-3">
            <div class="menu-card" data-category="Combo Meal" data-description="Includes BBQ, tenga ng baboy, leeg ng manok, 2 salted eggs, sliced tomato, and a family-sized Coke.">
                <img src="img/menu/COMBOMEAL.png" alt="Combo Meal">
                <div class="card-body text-center">
                    <div class="card-title">Combo Meal</div>
                    <div class="card-cat">Combo Meal</div>
                    <div class="card-actions">
                        <div class="card-price">₱499</div>
                        <div>
                            <button class="btn-details">Details</button>
                            <button class="btn-add">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="menu-card" data-category="Combo Meal" data-description="Double portion: 2x BBQ, 2x tenga ng baboy, 2x leeg ng manok, 2 salted eggs, sliced tomato, and two family-sized Cokes.">
                <img src="img/menu/COMBOMEALPROMAX.png" alt="Combo Meal ProMax">
                <div class="card-body text-center">
                    <div class="card-title">Combo Meal ProMax</div>
                    <div class="card-cat">Combo Meal</div>
                    <div class="card-actions">
                        <div class="card-price">₱699</div>
                        <div>
                            <button class="btn-details">Details</button>
                            <button class="btn-add">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Category: Family Meal (heading removed for cleaner layout) -->
        <div class="menu-grid mb-3">
            <div class="menu-card" data-category="Family Meal">
                <img src="img/menu/FAMILYCOMBO.png" alt="Family Combo">
                <div class="card-body text-center">
                    <div class="card-title">Family Meal</div>
                    <div class="card-cat">Family Meal</div>
                    <div class="card-actions">
                        <div class="card-price">₱1,299</div>
                        <div>
                            <button class="btn-details">Details</button>
                            <button class="btn-add">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Category: Affordable Meal (heading removed for cleaner layout) -->
        <div class="menu-grid mb-3">
            <div class="menu-card" data-category="Affordable Meal">
                <img src="img/menu/LEEGCOMBOMEAL.png" alt="Leeg Combo Meal">
                <div class="card-body text-center">
                    <div class="card-title">Leeg Combo</div>
                    <div class="card-cat">Affordable Meal</div>
                    <div class="card-actions">
                        <div class="card-price">₱199</div>
                        <div>
                            <button class="btn-details">Details</button>
                            <button class="btn-add">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="menu-card" data-category="Affordable Meal">
                <img src="img/menu/BBQCOMBOMEAL.png" alt="BBQ Combo Meal">
                <div class="card-body text-center">
                    <div class="card-title">BBQ Combo</div>
                    <div class="card-cat">Affordable Meal</div>
                    <div class="card-actions">
                        <div class="card-price">₱219</div>
                        <div>
                            <button class="btn-details">Details</button>
                            <button class="btn-add">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="menu-card" data-category="Affordable Meal">
                <img src="img/menu/TENGACOMBOMEAL.png" alt="Tenga Combo Meal">
                <div class="card-body text-center">
                    <div class="card-title">Tenga Combo</div>
                    <div class="card-cat">Affordable Meal</div>
                    <div class="card-actions">
                        <div class="card-price">₱189</div>
                        <div>
                            <button class="btn-details">Details</button>
                            <button class="btn-add">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Category: Basic Combo Meal (heading removed for cleaner layout) -->
        <div class="menu-grid mb-5">
            <div class="menu-card" data-category="Basic Combo Meal">
                <img src="img/menu/BBQ1.png" alt="BBQ1">
                <div class="card-body text-center">
                    <div class="card-title">BBQ 1</div>
                    <div class="card-cat">Basic Combo Meal</div>
                    <div class="card-actions"><div class="card-price">₱129</div><div><button class="btn-details">Details</button><button class="btn-add">Add</button></div></div>
                </div>
            </div>
            <div class="menu-card" data-category="Basic Combo Meal">
                <img src="img/menu/BBQ2.png" alt="BBQ2">
                <div class="card-body text-center">
                    <div class="card-title">BBQ 2</div>
                    <div class="card-cat">Basic Combo Meal</div>
                    <div class="card-actions"><div class="card-price">₱139</div><div><button class="btn-details">Details</button><button class="btn-add">Add</button></div></div>
                </div>
            </div>
            <div class="menu-card" data-category="Basic Combo Meal">
                <img src="img/menu/TENGA1.png" alt="Tenga1">    
                <div class="card-body text-center">
                    <div class="card-title">Tenga 1</div>
                    <div class="card-cat">Basic Combo Meal</div>
                    <div class="card-actions"><div class="card-price">₱119</div><div><button class="btn-details">Details</button><button class="btn-add">Add</button></div></div>
                </div>
            </div>
            <div class="menu-card" data-category="Basic Combo Meal">
                <img src="img/menu/TENGA2.png" alt="Tenga2">
                <div class="card-body text-center">
                    <div class="card-title">Tenga 2</div>
                    <div class="card-cat">Basic Combo Meal</div>
                    <div class="card-actions"><div class="card-price">₱119</div><div><button class="btn-details">Details</button><button class="btn-add">Add</button></div></div>
                </div>
            </div>
            <div class="menu-card" data-category="Basic Combo Meal">
                <img src="img/menu/LEEG1.png" alt="Leeg1">
                <div class="card-body text-center">
                    <div class="card-title">Leeg 1</div>
                    <div class="card-cat">Basic Combo Meal</div>
                    <div class="card-actions"><div class="card-price">₱109</div><div><button class="btn-details">Details</button><button class="btn-add">Add</button></div></div>
                </div>
            </div>
            <div class="menu-card" data-category="Basic Combo Meal">
                <img src="img/menu/LEEG2.png" alt="Leeg2">
                <div class="card-body text-center">
                    <div class="card-title">Leeg 2</div>
                    <div class="card-cat">Basic Combo Meal</div>
                    <div class="card-actions"><div class="card-price">₱109</div><div><button class="btn-details">Details</button><button class="btn-add">Add</button></div></div>
                </div>
            </div>
        </div>
    </section>

        <!--Menu-->
    <div class="container my-5">
    <h2 class="text-center mb-4">Our Menu</h2>
    <div class="menu-grid" id="db-grid">
        <?php
        // Ensure database connection is available
        if (!isset($conn)) {
            // adjust path if your config is in a different folder
            require_once 'config.php';
        }

        // If config set an error or $conn is null, show a friendly message instead of fatal error
        if (empty($conn)) {
            echo '<div class="text-center" style="padding:22px 12px;">';
            echo '<h4>Database unavailable</h4>';
            $msg = isset($db_error) ? $db_error : 'Database connection is not available. Please enable the mysqli or PDO extension in your PHP configuration and verify credentials in config.php.';
            echo '<p style="max-width:680px;margin:10px auto;color:#444;">' . htmlspecialchars($msg) . '</p>';
            echo '</div>';
        } else {
            // Fetch menu items from the database (support both mysqli and PDO)
            if ($conn instanceof PDO) {
                try {
                    $stmt = $conn->query("SELECT name, price, category, image_url FROM menu");
                    $rows = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
                    if ($rows) {
                        foreach ($rows as $row) {
                            $name = htmlspecialchars($row['name']);
                            $cat = htmlspecialchars($row['category']);
                            $price = htmlspecialchars($row['price']);
                            $img = htmlspecialchars($row['image_url']);

                            echo '<div class="menu-card" data-category="' . $cat . '">';
                            echo '  <img src="' . $img . '" alt="' . $name . '">';
                            echo '  <div class="card-body text-center">';
                            echo '    <div class="card-title">' . $name . '</div>';
                            echo '    <div class="card-cat">' . $cat . '</div>';
                            echo '    <div class="card-actions">';
                            echo '      <div class="card-price">' . ($price !== '' ? '₱' . $price : '--') . '</div>';
                            echo '      <div><button class="btn-details">Details</button> <button class="btn-add">Add</button></div>';
                            echo '    </div>';
                            echo '  </div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="text-center">No menu items available.</p>';
                    }
                } catch (Exception $e) {
                    echo '<p class="text-center">Error fetching menu items: ' . htmlspecialchars($e->getMessage()) . '</p>';
                }
            } else {
                // assume mysqli
                $sql = "SELECT name, price, category, image_url FROM menu";
                $result = @$conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    // Loop through and display each menu item
                    while ($row = $result->fetch_assoc()) {
                        $name = htmlspecialchars($row['name']);
                        $cat = htmlspecialchars($row['category']);
                        $price = htmlspecialchars($row['price']);
                        $img = htmlspecialchars($row['image_url']);

                        echo '<div class="menu-card" data-category="' . $cat . '">';
                        echo '  <img src="' . $img . '" alt="' . $name . '">';
                        echo '  <div class="card-body text-center">';
                        echo '    <div class="card-title">' . $name . '</div>';
                        echo '    <div class="card-cat">' . $cat . '</div>';
                        echo '    <div class="card-actions">';
                        echo '      <div class="card-price">' . ($price !== '' ? '₱' . $price : '--') . '</div>';
                        echo '      <div><button class="btn-details">Details</button> <button class="btn-add">Add</button></div>';
                        echo '    </div>';
                        echo '  </div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="text-center">No menu items available.</p>';
                }
            }
        }
        ?>
    </div>
    </div>

    <!-- property -->
    <footer class="bg-orange text-light py-4">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-12">
                    <img src="img/LOGO.png" alt="Logo" class="mb-3">
                    <p>Experience the best BBQ in town. Follow us on social media for the latest updates and exclusive offers.</p>
                    <ul class="list-inline">
                        <li class="list-inline-item"><a href="https://www.facebook.com/art.rivera.195397/" target="_blank" class="text-light"><i class="fab fa-facebook"></i></a></li>
                        <li class="list-inline-item"><a href="https://x.com/home" target="_blank" class="text-light"><i class="fab fa-twitter"></i></a></li>
                        <li class="list-inline-item"><a href="https://www.instagram.com/artriveraa/" target="_blank" class="text-light"><i class="fab fa-instagram"></i></a></li>
                        <li class="list-inline-item"><a href="https://www.youtube.com/@MrBeast" target="_blank" class="text-light"><i class="fab fa-youtube"></i></a></li>
                    </ul>
                    <p>&copy; 2024 BIBIQUE.SHOP. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    
    <div class="container my-5">
      
    </div>

    <img src="img/emoji/menu2.gif" alt="" title="" style="display: block; margin: 0 auto;">
    <p>nya ichi ni san nya arigato nya ich ni san nya arigato</p>
    <!-- Details modal -->
    <div id="menu-modal" aria-hidden="true" style="display:none;">
        <div id="menu-modal-overlay" style="position:fixed;left:0;top:0;right:0;bottom:0;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:10000;">
            <div id="menu-modal-content" role="dialog" aria-modal="true" style="background:#fff;border-radius:10px;max-width:740px;width:94%;box-shadow:0 18px 40px rgba(0,0,0,0.2);overflow:hidden;">
                <button id="menu-modal-close" aria-label="Close details" style="position:absolute;right:14px;top:10px;background:#fff;border:none;font-size:20px;padding:6px 10px;cursor:pointer;border-radius:6px;z-index:10001;">✕</button>
                <div style="display:flex;flex-wrap:wrap;">
                    <div style="flex:1 1 320px;min-width:260px;max-width:360px;">
                        <img id="menu-modal-img" src="" alt="" style="width:100%;height:100%;object-fit:cover;display:block;">
                    </div>
                    <div style="flex:1 1 320px;padding:20px;">
                        <h3 id="menu-modal-title" style="margin-top:0;margin-bottom:8px;font-size:22px;color:#111;"></h3>
                        <div id="menu-modal-cat" style="color:#777;margin-bottom:12px;"></div>
                        <div id="menu-modal-price" style="font-weight:800;color:#D6001C;margin-bottom:12px;font-size:18px;"></div>
                        <p id="menu-modal-desc" style="color:#444;line-height:1.5;margin-bottom:16px;"></p>
                        <div style="display:flex;gap:10px;align-items:center;">
                            <button id="menu-modal-add" class="btn-add" style="background:#D6001C;color:#fff;border:none;padding:10px 14px;border-radius:8px;cursor:pointer;font-weight:700;">Add to cart</button>
                            <button id="menu-modal-close-2" style="background:#fff;border:1px solid #eee;padding:8px 12px;border-radius:8px;cursor:pointer;">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/fd2bbc93ce.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Robust filter script: uses event delegation, alias map, logs clicks and shows "No items found" message.
        (function(){
            const alias = {
                'combo': 'combo meal',
                'combomeal': 'combo meal',
                'family': 'family meal',
                'affordable': 'affordable meal',
                'basic': 'basic combo meal',
                'basic combo': 'basic combo meal'
            };

            function norm(s){ return (s||'').toString().trim().toLowerCase(); }

            function init(){
                const container = document.querySelector('.filters');
                const cards = Array.from(document.querySelectorAll('.menu-card'));
                if(!container){ console.warn('Filter container not found'); return; }

                // create a small "no results" element
                let noEl = document.querySelector('#no-results-message');
                if(!noEl){
                    noEl = document.createElement('div');
                    noEl.id = 'no-results-message';
                    noEl.style.textAlign = 'center';
                    noEl.style.padding = '18px 0';
                    noEl.style.display = 'none';
                    noEl.innerText = 'No items found for this category.';
                    container.parentNode.insertBefore(noEl, container.nextSibling);
                }

                console.info('Menu filters init — buttons:', container.querySelectorAll('.btn-filter').length, 'cards:', cards.length);

                container.addEventListener('click', function(e){
                    const btn = e.target.closest('.btn-filter');
                    if(!btn) return;
                    e.preventDefault();
                    // set active class
                    container.querySelectorAll('.btn-filter').forEach(b=>b.classList.remove('active'));
                    btn.classList.add('active');

                    const raw = btn.getAttribute('data-filter') || 'all';
                    let wanted = norm(raw);
                    // map aliases
                    if(alias[wanted]) wanted = alias[wanted];

                    // apply filter
                    let visible = 0;
                    cards.forEach(card=>{
                        const cat = norm(card.getAttribute('data-category'));
                        const title = norm((card.querySelector('.card-title')||{}).textContent || '');
                        const catText = norm((card.querySelector('.card-cat')||{}).textContent || '');

                        const matchAll = (wanted === 'all' || wanted === '');
                        const match = matchAll || cat === wanted || catText === wanted || title.includes(wanted);
                        card.style.display = match ? '' : 'none';
                        if(match) visible++;
                    });

                    // show no-results message when none visible
                    noEl.style.display = visible ? 'none' : '';
                    console.info('Filter clicked:', raw, 'normalized:', wanted, 'visible:', visible);
                });

                // initialize current active
                const initial = container.querySelector('.btn-filter.active') || container.querySelector('.btn-filter');
                if(initial){ initial.click(); }
            }

            if(document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
        })();
    </script>
    <script>
        // Fallback: direct button bindings + on-page debug panel to help when serving on different ports (e.g. http://localhost:3000)
        (function(){
            function norm(s){ return (s||'').toString().trim().toLowerCase(); }

            const container = document.querySelector('.filters');
            if(!container) return;
            const buttons = Array.from(container.querySelectorAll('.btn-filter'));
            const cards = Array.from(document.querySelectorAll('.menu-card'));

            // debug panel removed; keep bindings minimal

            // direct binding (fallback if delegation doesn't work in some setups)
            buttons.forEach(btn=>{
                btn.addEventListener('click', function(e){
                    const raw = btn.getAttribute('data-filter') || 'all';
                    const wanted = norm(raw);
                    // apply same matching logic used earlier
                    let visible = 0;
                    cards.forEach(card=>{
                        const cat = norm(card.getAttribute('data-category'));
                        const title = norm((card.querySelector('.card-title')||{}).textContent || '');
                        const catText = norm((card.querySelector('.card-cat')||{}).textContent || '');
                        const match = (wanted === 'all') || cat === wanted || title.includes(wanted) || catText === wanted;
                        card.style.display = match ? '' : 'none';
                        if(match) visible++;
                    });
                    // debug panel removed
                });
            });

            // no debug panel
        })();
    </script>
    <script>
        // Details modal behavior
        (function(){
            function qs(sel, ctx) { return (ctx||document).querySelector(sel); }
            function qsa(sel, ctx) { return Array.from((ctx||document).querySelectorAll(sel)); }

            function openModal(card){
                var img = qs('img', card);
                var title = qs('.card-title', card)?.textContent || '';
                var cat = qs('.card-cat', card)?.textContent || '';
                var price = qs('.card-price', card)?.textContent || '';
                var desc = card.getAttribute('data-description') || qs('.card-cat', card)?.textContent || '';

                qs('#menu-modal-img').src = img ? img.src : '';
                qs('#menu-modal-img').alt = title;
                qs('#menu-modal-title').textContent = title;
                qs('#menu-modal-cat').textContent = cat;
                qs('#menu-modal-price').textContent = price;
                qs('#menu-modal-desc').textContent = desc;

                var modal = qs('#menu-modal');
                modal.style.display = '';
                qs('#menu-modal-overlay').focus?.();
                modal.setAttribute('aria-hidden','false');
            }

            function closeModal(){
                var modal = qs('#menu-modal');
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden','true');
            }

            // bind detail buttons
            document.addEventListener('click', function(e){
                var btn = e.target.closest('.btn-details');
                if(btn){
                    e.preventDefault();
                    var card = e.target.closest('.menu-card');
                    if(card) openModal(card);
                }

                if(e.target.id === 'menu-modal-close' || e.target.id === 'menu-modal-close-2'){
                    closeModal();
                }
            });

            // close on Escape
            document.addEventListener('keydown', function(e){ if(e.key === 'Escape') closeModal(); });
        })();
    </script>
    <script>
    // Global function used by inline onclick handlers on filter buttons.
    window.filterBy = function(raw){
            try{
                const wanted = (raw||'all').toString().trim().toLowerCase();
                const cards = Array.from(document.querySelectorAll('.menu-card'));
                let visible = 0;
                cards.forEach(card=>{
                    const cat = (card.getAttribute('data-category')||'').toString().trim().toLowerCase();
                    const title = (card.querySelector('.card-title')||{}).textContent || '';
                    const catText = (card.querySelector('.card-cat')||{}).textContent || '';
                    const match = wanted === 'all' || cat === wanted || title.toLowerCase().includes(wanted) || catText.toLowerCase() === wanted;
                    card.style.display = match ? '' : 'none';
                    if(match) visible++;
                });
                // debug panel removed
            } catch(err){
                console.error('filterBy error', err);
            }
        }
    </script>
    <script src="js/page-transitions.js"></script>
</body>
</html>
