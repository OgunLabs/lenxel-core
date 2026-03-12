# Lenxel Core - WordPress LMS Plugin

This is the core WordPress plugin for the Lenxel Learning Management System.

## Overview

This plugin provides all the foundational LMS features, including course creation, lesson management, quizzes, and student tracking, all within the WordPress admin environment.

## Key Responsibilities

-   Provides the admin UI for all LMS functionalities.
-   Creates and manages custom post types for courses, lessons, quizzes, etc. (see `posttypes/`).
-   Integrates with the central Lenxel AI API (`packages/api`) to provide AI-powered content generation features.
-   Handles license key validation by communicating with the API.

## Technical Details

-   **Language**: PHP
-   **Entry Point**: `lenxel-core.php`
-   **Dependencies**: This plugin is designed to work with the `lenxel-wp` theme but is compatible with other standard WordPress themes.

## External Services

This plugin uses the following external services. For complete details on what data is sent, when, and privacy policies, see the **External services** section in `readme.txt`:

1. **Lenxel AI API** (api.lenxel.ai) - AI-powered course content generation
2. **Google Maps API** (maps.googleapis.com) - Interactive maps for course locations
3. **Google Fonts API** (fonts.googleapis.com) - Custom typography
4. **Vimeo API** (vimeo.com/api) - Video thumbnail retrieval for course lessons
5. **Lenxel User Portal** (portal.lenxel.ai) - User account and AI credit management
6. **Redux.io Custom Fonts API** (redux.io/fonts) - Font file conversion (Redux Framework vendor service)
7. **Feedback Notification Service** (Google Cloud Run) - Optional deactivation feedback collection for plugin improvement (**disabled by default**, opt-in via LenxelWP → Privacy & Feedback)

All external service usage is documented in accordance with WordPress.org requirements.

## Privacy Settings

Manage privacy and feedback preferences in **WordPress Admin → LenxelWP → Privacy & Feedback**. This includes:
- Deactivation feedback opt-in (disabled by default)
- Information about external services
- Links to Terms of Service and Privacy Policy
