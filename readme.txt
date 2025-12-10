=== EpassCard ===
**Contributors:** wooxperto,xihad1 
**Tags:** wallet, passbook, gift card, Apple Wallet, Google Wallet  
**Requires at least:** 5.0  
**Tested up to:** 6.9
**Requires PHP:** 7.2
**Stable tag:** 1.0.0  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

**Create digital wallet passes for Apple Wallet, Google Wallet, and EpassCard.**

== Description ==
**Smartest Card Solution For Google Wallet and Apple Wallet and EpassCard**
Epasscard is a powerful WordPress plugin that brings digital wallet functionality to your Website. It allows users to generate and manage electronic passes and cards, such as loyalty cards, coupons, and gift cards, directly from your website.

With the **Epasscard to Gift Card Extension**, you can automatically generate a digital pass whenever a gift card is created using the popular **Gift Card for WooCommerce** plugin. This pass can be stored and accessed via Epasscard, providing a seamless experience for your customers.

**Key Features:**

- Create and manage electronic wallet passes
- Compatible with Apple Wallet and Google Wallet formats
- Integrates with Gift Card for WooCommerce via extension plugin
- Automatically generates Epasscard pass when gift card is issued
- Customizable pass design and metadata
- Easy to use and developer-friendly


== External Services ==

This plugin relies on external services provided by **EpassCard** to generate, validate, and manage digital wallet passes.

The following APIs are used:

- **Pass Creation and Management**  
  Service: https://api.epasscard.com/api/public/v1/  
  Purpose: To create, update, and retrieve digital pass templates and details.  
  Data Sent: Pass metadata (design, user-provided information such as gift card details) when a pass is created or updated.  

- **Certificates**  
  Service: https://api.epasscard.com/api/certificate/all-certificates/  
  Purpose: To fetch available certificates required for pass generation.  
  Data Sent: None from the user; only plugin requests certificate information.  

- **API Key Validation and Refresh**  
  Services:  
  - https://api.epasscard.com/api/public/v1/validate-api-key  
  - https://api.epasscard.com/api/public/v1/refresh-api-key  
  Purpose: To validate and refresh your EpassCard API key.  
  Data Sent: API key provided by the site administrator.  

- **Location and Place Services**  
  Services:  
  - https://api.epasscard.com/api/google/geocode/  
  - https://api.epasscard.com/api/google/places/  
  Purpose: To resolve geolocation and place information for passes.  
  Data Sent: Location or place identifiers when configured in pass metadata.  

- **API Key Management Portal**  
  Service: https://app.epasscard.com/api-keys  
  Purpose: To allow administrators to generate and manage API keys.  
  Data Sent: Administrator account information when accessing the portal (handled directly by EpassCard).

**Important:**  
- Data is only transmitted when you configure or generate passes.  
- No personal data is sent unless you explicitly include it in the pass metadata.  
- All services are provided by **EpassCard**.  


Epasscard api documentation: https://documenter.getpostman.com/view/47181495/2sB3QQHSXL
Terms of Service: https://epasscard.com/terms  
Privacy Policy: https://epasscard.com/privacy


== Installation ==

1. Upload the `epasscard` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Install and activate the **Gift Card for WooCommerce** plugin if you want to use gift card integration.
4. Install the **Epasscard to Gift Card Extension** plugin to enable automatic pass generation.
5. Configure your pass settings under **Epasscard > Settings**.

== Frequently Asked Questions ==

= Does Epasscard work with Apple Wallet and Google Wallet? =  
Yes, Epasscard generates passes compatible with both Apple Wallet and Google Wallet.

= Do I need the Gift Card for WooCommerce plugin? =  
Only if you want to generate Epasscard passes from WooCommerce gift cards. Otherwise, Epasscard can be used independently.

= Can I customize the pass design? =  
Yes, you can customize the appearance and metadata of your passes via the plugin settings.

== Screenshots ==

1. Epasscard settings panel  
2. Generate pass template  
3. WooCommerce gift card integration
4. Template list

== Changelog ==

= 1.0.0 =
* Initial release  
* Digital pass generation  
* Gift Card for WooCommerce integration via extension plugin

== Upgrade Notice ==

= 1.0.0 =
First stable release of Epasscard. Includes core wallet functionality and WooCommerce gift card integration.

== Credits ==

Developed by [WebCartisan]  
For support, contact [hello@webcartisan.com]
