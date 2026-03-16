# Lenxel.ai Website Documentation Updates
## Required Updates for Terms of Service and Privacy Policy

This document contains content that should be added to the Lenxel.ai website's Terms of Service and Privacy Policy pages to ensure full compliance with WordPress.org plugin directory requirements and user privacy standards.

---

## 1. Privacy Policy Updates

### Section: Data Collection from WordPress Plugin

Add the following section to your Privacy Policy at https://lenxel.ai/privacy-and-policy:

#### **Lenxel Core WordPress Plugin - Data Collection**

Our Lenxel Core WordPress plugin may collect data when you use certain features. All data collection is either:
1. Required for essential functionality you explicitly request, or
2. Completely optional and requires your explicit opt-in consent

Below is a complete list of data we collect and when:

##### **AI Course Generation (User-Initiated)**

**When:** Only when you explicitly click "Generate with AI" to create course content  
**Data Collected:**
- Course title and description you provide
- Supporting files and course prompts you submit
- Your API key (obtained from portal.lenxel.ai)
- WordPress site URL (for API key validation)

**Purpose:** To generate AI-powered course content as you requested  
**How It Works:** The plugin uses `wp_remote_post()` API calls (located in `lenxel-core.php` line 502 and related functions) to send your course data to our Lenxel AI API endpoints (https://api.lenxel.ai)  
**API Endpoints:**
- https://api.lenxel.ai/wp/sites/status/verify (API key validation - optional, informational only)
- https://api.lenxel.ai/ (course generation endpoints)

**Storage:** Temporarily processed on our servers; AI-generated content is returned to your WordPress site  
**Opt-Out:** Don't use the AI generation feature; all plugin functionality remains available

##### **API Key Validation (Optional)**

**When:** Only when you enter an API key in plugin settings  
**Data Collected:**
- Your API key
- Your WordPress site URL

**Purpose:** To verify the API key is valid (informational only; does not restrict features)  
**Storage:** Site URL associated with API key for usage tracking  
**Opt-Out:** Don't enter an API key; all plugin features remain fully functional

##### **Deactivation Feedback (Opt-In Required - DISABLED BY DEFAULT)**

**When:** Only if you:
1. First opt-in via WordPress Admin → LenxelWP → Privacy & Feedback, AND
2. Then choose to submit feedback when deactivating the plugin

**Data Collected (only if you submit feedback):**
- Deactivation reason (from predefined choices)
- Optional comment or alternative plugin name (if provided)
- Your email address (only if you check "Include my email")
- Your WordPress site URL
- Date and time of deactivation

**Purpose:** To improve the plugin based on user feedback  
**How It Works:** Feedback is sent to our Google Cloud Run endpoint (https://form-submission-to-slack-notify-495600076509.us-central1.run.app) which forwards it to our internal Slack workspace for review by the development team  
**Storage:** Feedback is forwarded to our internal team via Slack; email addresses are stored securely for follow-up communication  
**Service Provider:** Lenxel (Ogun Labs) operates this service via Google Cloud Run infrastructure  
**Third-Party Services Used:**
- Google Cloud Run (infrastructure and endpoint hosting)
- Slack (feedback notification and storage)

**Opt-Out:** This feature is disabled by default. To enable: LenxelWP → Privacy & Feedback → Check "Help improve Lenxel Core..." To disable: Uncheck the same option

**Important:** Even after opting in, you can still skip providing feedback by clicking "Skip & Deactivate" or closing the feedback modal. No data is transmitted unless you explicitly complete and submit the feedback form.

**Code Location:** The deactivation handler is located in `lenxel-core.php` (lines 640-690), with the Google Cloud Run endpoint defined at line 684.

##### **AI Credits and Account Management**

**When:** When you access portal.lenxel.ai to manage your account or purchase AI credits  
**Data Collected:**
- Email address and password (for account authentication)
- Payment information (processed securely by our payment provider)
- AI credit balance and usage history

**Purpose:** To manage your account, track AI credit usage, and process purchases  
**Storage:** Securely stored on our servers; payment information is handled by PCI-compliant payment processors  
**Third-Party Services:** Payment processors (Stripe, PayPal, or similar)

##### **Google Maps (If Used)**

**When:** When you use map widgets in course locations  
**Data Collected:**
- Your IP address (automatically sent by Google Maps)
- Location coordinates you configure

**Purpose:** To display interactive maps  
**Third-Party Service:** Google Maps Platform  
**Opt-Out:** Don't use map widgets in your courses

##### **Google Fonts (If Used)**

**When:** When you load pages using custom Google Fonts  
**Data Collected:**
- Font names
- Your IP address and browser information (automatically sent by browser)

**Purpose:** To load custom typography  
**Third-Party Service:** Google Fonts API  
**Opt-Out:** Select system fonts instead of Google Fonts in theme settings

##### **Vimeo (If Used)**

**When:** When you add Vimeo videos to course lessons  
**Data Collected:**
- Vimeo video ID
- Your IP address (automatically sent by browser)

**Purpose:** To fetch video thumbnails for preview  
**Third-Party Service:** Vimeo API  
**Opt-Out:** Don't use Vimeo videos; use other video hosting services

#### **Your Rights**

You have the right to:
- **Access** your data: Contact us at privacy@lenxel.ai
- **Delete** your data: Request deletion via privacy@lenxel.ai
- **Opt-out** of optional data collection: Disable features in plugin settings
- **Export** your data: Request a copy via privacy@lenxel.ai

#### **Data Retention**

- **AI Generation Requests:** Temporarily processed; not stored long-term
- **API Keys & Site URLs:** Stored while you have an active account
- **Deactivation Feedback:** Stored indefinitely for product improvement; email addresses retained for follow-up communication
- **Account Data:** Stored while your account is active; deleted upon account closure request

#### **Data Security**

All data transmissions use secure HTTPS connections. We implement industry-standard security measures to protect your data.

#### **Third-Party Services**

We use the following third-party services, each with their own privacy policies:
- **Lenxel Services:** https://lenxel.ai/terms-of-service (Terms) | https://lenxel.ai/privacy-and-policy (Privacy)
- **Google Cloud Platform:** https://cloud.google.com/terms/cloud-privacy-notice (Privacy)
- **Slack:** https://slack.com/trust/privacy/privacy-policy (Privacy) | https://slack.com/terms-of-service/api (API Terms)
- **Google Maps:** https://cloud.google.com/maps-platform/terms (Terms) | https://policies.google.com/privacy (Privacy)
- **Google Fonts:** https://developers.google.com/fonts/faq/privacy (Privacy FAQ)
- **Vimeo:** https://vimeo.com/terms (Terms) | https://vimeo.com/privacy (Privacy)
- **Redux Framework (Custom Fonts API):** https://redux.io/terms (Terms) | https://redux.io/privacy (Privacy)

**Note:** Your use of these third-party services through our plugin is subject to their respective terms and policies. We recommend reviewing these policies to understand how each service handles your data.

**Deactivation Feedback Service Details:**
The deactivation feedback feature (when enabled) sends data through our Google Cloud Run endpoint to our Slack workspace. This service is operated by Lenxel (Ogun Labs) and uses:
- Google Cloud Run for serverless endpoint hosting
- Slack API for team notifications and feedback storage
See our Privacy Policy above for complete details on what data is collected and when.

---

## 2. Terms of Service Updates

### Section: WordPress Plugin Terms

Add the following section to your Terms of Service at https://lenxel.ai/terms-of-service:

#### **Lenxel Core WordPress Plugin Terms**

By using the Lenxel Core WordPress plugin, you agree to the following terms:

##### **Optional Features and Data Collection**

1. **Deactivation Feedback:** The deactivation feedback feature is disabled by default. By enabling this feature in LenxelWP → Privacy & Feedback, you consent to:
   - The collection and processing of deactivation feedback data as described in our Privacy Policy
   - Transmission of feedback through our Google Cloud Run endpoint (operated by Lenxel/Ogun Labs)
   - Forwarding of feedback to our internal Slack workspace for review by the development team
   - Storage of feedback data and optional email address for follow-up communication
   
   You may disable this feature at any time, and even when enabled, you can skip providing feedback on each deactivation.

2. **AI Course Generation:** By using the AI course generation feature, you acknowledge that:
   - Course content you submit is temporarily processed by our AI API
   - Generated content is subject to AI limitations and should be reviewed before publication
   - AI credit usage is tracked and may incur charges once free credits are exhausted

3. **API Key Usage:** API keys provided via portal.lenxel.ai are:
   - Personal to your account and should not be shared
   - Used to authenticate and track AI credit usage
   - Can be revoked at any time from your portal account

##### **External Services**

The plugin integrates with the following external services as described in the plugin's readme.txt and External Services Documentation page:

1. **Lenxel AI API** - AI-powered course content generation
2. **Google Maps JavaScript API** - Interactive maps for course locations and Redux Framework admin fields
3. **Google Fonts API** - Custom typography loading
4. **Vimeo API** - Video thumbnail retrieval
5. **Lenxel User Portal** (portal.lenxel.ai) - API key management and AI credit purchases
6. **Redux.io Custom Fonts API** - Custom font file conversion (optional Redux Framework feature)
7. **Deactivation Feedback Service** - Google Cloud Run endpoint forwarding to Slack (opt-in only, disabled by default)

Your use of these services is subject to their respective Terms of Service and Privacy Policies as documented in the plugin's readme.txt file. Complete documentation of each service, including what data is sent, when, and links to privacy policies, is available in the plugin's External Services section.

**Third-Party Vendor Libraries (No Data Transmission):**

The plugin includes third-party vendor libraries that may contain hardcoded URLs or documentation links in their source code. **IMPORTANT:** These URLs are NOT contacted during plugin operation and do NOT transmit any user data. They include:

1. **React Router** (MIT Licensed) - Build files may contain documentation URLs (e.g., reactrouter.com upgrade guides) used in console warnings. These are reference links only.
2. **Owl Carousel** (MIT Licensed) - Contains video platform URLs (including Vzaar API) as part of the library's video support features. The Vzaar feature is not used by this plugin.
3. **Redux Framework** (MIT Licensed) - Comprehensive theme framework. External services used by Redux (Google Maps, Custom Fonts) are documented separately in sections 2 and 6 above.

These vendor library URLs appear in code comments, console warnings, or unused features but are never invoked during normal plugin operation.

##### **Free and Commercial Use**

The Lenxel Core plugin is licensed under GPL v3 and is free to use. However:
- AI credits may require purchase after exhausting free credits
- Commercial use of AI-generated content is permitted
- Plugin upgrades and premium features may be offered separately

##### **Deactivation and Data Deletion**

You may deactivate the plugin at any time. To request deletion of data collected through the plugin (such as feedback or API keys), contact us at privacy@lenxel.ai.

##### **Changes to Features**

We reserve the right to:
- Modify or discontinue features with reasonable notice
- Update AI credit pricing with notice to existing users
- Change API endpoints or authentication methods with migration support

---

## 3. Additional Website Pages

### Create: External Services Documentation Page

**URL:** https://lenxel.ai/wp/documentation/index.html  
**Purpose:** Comprehensive documentation of all external services used by Lenxel Core plugin

**Content:**
This page should contain the complete "External Services" section from the plugin's readme.txt file, formatted for web viewing with proper headings, links, and styling.

Include:
- All 7 external services detailed in readme.txt
- Copy the exact content from the readme.txt "External Services" section
- Add clear navigation and table of contents
- Make it easily linkable from plugin settings and documentation

---

## 4. Recommended Privacy Policy Enhancements

### Add These Standard Sections (if not present):

#### **Contact Information**
```
For privacy-related questions or data requests:
Email: privacy@lenxel.ai
Response time: Within 7 business days
```

#### **GDPR Compliance**
```
For users in the European Union:
- Right to access your data
- Right to rectification
- Right to erasure ("right to be forgotten")
- Right to restrict processing
- Right to data portability
- Right to object to processing

To exercise these rights, contact: privacy@lenxel.ai
```

#### **CCPA Compliance**
```
For California residents:
- Right to know what personal information is collected
- Right to know whether personal information is sold or disclosed
- Right to say no to the sale of personal information
- Right to delete personal information
- Right to equal service and price

Note: Lenxel does not sell personal information.
To exercise these rights, contact: privacy@lenxel.ai
```

#### **Children's Privacy**
```
Our services are not directed to individuals under 16 years of age.
We do not knowingly collect personal information from children under 16.
If you believe we have collected such information, contact: privacy@lenxel.ai
```

---

## 5. Implementation Checklist

### Immediate Actions Required:

- [ ] Update Privacy Policy at https://lenxel.ai/privacy-and-policy with "Data Collection from WordPress Plugin" section
  - [ ] Include detailed deactivation feedback section with Google Cloud Run and Slack details
  - [ ] Add all third-party service privacy policy links
  - [ ] Add GDPR, CCPA, and Children's Privacy sections
- [ ] Update Terms of Service at https://lenxel.ai/terms-of-service with "WordPress Plugin Terms" section
  - [ ] Include deactivation feedback consent terms with Google Cloud Run/Slack disclosure
  - [ ] List all 7 external services used by the plugin
  - [ ] Add third-party vendor library clarification
- [ ] Create External Services Documentation page at https://lenxel.ai/wp/documentation/index.html
  - [ ] Copy complete "External Services" section from readme.txt
  - [ ] Include all 7 services with full Terms/Privacy links
  - [ ] Add "Third-Party Vendor Library Code" section
- [ ] Add privacy contact email: privacy@lenxel.ai (or update with actual privacy email)
- [ ] Verify all links in readme.txt point to correct pages:
  - https://lenxel.ai/terms-of-service
  - https://lenxel.ai/privacy-and-policy
  - https://lenxel.ai/wp/documentation/index.html (if created)

### Testing:

- [ ] Verify all documentation is accessible to users
- [ ] Test plugin settings page links to Terms/Privacy
- [ ] Confirm deactivation feedback opt-in works correctly
- [ ] Test "Skip & Deactivate" functionality
- [ ] Verify no data is sent when feedback is disabled

### WordPress.org Submission:

- [ ] Include link to these policies in plugin submission notes
- [ ] Reference the opt-in deactivation feedback in plugin description
- [ ] Ensure readme.txt accurately reflects all changes

---

## 6. Sample Privacy Email Response Template

When users contact privacy@lenxel.ai for data requests:

```
Subject: Re: Data Request - Lenxel Core Plugin

Dear [User Name],

Thank you for your data request regarding the Lenxel Core WordPress plugin.

[For Access Requests:]
Based on your email address, we have the following data associated with your account:
- API Key: [masked]
- Associated Site URL: [site_url]
- Deactivation Feedback: [yes/no, if yes, include summary]
- AI Credit Balance: [number]

[For Deletion Requests:]
We have processed your deletion request and removed the following data:
- API key associations
- Deactivation feedback submissions
- Site URL records

Please note: Your portal.lenxel.ai account (if any) must be deleted separately through the portal or by contacting support@lenxel.ai.

[For Opt-Out Requests:]
To opt out of deactivation feedback:
1. Go to WordPress Admin → LenxelWP → Privacy & Feedback
2. Uncheck "Help improve Lenxel Core..."
3. Click "Save Settings"

If you have any questions, please let us know.

Best regards,
Lenxel Privacy Team
privacy@lenxel.ai
```

---

## 7. Legal Disclaimer

**Note:** This documentation provides recommended content for privacy and terms updates. It should be reviewed by your legal team before publication to ensure compliance with:
- GDPR (EU General Data Protection Regulation)
- CCPA (California Consumer Privacy Act)
- Other applicable privacy laws in your jurisdiction

Consult with legal counsel to ensure all content meets your specific legal requirements.

---

## 8. Document Change Log

### Version 1.1 - March 11, 2026
**External Services Documentation Enhancement**

Updated to address WordPress.org requirements for comprehensive external service disclosure:

**Privacy Policy Updates:**
- Enhanced deactivation feedback section with specific Google Cloud Run endpoint URL
- Clarified service provider: Lenxel (Ogun Labs) via Google Cloud Run
- Added code location references (lenxel-core.php lines 640-690, line 684)
- Expanded third-party services list to include Redux.io Custom Fonts
- Added comprehensive Terms of Service and Privacy Policy links for all services
- Added clarification note about how deactivation feedback flows: Google Cloud Run → Slack

**Terms of Service Updates:**
- Enhanced deactivation feedback consent section with Google Cloud Run/Slack disclosure
- Expanded external services list from general description to complete enumeration of all 7 services
- Added third-party vendor library clarification (Redux Framework, Owl Carousel, React Router)
- Clarified that vendor library URLs do not transmit data

**Implementation Checklist Updates:**
- Added sub-tasks for Privacy Policy updates (Google Cloud Run/Slack details, third-party links)
- Added sub-tasks for Terms of Service updates (service enumeration, vendor libraries)
- Added requirement to include "Third-Party Vendor Library Code" section on external services page

**Key Compliance Improvements:**
- All 7 external services now have complete Terms of Service and Privacy Policy links
- Google Cloud Run endpoint fully documented with purpose and data flow explanation
- Slack API usage fully disclosed as final destination for deactivation feedback
- Third-party vendor libraries clarified to distinguish from actual external API services
- Code locations provided for all services where applicable

---

**Last Updated:** March 11, 2026  
**Plugin Version:** 1.3.6  
