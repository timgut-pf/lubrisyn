document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.main-nav');

    if (toggle && nav) {
        toggle.addEventListener('click', function() {
            const isOpen = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen);
            
            const icon = toggle.querySelector('.dashicons');
            if (isOpen) {
                icon.classList.replace('dashicons-menu', 'dashicons-no-alt');
            } else {
                icon.classList.replace('dashicons-no-alt', 'dashicons-menu');
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Target the "Shop" menu item specifically or any item with a sub-menu
    const shopParent = document.querySelector('.menu-item-has-children > a[href*="shop"]');

    if (shopParent) {
        shopParent.addEventListener('click', function(e) {
            // Check if we are on a screen size where we want click-to-open
            // Usually, this is essential for mobile and preferred for some desktop UX
            e.preventDefault();
            
            const parentLi = this.parentElement;
            const isOpen = parentLi.classList.contains('sub-menu-open');

            // Close other open sub-menus if necessary
            document.querySelectorAll('.menu-item-has-children').forEach(item => {
                item.classList.remove('sub-menu-open');
            });

            if (!isOpen) {
                parentLi.classList.add('sub-menu-open');
            }
        });
    }
});

