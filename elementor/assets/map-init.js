/**
 * Lenxel Google Maps Initialization
 * WordPress-compliant map initialization using data attributes
 * 
 * Note: lenxelGoogleMapsLoaded callback is defined inline before this script loads
 */

(function($) {
    'use strict';

    /**
     * Initialize a single Google Map
     * @param {jQuery} $mapElement - The map container element
     */
    function initializeMap($mapElement) {
        if (!$mapElement.length) {
            console.warn('Map element not found');
            return;
        }

        if (!window.google || !window.google.maps || !window.google.maps.Map) {
            console.warn('Google Maps API not loaded');
            return;
        }

        try {
            var configData = $mapElement.attr('data-map-config');
            if (!configData) {
                console.warn('No map configuration found');
                return;
            }

            var config = JSON.parse(configData);
            
            // Parse styles if it's a string
            if (typeof config.styles === 'string') {
                try {
                    config.styles = JSON.parse(config.styles);
                } catch(e) {
                    console.warn('Failed to parse map styles', e);
                    config.styles = [];
                }
            }

            // Get map type ID from string
            var mapTypeId = google.maps.MapTypeId.ROADMAP; // default
            if (config.mapTypeId && google.maps.MapTypeId[config.mapTypeId]) {
                mapTypeId = google.maps.MapTypeId[config.mapTypeId];
            }

            // Initialize map using jQuery UI Map plugin
            $mapElement.gmap({
                'scrollwheel': config.scrollwheel || false,
                'zoom': parseInt(config.zoom) || 14,
                'center': config.center || '0,0',
                'mapTypeId': mapTypeId,
                'styles': config.styles || [],
                'panControl': config.panControl !== false,
                'callback': function() {
                    var self = this;
                    
                    // Add marker if configured
                    if (config.marker && config.marker.position) {
                        var marker = {
                            position: config.marker.position
                        };
                        
                        var markerObj = self.addMarker(marker);
                        
                        // Add click handler if there's a title
                        if (config.marker.title && markerObj) {
                            markerObj.on('click', function() {
                                if (config.marker.title) {
                                    self.openInfoWindow({
                                        'content': config.marker.title
                                    }, this.instance.markers[0]);
                                }
                            });
                        }
                    }
                }
            });

        } catch (error) {
            console.error('Error initializing map:', error);
        }
    }

    /**
     * Initialize all maps on the page
     */
    function initializeAllMaps() {
        $('.lnx-gmap-init').each(function() {
            var $this = $(this);
            
            // Skip if already initialized
            if ($this.hasClass('lnx-gmap-initialized')) {
                return;
            }
            
            initializeMap($this);
            $this.addClass('lnx-gmap-initialized');
        });
    }

    /**
     * Wait for Google Maps API to be fully loaded using the callback
     */
    function waitForGoogleMaps(callback, maxAttempts) {
        if (window.lenxelGoogleMapsReady) {
            callback();
            return;
        }

        maxAttempts = maxAttempts || 100; // Maximum 10 seconds (100 * 100ms)
        var attempts = 0;

        var checkInterval = setInterval(function() {
            attempts++;
            
            if (window.lenxelGoogleMapsReady || (window.google && window.google.maps && window.google.maps.Map)) {
                clearInterval(checkInterval);
                window.lenxelGoogleMapsReady = true;
                callback();
            } else if (attempts >= maxAttempts) {
                clearInterval(checkInterval);
                console.error('Google Maps API failed to load within timeout');
            }
        }, 100);
    }

    // Initialize on document ready
    $(document).ready(function() {
        // Wait a bit for scripts to load
        setTimeout(function() {
            waitForGoogleMaps(function() {
                initializeAllMaps();
            });
        }, 100);
    });

    // Listen for the custom event from the callback
    $(document).on('lenxel-google-maps-ready', function() {
        initializeAllMaps();
    });

    // Also initialize for dynamically loaded content (Elementor preview)
    $(window).on('elementor/frontend/init', function() {
        waitForGoogleMaps(function() {
            initializeAllMaps();
        });
    });

    // Re-initialize on window load as a fallback
    $(window).on('load', function() {
        setTimeout(function() {
            waitForGoogleMaps(function() {
                initializeAllMaps();
            });
        }, 500);
    });

})(jQuery);
