
/* Native slider fallback for AB Employee Carousel - provides basic sliding, autoplay, dots & nav */
(function(){
    function initNativeCarousel(rootId, options) {
        var root = document.getElementById(rootId);
        if(!root) return;
        var track = root.querySelector('.abegc-slides') || root.querySelector('.swiper-wrapper');
        var slides = root.querySelectorAll('.abegc-slide, .swiper-slide');
        var prev = root.querySelector('.abegc-prev, .swiper-button-prev');
        var next = root.querySelector('.abegc-next, .swiper-button-next');
        var dotsEl = root.querySelector('.abegc-dots, .swiper-pagination');
        var perView = options.perView || 3;
        var gap = options.gap || 20;
        var idx = 0, timer=null;
        function layout(){
            var w = root.clientWidth;
            perView = w < 640 ? 1 : (w < 1024 ? Math.min(2, options.perView) : options.perView);
            slides.forEach(function(s){ s.style.minWidth = (100 / perView) + '%'; s.style.marginRight = gap + 'px'; });
            go(idx, true);
        }
        function go(i, immediate){
            if(slides.length === 0) return;
            if(i<0) i = slides.length - 1;
            if(i>=slides.length) i = 0;
            idx = i;
            var offset = idx * (track.scrollWidth / slides.length);
            track.scrollTo({left: offset, behavior: immediate? 'auto':'smooth'});
            updateDots();
        }
        function updateDots(){
            if(!dotsEl) return;
            if(!dotsEl.dataset.ready){
                slides.forEach(function(_,i){
                    var b=document.createElement('button');
                    b.type='button'; b.className='abegc-dot'; b.setAttribute('aria-label','Go to slide '+(i+1));
                    b.addEventListener('click', function(){ stop(); go(i); start(); });
                    dotsEl.appendChild(b);
                });
                dotsEl.dataset.ready='1';
            }
            var dots = dotsEl.querySelectorAll('.abegc-dot');
            dots.forEach(function(d,i){ d.classList.toggle('active', i===idx); });
        }
        function start(){ if(!options.autoplay) return; stop(); timer = setInterval(function(){ go(idx+1); }, options.delay||4000); }
        function stop(){ if(timer){ clearInterval(timer); timer=null; } }
        if(prev) prev.addEventListener('click', function(){ stop(); go(idx-1); start(); });
        if(next) next.addEventListener('click', function(){ stop(); go(idx+1); start(); });
        if(options.pauseOnHover){
            root.addEventListener('mouseenter', stop);
            root.addEventListener('mouseleave', start);
        }
        window.addEventListener('resize', layout, {passive:true});
        layout(); start();
    }

    // Expose a global initializer used by PHP output
    window.abegcNativeInit = initNativeCarousel;
})();
