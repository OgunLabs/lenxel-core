/**
 * Lenxel Core - Promotion Dialog Functionality
 * WordPress-compliant dialog initialization
 */
(function() {
   'use strict';

   console.log('[1] Initial script load - defining closeDialogPro function');
   
   // Define close dialog function GLOBALLY - MUST work
   window.closeDialogPro = function() {
      console.log('[CLOSE] === closeDialogPro CALLED ===');
      var dialog = document.querySelector('.dialog-premium-lenxel');
      console.log('[CLOSE] Dialog element found:', !!dialog);
      if (dialog) {
         dialog.style.display = 'none';
         console.log('[CLOSE] Dialog display set to none');
      }
      return false;
   };
   
   console.log('[2] closeDialogPro function defined, type:', typeof window.closeDialogPro);
   
   // Try to trigger on EVERY element click IMMEDIATELY
   document.addEventListener('click', function(e) {
      console.log('[CLICK] Click detected on:', e.target.tagName, e.target.className);
      if (e.target && (e.target.classList.contains('eicon-close') || e.target.closest('.eicon-close'))) {
         console.log('[CLICK] Close button clicked!');
         console.log('[CLICK] Calling closeDialogPro directly...');
         window.closeDialogPro();
      }
   }, true); // Capture phase - fires FIRST
   
   console.log('[3] Global click listener attached');
   
   jQuery(document).ready(function($) {
      console.log('[4] jQuery ready fired');
      
      // Build dialog HTML
      var dialogHTML = '<div class="dialog-widget dialog-buttons-widget dialog-type-buttons dialog-premium-lenxel" id="elementor-element--promotion__dialog" aria-modal="true" role="document" tabindex="0" style="top: 350px; left: 276px; display: none; position: fixed; background: #1f2124; border: 1px solid #1f2124; padding: 20px; border-radius: 5px; z-index: 10000;"><div class="dialog-header dialog-buttons-header dialog-premium-lenxel-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;"><div id="elementor-element--promotion__dialog__title" class="dialog-premium-lenxel-title">Testimonial Carousel Widget</div><i class="eicon-pro-icon" style="flex-grow: 1; margin-left: 5px;margin-right:10px; font-size: 14px; color: #93003c;"></i><i class="eicon-close" onclick="window.closeDialogPro(); return false;" style="cursor: pointer; font-size: 20px; pointer-events: auto; user-select: none; color: #fff;"></i></div><div class="dialog-message dialog-buttons-message dialog-premium-lenxel-message" style="margin-bottom: 15px;">Use Testimonial Carousel widget and dozens more pro features to extend your toolbox and build sites faster and better.</div><div class="dialog-buttons-wrapper dialog-buttons-buttons-wrapper"><a href="https://lenxel.ai/" target="_blank" class="elementor-button go-pro dialog-button dialog-action dialog-buttons-action" style="display: inline-block; padding: 10px 20px; background: orange; color: white; text-decoration: none; border-radius: 3px;">Upgrade to Pro</a></div></div>';
      
      jQuery('body').append(dialogHTML);
      console.log('[5] Dialog appended to body');
      
      // Verify element exists
      var closeBtn = document.querySelector('.dialog-premium-lenxel .eicon-close');
      console.log('[5a] Close button exists:', !!closeBtn);
      if (closeBtn) {
         console.log('[5b] Close button onclick attr:', closeBtn.getAttribute('onclick'));
         console.log('[5c] Close button style:', closeBtn.style.cssText);
         
         // Add redundant handler as fallback
         closeBtn.addEventListener('click', function(e) {
            console.log('[HANDLER] Direct addEventListener fired');
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            window.closeDialogPro();
            return false;
         }, true);
         console.log('[5d] Direct addEventListener attached to close button');
      }
      
      // jQuery delegation backup
      $(document).on('click', '.eicon-close', function(e) {
         console.log('[JQUERY] jQuery click handler fired on .eicon-close');
         e.preventDefault();
         e.stopPropagation();
         window.closeDialogPro();
         return false;
      });
      console.log('[6] jQuery event delegation attached');
      
      // Outside click - close dialog
      $('body').on('click', function(e) {
         var dialog = $('.dialog-premium-lenxel');
         if (dialog.is(':visible') && !$(e.target).closest('.dialog-premium-lenxel').length) {
            dialog.css('display', 'none');
            console.log('[OUTSIDE] Dialog closed by outside click');
         }
      });
      console.log('[7] Setup complete');
   });

})();
