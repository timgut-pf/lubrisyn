(function($){
    var initializeBlock = function( $block ) {
        var $slider = $block.find('.hero-swiper');
        var titleEl = $block.find('.js-dynamic-title')[0];
        var subEl = $block.find('.js-dynamic-subtitle')[0];
        if (!$slider.length) return;

        new Swiper($slider[0], {
            loop: true,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            speed: 800,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            pagination: {
                el: $block.find('.hero-dots')[0],
                clickable: true,
            },
            on: {
                slideChangeTransitionStart: function () {
                    var activeSlide = this.slides[this.activeIndex];
                    var newTitle = activeSlide ? activeSlide.getAttribute('data-title') : '';
                    var newSub = activeSlide ? activeSlide.getAttribute('data-subtitle') : '';
                    if (titleEl) titleEl.textContent = newTitle || '';
                    if (subEl) subEl.textContent = newSub || '';
                },
            },
        });
     }

    $(document).ready(function(){
        $('.hp-hero').each(function(){
            initializeBlock( $(this) );
        });
    });

    if ( window.acf ) {
        window.acf.addAction( 'render_block_preview/type=hero-slider', initializeBlock );
    }
})(jQuery);