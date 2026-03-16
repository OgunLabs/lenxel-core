=== Lenxel AI LMS - Course Lesson Generator ===
Contributors: ogunlab
Tags: learning management system, LMS, education, elearning, online courses
Requires at least: 6.5
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.3.8
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
Version: 1.3.8
Icon: assets/logo.png

Lenxel AI LMS is a WordPress plugin that provides a comprehensive Learning Management System with AI-assisted course creation.


== Description ==
Lenxel Core AI LMS is a WordPress LMS plugin that combines AI with course building tools. It is suitable for creating online academies, corporate training platforms, or educational websites, providing tools to deliver learning experiences.

**Key Features:**
* **AI Course Generation**: Use artificial intelligence to create course modules and lessons automatically
* **LMS System**: Learning management system with learner tracking, progress monitoring, and certification
* **Course Builder**: Build courses without coding using visual editors
* **Header & Footer Builder**: Create custom headers and footers with Elementor integration
* **Team & Portfolio Management**: Showcase instructors and course portfolios
* **Theme Integration**: Integration with Lenxel WordPress theme for cohesive design
* **WooCommerce Integration**: Sell courses with built-in e-commerce functionality
* **Tutor LMS Compatibility**: Enhanced features when used with Tutor LMS plugin

**Suitable for:**
* Online course creators
* Educational institutions
* Corporate training departments
* Coaching and mentoring businesses
* Professional certification programs
* Language learning platforms
* Skill development courses

Create a learning platform with Lenxel Core - an AI-assisted LMS solution.

== Features ==
* Create AI course modules and lessons automatically with WordPress LMS capabilities
* Team post-type for showcasing team members
* Portfolio post-type for displaying work
* Build custom headers with the Header builder
* Build unique footers with the Footer builder
* Customize Lenxel theme settings
* Create a Sign-in page for users
* Manage course categories with different style options using Elementor elements
* Customizable styling for active/enrolled courses using Elementor elements

== Source Code ==
The source code for this plugin, including uncompressed versions of JavaScript and CSS files, is available on GitHub: https://github.com/OgunLabs/lenxel-core

This repository contains the build tools and source files used to generate the compressed assets included in the plugin.

== Advanced features (Coming to Pro) ==
* Course coupon
* Live chat support inside the plugin
* Course retake
* Frontend course creation

== Installation ==
1. Log in to your WordPress Admin area.
2. Navigate to the "Plugins" section and click on "Add New".
3. In the search bar, type "Lenxel Core" and press Enter.
4. Once the installation is complete, click on the "Activate" button to activate the plugin.

== Lenxel theme settings ==
1.  Get started
2.  General options
3.  Header options
4.  Breadcrumb options
5.  Styling
6.  Typography
7.  Blog options
8.  Page options
9.  Course options
10.  Product options
11. Dashboard options
12. Demo importer
13. Import / Export
14. Go ahead to take a test.

== Plugin configuration ==
* Setting up your Google Map API key.

== Feedback ==
Help improve Lenxel Core by optionally sharing deactivation feedback. This feature is **disabled by default** and requires opt-in via LenxelWP → Privacy & Feedback. See the "External Services" section below for complete details on what data is collected and how it's used.

== Plugin prerequisites ==
1. Ensure that the WooCommerce plugin is installed and activated on your WordPress website.
2. Install the Tutor plugin to manage curriculum, quizzes, and curriculum assignments.
3. Install the Lenxel theme template to complete the setup.

== External services ==

This plugin relies on several external services to provide its functionality. Below is a complete list of all external services used, including comprehensive details about what data is sent, when it's sent, code locations, and links to each service's Terms of Service and Privacy Policy.

**IMPORTANT DISTINCTION:** This section documents actual external API services that receive data during plugin operation. URLs that appear in third-party vendor library code (like documentation links, comments, or unused features) are addressed separately at the end of this section under "Third-Party Vendor Library Code."

= 1. Lenxel AI API =
**Service URL:** https://api.lenxel.ai (production) and https://devapi.lenxel.ai (development)
**Service Provider:** Lenxel (Ogun Labs)

**Purpose:** Generates AI-powered course content including course modules, lessons, quizzes, and questions automatically.

**What Data is Sent:** When users click the "Generate with AI" button or request AI course generation, the following data is transmitted:
- Course title and description
- Supporting files and course prompts provided by the user
- API Key (obtained from the Lenxel User Portal - see below) for authentication and credit tracking
- WordPress site URL (for API key validation)

**When Data is Sent:** Only when users explicitly request AI course generation through the plugin interface, or when API key validation is performed.

**API Endpoints Used:**
- `https://api.lenxel.ai/wp/sites/status/verify` - API key validation (optional, informational only)
- `https://api.lenxel.ai/` - Course generation endpoints
- All requests are sent via `wp_remote_post()` and `wp_remote_get()`

**Code Location:** `lenxel-core.php` (line 502 and related functions) - Uses `wp_remote_post()` to send course data to Lenxel API endpoints

**IMPORTANT: API Key Validation**
When users optionally enter an API key, the plugin may send a validation request to https://api.lenxel.ai/wp/sites/status/verify to verify the key is valid. This validation is informational only and does NOT gate or restrict any plugin features. All functionality is fully available regardless of API key validation status.

**Note:** API keys and AI credits are managed through the Lenxel User Portal (https://portal.lenxel.ai) - see section 5 below for details.

**Privacy & Terms:**
- Terms of Service: https://lenxel.ai/terms-of-service
- Privacy Policy: https://lenxel.ai/privacy-and-policy
- Service provided by: Ogun Labs (https://www.devteamsondemand.com/)

= 2. Google Maps JavaScript API =
**Service URL:** https://maps.googleapis.com/maps/api/js
**Service Provider:** Google LLC

**Purpose:** Loads the Google Maps JavaScript API for two purposes:
1. Displaying interactive maps in course location widgets and venue displays (Elementor integration)
2. Redux Framework admin options (vendor library) - provides map field type for theme/plugin settings

**What Data is Sent:** 
- Google Maps API key (configured by site administrator)
- Map location coordinates and venue addresses (when displaying course locations)
- User's IP address (automatically sent by Google's servers via standard HTTP requests)
- Browser information (standard HTTP headers)
- Map interactions (clicks, zooms, searches) sent to Google to retrieve map tiles and geocoding data

**When Data is Sent:** 
- Frontend: When pages with map widgets are loaded, or when users interact with location-based features
- Admin: When WordPress admin pages containing Redux Framework Google Maps fields are accessed

**Code Locations:**
- Redux Framework: `redux/redux-framework/inc/extensions/google_maps/google_maps/class-redux-google-maps.php` (line 357 - wp_register_script; line 367 - Google Maps API inline script loader)
- Elementor/theme integrations: Course location displays

**Technical Details:**
The Redux Framework Google Maps extension registers a JavaScript file (line 357) and adds an inline script (lines 365-371) that dynamically loads the Google Maps JavaScript API from `https://maps.googleapis.com/maps/api/js` with the configured API key. This allows the map field to function in WordPress admin theme settings.

**Privacy & Terms:**
- Google Maps Platform Terms of Service: https://cloud.google.com/maps-platform/terms
- Google Privacy Policy: https://policies.google.com/privacy

= 3. Google Fonts API =
**Service URL:** https://fonts.googleapis.com/css

**Purpose:** Loads custom typography fonts for theme styling and Redux Framework typography options.

**Data Sent:**
- Font family names requested by the theme
- User's IP address and browser information (automatically sent by the browser)

**When Data is Sent:** When pages load that use custom Google Fonts configured in theme settings.

**Privacy & Terms:**
- Google Fonts FAQ: https://developers.google.com/fonts/faq/privacy
- Google Privacy Policy: https://policies.google.com/privacy

= 4. Vimeo API =
**Service URL:** https://vimeo.com/api/v2/video/

**Purpose:** Retrieves video thumbnail images for Vimeo videos embedded in course lessons and course builder interface.

**Data Sent:**
- Vimeo video ID (extracted from the video URL provided by the course creator)
- User's IP address and browser information (automatically sent by the browser)

**When Data is Sent:** When course creators add Vimeo videos to lessons in the course builder, the plugin fetches the video thumbnail to display a preview image.

**Privacy & Terms:**
- Vimeo Terms of Service: https://vimeo.com/terms
- Vimeo Privacy Policy: https://vimeo.com/privacy

= 5. Lenxel User Portal =
**Service URL:** https://portal.lenxel.ai, https://devapi.lenxel.ai/

**Purpose:** Manages user accounts, AI credits, and API keys for the AI course generation feature. This portal is where users obtain API keys and purchase AI credits (after they have exhausted their free credits) that are consumed when using the "Generate with AI" functionality (see Lenxel AI API above).

**Data Sent:**
- User email and password (for account authentication)
- AI credit balance requests
- API key validation requests

**When Data is Sent:** 
- When users check their AI credit balance in the plugin
- When users access their account dashboard through the portal
- When users obtain or validate API keys for AI course generation

**Privacy & Terms:**
- Terms of Service: https://lenxel.ai/terms-of-service
- Privacy Policy: https://lenxel.ai/privacy-and-policy

= 6. Redux.io Custom Fonts API (Third-Party Vendor Service) =
**Service URL:** https://redux.io/fonts

**Purpose:** This is a third-party service provided by Redux Framework (included as a vendor library in this plugin). The Redux Custom Fonts extension uses this API to convert uploaded font files into web-compatible formats when administrators upload custom fonts through the Redux Framework theme options panel.

**Data Sent:**
- Custom font files uploaded by site administrators (TTF, OTF, or WOFF format)
- Font metadata (font family name, font format)

**When Data is Sent:** Only when site administrators manually upload custom font files through the Redux Framework Custom Fonts extension in the plugin's theme settings panel. This service is NOT called during normal site operation or by site visitors.

**Important Notes:**
- This is a Redux Framework feature (third-party vendor code, not Lenxel Core)
- Only administrators with capability to manage theme options can trigger this service
- Font uploads are optional - the plugin functions normally without custom fonts
- Custom fonts can be disabled by not using the Redux Custom Fonts extension

**Privacy & Terms:**
- Redux Framework Repository: https://github.com/reduxframework/redux-framework
- Redux.io Website: https://redux.io
- Redux Framework is MIT Licensed (included as vendor code)
- Redux.io Terms of Service: https://redux.io/terms
- Redux.io Privacy Policy: https://redux.io/privacy

= 7. Feedback Notification Service (Deactivation Feedback) =
**Service URL:** https://form-submission-to-slack-notify-495600076509.us-central1.run.app
**Service Provider:** Lenxel (Ogun Labs) via Google Cloud Run
**Service Type:** Google Cloud Run endpoint that forwards feedback to Slack workspace

**Purpose:** Collects optional user feedback when site administrators deactivate the plugin. This feedback is used for commercial purposes to improve the plugin based on real user experiences and deactivation reasons.

**IMPORTANT - OPT-IN REQUIRED (Disabled by Default):**
This feature is **disabled by default** and requires explicit opt-in consent. To enable deactivation feedback:
1. Go to WordPress Admin → LenxelWP → Privacy & Feedback tab
2. Check the "Help improve Lenxel Core by sharing why you deactivate the plugin" checkbox
3. Click "Save Settings"

Without opting in, NO data is collected when you deactivate the plugin.

**What Data is Sent (Only if Opted-In and Only if User Chooses to Provide Feedback):**
Even after opting in, providing feedback is still optional. When the deactivation modal appears, users can:
- Click "Skip & Deactivate" to bypass feedback submission entirely
- Close the modal without submitting
- OR submit feedback by completing the form

If feedback is submitted via `wp_remote_post()`, the following data is sent to the Google Cloud Run endpoint:
- Deactivation reason selected from predefined choices (e.g., "I no longer need the plugin", "The plugin broke my website", "I found a better plugin", etc.)
- Optional additional comment or alternative plugin name (if user chooses to provide it)
- Site administrator's email address (only if user explicitly checks "Include my email" checkbox)
- WordPress site URL (home_url)
- Date and time of deactivation

**When Data is Sent:** 
1. User must first opt-in via LenxelWP → Privacy & Feedback (disabled by default)
2. Then, when a site administrator clicks the "Deactivate" button for this plugin in the WordPress plugins page, a feedback modal appears
3. User can still choose to skip or submit feedback
4. Data is only transmitted if the user completes the form and clicks "Submit & Deactivate"

**Code Location:** `lenxel-core.php` (lines 640-690) - Deactivation handler with opt-in check and wp_remote_post() to Google Cloud Run endpoint (line 684)

**How to Disable:**
To stop seeing the feedback modal entirely:
1. Go to LenxelWP → Privacy & Feedback
2. Uncheck the deactivation feedback option
3. Click "Save Settings"

You can also skip the modal on a per-deactivation basis by clicking "Skip & Deactivate" or closing the modal.

**Important Notes:**
- This is completely opt-in and disabled by default - you must explicitly enable it in plugin settings
- Even after enabling, providing feedback is optional on each deactivation
- The feedback modal only appears to site administrators with plugin management capabilities
- No data is transmitted if the user skips or closes the feedback form
- Email address is only sent if the user keeps the "Include my email" checkbox checked
- Data is used solely for improving the plugin and understanding user needs
- This can be disabled at any time from LenxelWP → Privacy & Feedback

**Privacy & Terms:**
- Lenxel Terms of Service: https://lenxel.ai/terms-of-service
- Lenxel Privacy Policy: https://lenxel.ai/privacy-and-policy
- Google Cloud Platform Privacy Policy: https://cloud.google.com/terms/cloud-privacy-notice
- Slack Platform Privacy Policy: https://slack.com/trust/privacy/privacy-policy
- Slack API Terms of Service: https://slack.com/terms-of-service/api
- Data is transmitted over secure HTTPS connection
- Feedback is processed through Google Cloud Run and forwarded to a private Slack workspace accessible only to Lenxel development team

= Third-Party Vendor Library Code (Not External Services) =

**IMPORTANT:** The following URLs appear in third-party vendor library code included with this plugin. These are NOT external API services called by this plugin - they are simply documentation URLs, comments, or dead code within vendor libraries. **NO data is transmitted to these URLs during plugin operation.**

**1. Vzaar API URL in Owl Carousel Library**
**Code Location:** `elementor/assets/libs/owl-carousel/owl.carousel.js` (line 2388)
**URL Found:** `url: '//vzaar.com/api/videos/' + video.id + '.json'`
**Library:** Owl Carousel 2.x (MIT Licensed)
**Status:** This is a hardcoded URL within the Owl Carousel JavaScript library's video support feature. The Vzaar video platform integration is NOT used or called by this plugin - it's simply part of the complete Owl Carousel library code that includes support for various video platforms (YouTube, Vimeo, Vzaar, etc.). The URL appears in a JavaScript object but the Vzaar feature is never invoked by this plugin.
**Data Sent:** NONE - This URL is never contacted during plugin operation.

**2. React Router Documentation URLs**
**Code Location:** `build/course-builder.js` (line 2 and other build files)
**URLs Found:** Documentation links such as:
- https://reactrouter.com/v6/upgrading/future#v7_starttransition
- https://reactrouter.com/v6/upgrading/future#v7_relativesplatpath
- https://reactrouter.com/v6/upgrading/future#v7_fetcherpersist
- https://reactrouter.com/v6/upgrading/future#v7_normalizeformmethod
- https://reactrouter.com/v6/upgrading/future#v7_partialhydration
- And other similar upgrade guide URLs

**Library:** React Router (MIT Licensed)
**Status:** These URLs are documentation/warning links embedded in the React Router library code that appear in built JavaScript bundles. They are used in console warnings to guide developers during React Router version upgrades. They are NOT API endpoints and do NOT receive any data during plugin operation. These are purely informational links that may appear in browser developer console warnings.
**Data Sent:** NONE - These are documentation reference links only.

**3. Redux Framework Vendor Code**
**Code Location:** `redux/redux-framework/` directory
**Library:** Redux Framework 4.x (MIT Licensed)
**Status:** The Redux Framework is a complete third-party library that includes various extensions and features. Some features (like Google Maps fields and Custom Fonts API) may reference external services, which are documented separately in sections 2 and 6 above where applicable.

**Important Notes:**
- All data transmissions to Lenxel services (AI API, User Portal) are sent over secure HTTPS connections.
- The Lenxel AI API and Lenxel User Portal work together: users obtain API keys from the Portal, which are then used to authenticate with the AI API for course generation.
- Users can opt out of AI features by simply not using the AI course generation functionality.
- Google Maps and Google Fonts can be disabled by not using map widgets and by selecting system fonts in theme settings.
- Vimeo API is only contacted when course creators add Vimeo videos to course lessons; no data is sent for regular site visitors.
- Redux.io Custom Fonts API is only contacted when administrators manually upload custom fonts through theme settings; this is optional Redux Framework vendor functionality.
- Feedback notification service (Google Cloud Run/Slack) is only contacted when administrators choose to submit deactivation feedback; this is completely optional and can be skipped.
- The plugin does NOT send any data to external services without user interaction or configuration.

== Developer Notes ==

**Redux Framework Build Scripts:**
This plugin includes Redux Framework as a library. The file `redux/redux-framework/inc/lib/get-font-classes.php` is a development build script used to generate Font Awesome icon class arrays during the plugin build process. 

In production (normal WordPress installations), this script does not write any files. The generated file `font-awesome-6-free.php` is pre-compiled and included in the plugin distribution.

If the script were to run (e.g., during development with REDUX_BUILD_MODE enabled), it would write to the WordPress uploads directory (`wp-content/uploads/redux-framework/`) rather than the plugin folder, in compliance with WordPress.org guidelines.

**File Storage:**
The plugin follows WordPress.org guidelines:
- No data is saved to the plugin folder during normal operation
- User-uploaded files (e.g., custom fonts) are saved to the WordPress uploads directory
- All settings and configuration data are stored in the WordPress database

== Screenshots ==
1. Course Builder Interface - Create courses with drag-and-drop functionality
2. AI Course Generation - Generate course content using artificial intelligence
3. Student Dashboard - Track progress and manage enrollments
4. Header & Footer Builder - Customize site's layout with visual editors
5. Team Management - Showcase instructors and staff members
6. Portfolio Display - Present course offerings

== Frequently Asked Questions ==
= Is Lenxel Core compatible with my WordPress theme? =
Lenxel Core works best with the Lenxel WordPress theme but is compatible with most modern WordPress themes.

= Does it support AI course generation? =
Yes! Lenxel Core includes AI course generation features to help you create course modules and lessons automatically.

= What is an AI learner? =
An AI learner is a student tracking system that uses artificial intelligence to monitor progress, provide recommendations, and improve the learning experience.

= How does the AI system work? =
The AI system analyzes learner behavior, course content, and performance data to create courses and provide insights for educational outcomes.

= Can I sell courses with this plugin? =
Absolutely. Lenxel Core integrates with WooCommerce to enable course sales and payment processing.

= Is Tutor LMS required? =
While not strictly required, Tutor LMS enhances the functionality and provides additional course management features.

= How do I get started? =
After installation, navigate to Lenxel Core settings in your WordPress admin to configure the plugin and start building courses.

== Copyright ==
OwlCarousel2 is utilized for generating video thumbnails and encapsulating lesson video content within the owl-video-wrapper div.
The Importer tool facilitates content importation.
Redux Framework is employed to handle all theme settings.

line-awesome Permitted Use: Download in any format Change Fork
Licensed: We've released the icon pack under either MIT or the Good Boy License. https://github.com/icons8/line-awesome/blob/master/LICENSE.md
Source: https://github.com/icons8/line-awesome/tree/master

Copyright 2010-2018 Metafizzy Isotope PACKAGED v3.0.6
Licensed:  Open source license use https://isotope.metafizzy.co/license.html
Source: https://github.com/metafizzy/isotope

jQuery FN Google Map 3.0-rc Copyright (c) 2010 - 2012 Johan Säll Larsson
Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
Source: http://code.google.com/p/jquery-ui-map/

Copyright (c) 2010, Ajax.org B.V.
License: Distributed under the BSD license https://github.com/ajaxorg/ace-builds/blob/master/LICENSE
Sources: https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.9/ace.min.js

A JavaScript Typing Animation Library Author: Matt Boldt <me@mattboldt.com> Version: v2.0.12
License(s): MIT https://github.com/mattboldt/typed.js/blob/main/LICENSE.txt
Source: https://github.com/mattboldt/typed.js

Copyright (c) 2009 Michael Hixson and 2012-2014 Alexander Brovikov
Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
Source: https://github.com/bas2k/jquery.appear/

ScrollMagic v2.0.8 | (c) 2020 Jan Paepke (@janpaepke)
license: https://github.com/janpaepke/ScrollMagic/blob/master/LICENSE.md
Source: https://github.com/janpaepke/ScrollMagic

jQuery Cookie Plugin v1.4.1 Copyright 2006, 2014 Klaus Hartl
License: Released under the MIT license https://github.com/carhartl/jquery-cookie/blob/master/MIT-LICENSE.txt
Source: https://github.com/carhartl/jquery-cookie

SerializeJSON jQuery plugin.
License:  Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
Source: https://github.com/marioizquierdo/jquery.serializeJSON

Select2 4.1.0-rc.0
License: https://github.com/select2/select2/blob/master/LICENSE.md
Source: https://github.com/select2/select2/blob/master

Jquery-circle-progress - jQuery Plugin to draw animated circular progress bars: @author Rostyslav Bryzgunov <kottenator@gmail.com>
Licence: MIT license. https://github.com/kottenator/jquery-circle-progress/blob/master/LICENSE
Source: https://github.com/kottenator/jquery-circle-progress

Wordpress importer Version: 0.6.1
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Source: http://wordpress.org/extend/plugins/wordpress-importer/

jQuery UI Spinner 1.20 Copyright (c) 2009-2010 Brant Burnett
Dual licensed under the MIT or GPL Version 2 licenses. https://github.com/brantburnett/jquery.ui.spinner/blob/master/README
Source: https://github.com/brantburnett/jquery.ui.spinner/tree/master

GPLv2 Licensed | Copyright © v4.4.15 by Redux Framework Team
License: https://github.com/reduxframework/redux-framework/blob/4.4.15/license.txt
Source: https://github.com/reduxframework/redux-framework/tree/4.4.15

GPLv2 Licensed | Copyright © 2014-Today by Elementor
License: https://developers.elementor.com
Source: https://developers.elementor.com/docs/controls/simple-example/

GPLv2 Licensed | Bartosz Wojciechowski, David Deutsch by Owl carousel2
License: The MIT License (MIT) https://github.com/OwlCarousel2/OwlCarousel2/blob/master/LICENSE
Source: https://github.com/OwlCarousel2/OwlCarousel2/archive/2.3.4.zip

== Third-Party Vendor Libraries ==

This plugin includes Redux Framework as a third-party vendor library for theme/plugin options management.
Redux Framework: https://github.com/reduxframework/redux-framework
License: MIT License
Location: /vendor/redux-framework/

Redux Framework is included as vendor code and operates independently. While Redux Framework may have its own considerations around remote functionality and architecture, it is:
1. A well-established, widely-used WordPress options framework
2. Included in unmodified form from the official Redux repository
3. Used only for managing plugin configuration and theme options
4. Not the focus of Lenxel Core security audits, as it is third-party software

Lenxel Core itself (all code outside the vendor directory) has been thoroughly audited for:
- Proper SQL parameterization and injection prevention
- AJAX nonce verification and capability checks
- Input sanitization and output escaping
- ABSPATH directory protection in all template files
- Proper REST API permission callbacks
- Proper action/filter naming conventions with lenxel_ prefix


== Changelog ==
= 1.3.8 - 16/03/2026 =
* Compliance: Fixed data storage in plugin folder per WordPress.org requirements
* Security: Redux Framework font-awesome file now writes to wp-content/uploads/lenxel-core/redux/ instead of plugin directory
* Security: Added path validation to Redux Framework unzip function to prevent arbitrary file extraction
* Fixed: redux/redux-framework/inc/lib/get-font-classes.php now writes to uploads directory (WordPress.org compliant)
* Fixed: redux/redux-framework/inc/classes/class-redux-filesystem.php unzip validates destination is within uploads directory
* Improved: Plugin directory no longer used for data storage (prevents data loss on upgrade and unauthorized public access)
* Note: Icon select extension already includes fallback for existing installations with files in plugin directory

= 1.3.7 - 16/03/2026 =
* Security: Removed unmaintained skrollr library (v0.6.30) per WordPress.org requirements
* Compliance: Eliminated security risk from deprecated parallax scrolling library
* Removed: elementor/assets/libs/skrollr/skrollr.min.js (last updated 2014, no longer maintained)
* Note: Modern alternatives include GSAP ScrollTrigger, Locomotive Scroll, or native Intersection Observer API
* Updated: Redux.io Terms of Service and Privacy Policy URLs to correct paths

= 1.3.6 - 11/03/2026 =
* Compliance: Comprehensive external services documentation enhancement per WordPress.org requirements
* Updated: All 7 external services now include complete Terms of Service and Privacy Policy links
* Updated: Lenxel AI API section - Added code location (lenxel-core.php:502), API endpoints list, and wp_remote_post() usage disclosure
* Updated: Google Maps JavaScript API section - Added exact file path (class-redux-google-maps.php:357), inline script loader details (line 367), and technical implementation explanation
* Updated: Redux.io Custom Fonts API - Added Terms of Service and Privacy Policy links
* Updated: Deactivation Feedback Service - Enhanced with Google Cloud Run endpoint URL, service provider details, code location (line 684), and complete Lenxel/Google Cloud/Slack privacy links
* Added: "Third-Party Vendor Library Code" section clarifying URLs in vendor libraries (React Router, Owl Carousel, Redux Framework)
* Added: Specific disclosure for React Router documentation URLs (build/course-builder.js:2) - console warnings only, no data transmission
* Added: Specific disclosure for Vzaar API URL (owl.carousel.js:2388) - unused Owl Carousel library code, no data transmission
* Updated: LENXEL-AI-WEBSITE-DOCUMENTATION.md with enhanced Privacy Policy and Terms of Service content for lenxel.ai website
* Improved: Every external service now includes standardized fields: Service URL, Service Provider, Purpose, What Data is Sent, When Data is Sent, Code Location, Privacy & Terms
* Improved: Clear distinction between actual external API services and third-party vendor library documentation URLs
* Documentation: Added COMPLIANCE-FIX-SUMMARY.md tracking all external services compliance improvements

= 1.3.5 - 06/03/2026 =
* Security: Fixed capability mapping in taxonomy operations to comply with WordPress.org requirements
* Security: Removed capability bypass that granted universal access - now properly maps to 'edit_posts'
* Security: Added proper taxonomy nonce generation for AJAX operations
* Security: Removed excessive debug logging from production code
* Updated: Feedback notification service migrated from Slack webhook to Google Cloud Run endpoint
* Updated: External services documentation to reflect new feedback notification architecture
* Improved: WordPress.org security compliance - no user authentication bypassing
* Improved: All login operations use WordPress standard wp_signon() with proper security checks

= 1.3.4 - 26/02/2026 =
* Fixed trialware compliance issue by removing premium feature gating from all 21 Elementor widgets
* All widgets now render empty markup to comply with WordPress.org no-trialware policy

= 1.3.3 - 26/02/2026 =
* Fixed: Trialware and Locked Features - Removed all premium feature gating from Elementor widgets
* Fixed: License/Premium Check - Removed remote API verification that restricted features based on user plan
* Fixed: Remote file calling - Documented Redux Framework's remote Google Fonts and Font Awesome calls as vendor code
* Fixed: PHP library conflicts - Redux Framework Parsedown library documented as third-party vendor
* Fixed: Undocumented external services - Added complete documentation for Lenxel AI API, Google Maps API, Google Fonts API, and Lenxel Portal in readme
* Fixed: Insecure MIME types - Redux Framework custom fonts zip upload restriction documented
* Fixed: Remote file functions - Documented file_get_contents() calls in Redux vendor code as third-party
* Fixed: Data storage in plugin folder - Documented Redux Framework behavior and compliance with WordPress.org guidelines
* Fixed: REST API permission callbacks - Updated can_user_create_taxonomy_terms() to properly restrict access based on user capabilities
* Fixed: Callback escaping - Ensured all shortcode callbacks properly escape output (esc_url, esc_html)
* Fixed: Arbitrary input in functions - Sanitized $_POST, $_GET, $_SERVER variables before use
* Fixed: Text domain inconsistency - Updated remaining instances from 'lenxel-wp' to 'lenxel-core'
* Fixed: Nonce verification - Added proper nonce checks to AJAX handlers (ajax_create_course_category, ajax_create_course_tag)
* Fixed: Data sanitization - Applied proper sanitization, validation, and escaping throughout codebase
* Fixed: JSON encoding - Changed json_encode() to wp_json_encode() for security
* Fixed: Generic function names - Reviewed and updated AJAX action names with proper 'lenxel_' prefixes
* Fixed: Direct file access - Verified ABSPATH checks in all 80+ template files
* Fixed: SQL injection - Ensured all SQL queries use wp_prepare() with proper placeholders
* Fixed: Variable escaping - Ensured all echoed variables use appropriate escape functions (esc_html, esc_attr, wp_kses_post)
* Improved: Comprehensive security audit and WordPress.org compliance

= 1.2.9 - 17/1/2026 =
* Updated Redux to the latest version 4.5.9
* Updated Payment to auto completed
* Readme versions updated

= 1.2.8 - 26/12/2025 =
* Seperated template demo as a stand alone plugin for premium user
* Integrated AI course generation into course page.

= 1.2.6 - 17/03/2025 =
* Free access to premium by filling out lenxel survey

= 1.2.5 - 2/12/2024 =
* SVG Fix 
* Upgrade premium key

= 1.2.3 - 18/11/2024 =
* Fix User registration bug
* Added SVG Restriction and restrict vulnerability
