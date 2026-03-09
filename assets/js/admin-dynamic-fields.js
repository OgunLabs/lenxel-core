/**
 * Lenxel Core - Dynamic Form Fields
 * Handles add/remove functionality for repeatable fields in admin
 */
(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Initialize all dynamic form fields
        $('.lenxel-dynamic-field-group').each(function() {
            var $group = $(this);
            var config = $group.data('field-config');
            
            if (!config) {
                return;
            }
            
            var count = parseInt(config.count) || 0;
            var $addBtn = $group.find('.lenxel-add-field-item');
            var $list = $group.find('.lenxel-field-list');
            
            // Add item handler
            $addBtn.on('click', function(e) {
                e.preventDefault();
                count = count + 1;
                
                var html = config.template.replace(/\{count\}/g, count);
                $list.append(html);
                
                return false;
            });
            
            // Remove item handler (delegated)
            $group.on('click', '.lenxel-remove-field', function(e) {
                e.preventDefault();
                $(this).closest('p').remove();
                return false;
            });
        });
    });
    
})(jQuery);
