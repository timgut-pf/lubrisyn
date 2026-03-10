document.addEventListener('DOMContentLoaded', function() {
    const reviewSwiper = new Swiper('.reviews-swiper', {
        slidesPerView: 2,
        spaceBetween: 30,
        loop: true,
         autoplay: {
            delay: 5000, // Time in ms (3 seconds)
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            // When window width is >= 1200px
            1200: {
                slidesPerView: 2,
            },
            0: {
                slidesPerView: 1,
            }
            
        }
    });
});