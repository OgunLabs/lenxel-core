/**
 * Lenxel - Testimonials Style 4 Scripts
 */
(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Update current slide content
        var updateCurrentSlide = function() {
            var slideContent = $('.resize-height .owl-stage-outer .owl-item.active.first .content-profile').html();
            if (slideContent) {
                $('.current-slide').html(slideContent);
            }
        };
        
        // Update initially
        setTimeout(updateCurrentSlide, 500);
        
        // Set interval to keep updating
        setInterval(updateCurrentSlide, 1000);
    });
    
})(jQuery);
