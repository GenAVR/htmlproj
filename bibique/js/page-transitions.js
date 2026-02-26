/* Page transitions script
   - Adds .page-ready on load to trigger CSS enter animation
   - Intercepts same-origin link clicks (excluding anchors, mailto:, tel:, external links, and modifier-clicks)
     to play an exit animation before navigating
*/
(function(){
  'use strict';
  var D = document;
  var body = D.body || D.documentElement;
  var TRANS_MS = 200; // shorter delay to match quicker CSS transition (180ms + buffer)
  // Loader elements
  var loader = null;
  var loaderBar = null;
  var loaderSpinner = null;
  var overlay = null;
  var overlaySpinner = null;
  var overlayText = null;

  function createLoader(){
    if(loader) return;
    loader = D.createElement('div'); loader.id = 'page-loader';
    loaderBar = D.createElement('div'); loaderBar.className = 'bar'; loader.appendChild(loaderBar);
    loaderSpinner = D.createElement('div'); loaderSpinner.className = 'spinner';
    // create centered overlay loader
    overlay = D.createElement('div'); overlay.id = 'page-loader-overlay';
    var overlayInner = D.createElement('div'); overlayInner.className = 'overlay-inner';
    var overlayBackdrop = D.createElement('div'); overlayBackdrop.className = 'overlay-backdrop';
    overlaySpinner = D.createElement('div'); overlaySpinner.className = 'overlay-spinner';
    overlayText = D.createElement('div'); overlayText.className = 'overlay-text'; overlayText.textContent = 'Loading…';
    overlayInner.appendChild(overlaySpinner);
    overlayInner.appendChild(overlayText);
    overlay.appendChild(overlayBackdrop);
    overlay.appendChild(overlayInner);

    D.body.appendChild(loader);
    D.body.appendChild(loaderSpinner);
    D.body.appendChild(overlay);
  }

  function showLoader(){
    createLoader();
    loader.style.display = '';
    loaderSpinner.style.display = '';
    if(overlay) overlay.style.display = 'flex';
    if(overlaySpinner) overlaySpinner.style.display = '';
    if(overlayText) overlayText.style.display = '';
    // animate to 60% quickly, then slowly to 90% until navigation
    loaderBar.style.width = '0%';
    requestAnimationFrame(function(){ loaderBar.style.width = '60%'; });
    // second step: gentle approach to 90% over a longer period
    setTimeout(function(){ loaderBar.style.transition = 'width 800ms linear'; loaderBar.style.width = '90%'; }, 220);
  }

  function hideLoader(){
    if(!loader) return;
    // complete and fade
    loaderBar.style.transition = 'width 160ms linear';
    loaderBar.style.width = '100%';
    setTimeout(function(){
      if(loader) loader.style.display = 'none';
      if(loaderSpinner) loaderSpinner.style.display = 'none';
      if(overlay) overlay.style.display = 'none';
      if(overlaySpinner) overlaySpinner.style.display = 'none';
      if(overlayText) overlayText.style.display = 'none';
      // reset to initial
      loaderBar.style.transition = 'width 220ms linear';
      loaderBar.style.width = '0%';
    }, 180);
  }

  function onReady(){
    // Mark page as ready so CSS can animate entrance
    requestAnimationFrame(function(){
      body.classList.add('page-ready');
    });
    // Ensure loader DOM exists early so CSS fallback can show it immediately
    try{ createLoader(); }catch(e){/* ignore */}
  }

  function isSameOrigin(href){
    try{ var url = new URL(href, location.href); return url.origin === location.origin; } catch(e){ return false; }
  }

  function shouldInterceptLink(a){
    if(!a || !a.getAttribute) return false;
    var href = a.getAttribute('href') || '';
    if(!href) return false;
    // allow anchors on same page
    if(href.charAt(0) === '#') return false;
    // allow mailto/tel/javascript
    if(href.indexOf('mailto:') === 0 || href.indexOf('tel:') === 0 || href.indexOf('javascript:') === 0) return false;
    if(!isSameOrigin(href)) return false;
    // allow resources (images, downloads) that end with an extension not html/php
    var ext = href.split('.').pop().split(/[?#]/)[0].toLowerCase();
    var nonHtmlExts = ['png','jpg','jpeg','gif','svg','pdf','zip','mp4','webm','mp3'];
    if(nonHtmlExts.indexOf(ext) !== -1) return false;
    return true;
  }

  function bindLinks(){
    D.addEventListener('click', function(e){
      var a = e.target.closest && e.target.closest('a');
      if(!a) return;
      if(!shouldInterceptLink(a)) return;
      // respect modifier keys and middle click
      if(e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
      e.preventDefault();
      var to = a.href;
      // trigger loader and exit animation
      showLoader();
      body.classList.remove('page-ready');
      body.classList.add('page-exit');
      setTimeout(function(){ location.href = to; }, TRANS_MS);
    }, {capture:false});
  }

  if(D.readyState === 'loading') D.addEventListener('DOMContentLoaded', function(){ onReady(); bindLinks(); }); else { onReady(); bindLinks(); }

})();
