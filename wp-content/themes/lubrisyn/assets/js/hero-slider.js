(function($){
    var initializeBlock = function( $block ) {
        var $slider = $block.find('.hero-swiper');
        if ($slider.length) {
            new Swiper($slider[0], {
                loop: true,
                effect: 'fade',
                fadeEffect: { crossFade: true },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                speed: 1500,
            });
        }
    }

    // Initialize each block on page load (Frontend)
    $(document).ready(function(){
        $('.hp-hero').each(function(){
            initializeBlock( $(this) );
        });
    });

    // Initialize block preview (Gutenberg Editor)
    if( window.acf ) {
        window.acf.addAction( 'render_block_preview/type=hero-slider', initializeBlock );
    }
})(jQuery);