/**
 * Lenxel - Category Carousel Initialization
 */
(function($) {
    'use strict';
    
    function initCategoryCarousel($carousel) {
        if (!$carousel.length || $carousel.hasClass('owl-loaded')) {
            return;
        }
        
        var config = $carousel.data('carousel-config');
        if (!config) {
            return;
        }
        
        $carousel.owlCarousel(config);
        
        // Update nav icons
        var randomId = $carousel.data('carousel-id');
        if (randomId) {
            $('.owl-carousel.stag' + randomId + ' .owl-nav .owl-prev').html('<i class="las la-arrow-left"></i>');
            $('.owl-carousel.stag' + randomId + ' .owl-nav .owl-next').html('<i class="las la-arrow-right"></i>');
        }
    }
    
    $(document).ready(function() {
        // Initialize all category carousels
        $('.lnx-category-carousel-init').each(function() {
            initCategoryCarousel($(this));
        });
    });
    
    // Re-initialize for dynamically loaded content (Elementor preview)
    $(window).on('elementor/frontend/init', function() {
        setTimeout(function() {
            $('.lnx-category-carousel-init').each(function() {
                initCategoryCarousel($(this));
            });
        }, 1000);
    });
    
})(jQuery);
