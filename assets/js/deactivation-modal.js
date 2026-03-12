/**
 * Lenxel Core - Deactivation Modal Handler
 * Handles the plugin deactivation feedback modal
 * Only shows modal if user has opted-in to feedback collection
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Cache DOM elements
        const $deactivateLink = $('#the-list').find('[data-slug="lenxel-core"] span.deactivate a');
        const $modal = $('.modal');
        const $overlay = $('.overlay');
        const $skipButton = $('#lenxelSkipDeactivation');
        const $confirmButton = $('#lenxelConfirmDeactivation');
        const $closeButton = $('.btn-close');
        const $form = $modal.find('form');
        
        // Store the original deactivation URL
        let deactivationUrl = '';
        
        // Check if feedback is enabled via data attribute
        const feedbackEnabled = $modal.data('feedback-enabled') || false;

        // Intercept deactivate link click
        $deactivateLink.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Store the deactivation URL
            deactivationUrl = $(this).attr('href');
            
            // Only show modal if feedback is enabled (user has opted-in)
            if (feedbackEnabled) {
                // Show modal
                $modal.removeClass('hidden');
                $overlay.removeClass('hidden');
            } else {
                // Feedback not enabled - proceed directly to deactivation
                window.location.href = deactivationUrl;
            }
            
            return false;
        });

        // Close modal function
        function closeModal() {
            $modal.addClass('hidden');
            $overlay.addClass('hidden');
            
            // Reset form
            $form[0].reset();
            $('.betterPlugin, .feedbackOther').hide();
            
            // Re-enable submit button
            $confirmButton.prop('disabled', false).text('Submit & Deactivate');
        }

        // Close button click
        $closeButton.on('click', function(e) {
            e.preventDefault();
            closeModal();
        });

        // Overlay click to close
        $overlay.on('click', function() {
            closeModal();
        });

        // Skip button - just deactivate without feedback
        $skipButton.on('click', function(e) {
            e.preventDefault();
            
            // Disable button to prevent double clicks
            $skipButton.prop('disabled', true).text('Skipping...');
            
            // Send skip notification
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'lenxel_deactivate_plugin',
                    skip: true,
                    _wpnonce: $form.find('input[name="_wpnonce"]').val()
                },
                success: function(response) {
                    console.log('Skip response:', response);
                    // Redirect to deactivation URL
                    window.location.href = deactivationUrl;
                },
                error: function(xhr, status, error) {
                    console.error('Skip AJAX error:', error);
                    // Even if AJAX fails, still deactivate
                    window.location.href = deactivationUrl;
                }
            });
        });

        // Form submission - send feedback then deactivate
        $form.on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Get selected feedback
            const selectedCause = $form.find('input[name="feedback[cause]"]:checked').val();
            
            if (!selectedCause) {
                alert('Please select a reason for deactivation');
                return false;
            }

            // Get additional comment if applicable
            let comment = '';
            if ($('#lenxel-cause-05').is(':checked')) {
                comment = $('.betterPlugin').val();
            } else if ($('#lenxel-cause-07').is(':checked')) {
                comment = $('.feedbackOther').val();
            }

            // Get email if checkbox is checked
            const includeEmail = $('#lenxel-include-email').is(':checked');
            const email = includeEmail ? $('#lenxel-include-email').val() : '';

            // Disable submit button to prevent double submission
            $confirmButton.prop('disabled', true).text('Submitting...');

            // Build AJAX data
            const ajaxData = {
                action: 'lenxel_deactivate_plugin',
                feedback: selectedCause,
                comment: comment,
                email: email,
                _wpnonce: $form.find('input[name="_wpnonce"]').val()
            };

            console.log('Sending feedback data:', ajaxData);

            // Send feedback via AJAX
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: ajaxData,
                success: function(response) {
                    console.log('Feedback response:', response);
                    // Small delay to ensure data is sent
                    setTimeout(function() {
                        // Redirect to deactivation URL
                        window.location.href = deactivationUrl;
                    }, 500);
                },
                error: function(xhr, status, error) {
                    console.error('Feedback AJAX error:', error);
                    console.error('Response:', xhr.responseText);
                    // Even if AJAX fails, still deactivate after a moment
                    setTimeout(function() {
                        window.location.href = deactivationUrl;
                    }, 500);
                }
            });

            return false;
        });

        // Show/hide conditional input fields based on radio selection
        $form.find('input[type="radio"]').on('change', function() {
            // Hide all conditional inputs first
            $('.betterPlugin, .feedbackOther').hide();
            
            // Show relevant input based on selection
            if ($('#lenxel-cause-05').is(':checked')) {
                $('.betterPlugin').show().focus();
            } else if ($('#lenxel-cause-07').is(':checked')) {
                $('.feedbackOther').show().focus();
            }
        });

        // ESC key to close modal
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && !$modal.hasClass('hidden')) {
                closeModal();
            }
        });
    });

})(jQuery);
