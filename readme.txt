=== EpassCard (Google Wallet, Apple Wallet, and more) ===
Contributors: wooxperto
Tags: apple-wallet, google-wallet, wallet-pass, pkpass, woocommerce
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WordPress wallet pass plugin for Apple Wallet and Google Wallet. Auto-issue digital membership cards, pkpass passes, and subscription wallet passes.

== Description ==

**EpassCard** is a **WordPress wallet pass plugin** that connects your site to [EpassCard](https://epasscard.com/) and automatically issues **Apple Wallet** and **Google Wallet** passes when members subscribe, renew, or when their data changes.

Create **digital wallet passes** — membership cards, loyalty cards, event badges, employee IDs, coupons, gift cards, and more — using pass templates in your EpassCard account. Map WordPress membership fields to pass fields in the admin. No Zapier, no custom API code, no separate pass management dashboard outside WordPress.

= Wallet pass generator and management for WordPress =

EpassCard is a **wallet pass solution** built for WordPress membership and subscription sites. It handles **pass creation**, **pass updates**, **pass distribution** (email links and member account pages), and **wallet pass notifications** (push reminders) — so members always have an up-to-date pass on their phone.

Supported pass platforms:

* **Apple Wallet** — `.pkpass` files for iPhone, iPad, and Apple Watch
* **Google Wallet** — digital passes for Android devices

= Digital membership cards =

Turn memberships into **digital membership cards** members save to their phone wallet.

* **MemberPress Apple Wallet and Google Wallet** — issue a **membership wallet pass** on signup, renewal, and profile updates
* **WooCommerce Subscriptions wallet pass** — create a **subscription wallet pass** when a subscription activates or renews
* **Ultimate Membership Pro** — issue wallet passes for UMP members with the same template-mapping workflow

Map name, plan, expiration date, QR codes, barcodes, and custom fields from your membership plugin to your pass template.

= Loyalty cards, event tickets, and more =

Because passes are designed in EpassCard, you are not limited to membership cards. Use the same WordPress integration to distribute:

* **Digital loyalty cards** and rewards passes
* **Event tickets** and conference badges
* **Employee ID cards** and visitor passes
* **Student ID** and campus cards
* **Digital coupons**, gift cards, and vouchers
* **QR code wallet passes** and barcode passes

If your EpassCard template supports it, EpassCard for WordPress can issue and update it automatically.

= PassKit alternative for WordPress =

Looking for a **PassKit alternative**, **PassCreator alternative**, or **wallet pass software** that works natively inside WordPress?

EpassCard is a **wallet pass platform** connector — not a middleware automation tool. MemberPress, Ultimate Membership Pro, and WooCommerce Subscriptions hooks are built in. Connect your API key, map templates, and passes are created when membership events fire.

= Features =

* **Apple Wallet and Google Wallet** — one integration, both mobile wallet platforms
* **PKPass support** — standard Apple Wallet `.pkpass` format
* **Automatic wallet pass issuance** — on signup, renewal, and mapped data changes
* **Pass updates** — keep wallet passes in sync when membership data changes
* **Template mapping** — map membership fields to pass template fields (including QR and barcode fields)
* **Push notifications** — wallet pass reminders aligned with membership timing
* **Modular integrations** — MemberPress, Ultimate Membership Pro, WooCommerce Subscriptions
* **Admin pass list** — searchable table of issued passes and delivery links
* **Member account access** — members open passes from their account area
* **API connection** — API key or email/password sign-in to the EpassCard pass API

= Who is this for? =

* **Membership sites** — associations, clubs, gyms, and online courses using MemberPress or UMP
* **Subscription businesses** — WooCommerce Subscriptions stores that want a **digital card** on the customer's phone
* **Event and conference organizers** — attendee passes and badges linked to membership or registration data
* **Agencies** — WordPress developers who need a reliable **wallet pass integration** without building a custom pkpass server

= Requirements =

* An [EpassCard](https://epasscard.com/) account (sign up at [app.epasscard.com](https://app.epasscard.com))
* MemberPress, Ultimate Membership Pro, and/or WooCommerce Subscriptions (per enabled module)

= External services =

This plugin connects to the **EpassCard API** (`https://api.epasscard.com`) to validate credentials, fetch pass templates, and create/update wallet passes. Data sent includes mapped membership fields you configure. See [EpassCard Terms](https://epasscard.com/) and their privacy policy for how they handle data.

## Privacy Policy 
EpassCard uses [Appsero](https://appsero.com) SDK to collect some telemetry data upon user's confirmation. This helps us to troubleshoot problems faster & make product improvements.

Appsero SDK **does not gather any data by default.** The SDK only starts gathering basic telemetry data **when a user allows it via the admin notice**. We collect the data to ensure a great user experience for all our users. 

Integrating Appsero SDK **DOES NOT IMMEDIATELY** start gathering data, **without confirmation from users in any case.**

Learn more about how [Appsero collects and uses this data](https://appsero.com/privacy-policy/).


== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/epasscard/` or install from WordPress.org.
2. Activate through the **Plugins** screen.
3. Go to **EpassCard → Connection** and connect your account.
4. Open **Connection → Integrations** and enable the modules you need.
5. Map pass templates under each enabled integration.

== Frequently Asked Questions ==

= Do I need an EpassCard account? =

Yes. Create a free account at [app.epasscard.com](https://app.epasscard.com), then connect it in **EpassCard → Connection**.

= Which membership plugins are supported? =

MemberPress, Ultimate Membership Pro, and WooCommerce Subscriptions in v1.0.1. Enable only the modules you use.

= How do I create an Apple Wallet pass on WordPress? =

1. Create a pass template in your EpassCard account (membership card, loyalty card, event ticket, or other pass type).
2. Install and activate the EpassCard WordPress plugin, then connect your API key.
3. Enable the MemberPress, Ultimate Membership Pro, or WooCommerce Subscriptions module.
4. Map membership fields to pass template fields.
5. When a member subscribes or a subscription activates, they receive a link to add the pass to **Apple Wallet** or **Google Wallet**.

= How do I add a Google Wallet pass to my WordPress site? =

The workflow is the same as Apple Wallet. EpassCard creates passes for both platforms from one template mapping. Members choose **Add to Apple Wallet** or **Add to Google Wallet** from their pass link.

= Is there a WordPress plugin for Apple Wallet membership cards? =

Yes. EpassCard is a **WordPress wallet pass plugin** designed for membership and subscription sites. It issues **digital membership cards** to Apple Wallet and Google Wallet automatically when members sign up or renew.

= Does EpassCard work with WooCommerce Apple Wallet and Google Wallet? =

Yes. Enable the WooCommerce Subscriptions module to issue a **WooCommerce wallet pass** when subscriptions activate, renew, or update. This is ideal for subscription boxes, SaaS products, and recurring membership products sold through WooCommerce.

= What is PKPass and does EpassCard support it? =

PKPass (`.pkpass`) is the file format Apple Wallet uses for digital passes. EpassCard issues standard pkpass files compatible with iPhone, iPad, and Apple Watch. You design the pass in EpassCard; the WordPress plugin sends member data and triggers pass creation through the EpassCard API.

= Can I use QR codes and barcodes on wallet passes? =

Yes. If your EpassCard pass template includes QR code or barcode fields, map membership data (member ID, subscription ID, check-in code, etc.) to those fields. Members scan the code from their **mobile wallet pass**.

= Is EpassCard a PassKit alternative for WordPress? =

Yes. EpassCard is a native **WordPress wallet pass plugin** for issuing Apple Wallet and Google Wallet passes — without Zapier, Make, or custom API development. It is built for membership and subscription sites using MemberPress, Ultimate Membership Pro, or WooCommerce Subscriptions.

= How does wallet pass automation work? =

Passes are created automatically when configured membership events occur (signup, renewal, subscription activation). When mapped membership data changes, EpassCard updates the existing pass via the API. Push notification rules can remind members before expiry or renewal.

= Can I issue digital loyalty cards or event tickets? =

Yes. Create the pass type you need in EpassCard (loyalty card, event ticket, coupon, gift card, employee badge, etc.), then map your WordPress membership or subscription fields to the template. The plugin issues whatever pass type your template defines.

= Does EpassCard send wallet pass update notifications? =

Yes. Configure push reminder rules in the plugin admin for events like subscription expiry, renewal, trial ending, and card expiration. Reminders are delivered as **wallet pass notifications** on supported devices.

= What is the difference between Passbook and Apple Wallet? =

Passbook was renamed to Apple Wallet in 2015. The underlying `.pkpass` format is the same. EpassCard issues passes for modern Apple Wallet and Google Wallet.

= Can I manually create or resend a pass? =

Yes. From the admin **Issued passes** table or member screens, you can create a pass, update an existing pass, or email the pass link to a member.

== Screenshots ==

1. Connection settings — connect your EpassCard account and enable integrations.
2. Template mapping — map membership fields to Apple Wallet and Google Wallet pass fields.
3. Issued passes — searchable list of wallet passes and delivery links.

== Changelog ==

= 1.0.1 =
* Rebrand display name to EpassCard.
* Premium SaaS admin UI, AJAX settings saves, and related improvements.
* Readme SEO: expanded Apple Wallet, Google Wallet, pkpass, membership, WooCommerce, and wallet pass management keywords.

= 1.0.0 =
* Initial release: Connection, MemberPress module, WooCommerce Subscriptions module.

== Upgrade Notice ==

= 1.0.1 =
Rebrand and admin UI improvements. Safe update for existing connections.

== Privacy Policy ==

EpassCard uses [Appsero](https://appsero.com) SDK to collect some telemetry data upon user's confirmation. This helps us to troubleshoot problems faster & make product improvements.

Appsero SDK **does not gather any data by default.** The SDK only starts gathering basic telemetry data **when a user allows it via the admin notice**. We collect the data to ensure a great user experience for all our users.

Integrating Appsero SDK **DOES NOT IMMEDIATELY** start gathering data, **without confirmation from users in any case.**

Learn more about how [Appsero collects and uses this data](https://appsero.com/privacy-policy/).
