(function($){
    var initializeBlock = function( $block ) {
        var $sliderContainer = $block.find('.hero-swiper');
        var $paginationContainer = $block.find('.hero-dots');

        if ($sliderContainer.length) {
            new Swiper($sliderContainer[0], {
                loop: true,
                effect: 'fade',
                fadeEffect: { crossFade: true },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                speed: 1500,
                pagination: {
                    el: $paginationContainer[0],
                    clickable: false,
                    type: 'bullets',
                },
                on: {
                    slideChange: function () {
                        var swiper = this;
                        
                        // 1. Get the real index (0, 1, 2...) regardless of clones
                        var realIndex = swiper.realIndex;
                        
                        // 2. Find the slide that matches the ORIGINAL data (not the clone)
                        // This ensures we get the text even if we are currently on a cloned slide
                        var $actualSlide = $(swiper.el).find('.swiper-slide[data-swiper-slide-index="' + realIndex + '"]').first();
                        
                        var newTitle = $actualSlide.attr('data-title');
                        var newSub   = $activeSlide.attr('data-subtitle');
                
                        // 3. Find the targets specifically within the parent block
                        var $title = $block.find('.js-dynamic-title');
                        var $subtitle = $block.find('.js-dynamic-subtitle');
                
                        // 4. Force the change
                        if ($title.length) {
                            $title.stop(true, true).animate({ opacity: 0 }, 300, function(){
                                $(this).text(newTitle).animate({ opacity: 1 }, 300);
                            });
                            $subtitle.stop(true, true).animate({ opacity: 0 }, 300, function(){
                                $(this).text(newSub).animate({ opacity: 1 }, 300);
                            });
                        }
                    }
                }
            });
        }
    }

    $(document).ready(function(){
        $('.hp-hero').each(function(){
            initializeBlock( $(this) );
        });
    });

    if( window.acf ) {
        window.acf.addAction( 'render_block_preview/type=hero-slider', function($block){
            initializeBlock($block);
        });
    }
})(jQuery);