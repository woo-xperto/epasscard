=== EpassCard (Google Wallet, Apple Wallet, and more) ===
Contributors: wooxperto
Tags: apple-wallet, google-wallet, wallet-pass, membership, woocommerce
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Issue Apple Wallet and Google Wallet passes for MemberPress, WooCommerce Subscriptions, and membership sites — automatically on signup and renewal.

== Description ==

**EpassCard** is a WordPress plugin for **digital wallet passes**. Connect your site to [EpassCard](https://epasscard.com/) and automatically issue **Apple Wallet** and **Google Wallet** passes when members subscribe, renew, or when their membership data changes.

No Zapier required. No custom code. Map your membership fields to a pass template and let EpassCard handle pass creation, updates, and delivery links.

= MemberPress Apple Wallet and Google Wallet =

Turn MemberPress memberships into **digital membership cards** members can save to their phone. When someone completes signup or renews a plan, EpassCard creates or updates their wallet pass with the fields you map — name, membership level, expiration date, and more.

= WooCommerce Subscriptions wallet pass =

Issue a **subscription wallet pass** for WooCommerce Subscriptions customers. Passes are created on subscription activation and updated when subscription or customer data changes.

= Ultimate Membership Pro =

Enable the Ultimate Membership Pro module to issue wallet passes for UMP members using the same template-mapping workflow.

= Features =

* **Apple Wallet and Google Wallet** — one integration, both platforms
* **Automatic issuance** — passes on signup, renewal, and data changes
* **Template mapping** — map membership fields to pass template fields
* **Modular integrations** — MemberPress, Ultimate Membership Pro, WooCommerce Subscriptions
* **Connection** — API key or email/password sign-in
* **Admin pass list** — searchable table of members and pass links

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

= How do I add a membership card to Apple Wallet on WordPress? =

1. Create a pass template in your EpassCard account.
2. Install and activate EpassCard, then connect your API key.
3. Enable the MemberPress (or other) module and map membership fields to pass fields.
4. When a member subscribes, they receive a link to add the pass to Apple Wallet or Google Wallet.

= Is EpassCard a PassKit alternative for WordPress? =

Yes. EpassCard is a native WordPress plugin for issuing Apple Wallet and Google Wallet passes — without Zapier, Make, or custom API code. It is built for membership and subscription sites using MemberPress, Ultimate Membership Pro, or WooCommerce Subscriptions.

= Does EpassCard work with Apple Wallet and Google Wallet? =

Yes. EpassCard creates passes compatible with both Apple Wallet (iPhone, iPad, Apple Watch) and Google Wallet (Android).

= What is the difference between Passbook and Apple Wallet? =

Passbook was renamed to Apple Wallet in 2015. The underlying `.pkpass` format is the same. EpassCard issues passes for modern Apple Wallet and Google Wallet.

= Can I issue passes automatically when a member signs up? =

Yes. Passes are created on signup and renewal, and updated when mapped membership data changes.

== Changelog ==

= 1.0.1 =
* Rebrand display name to EpassCard.
* Premium SaaS admin UI, AJAX settings saves, and related improvements.
* Readme SEO: Apple Wallet, Google Wallet, MemberPress, and WooCommerce Subscriptions keywords.

= 1.0.0 =
* Initial release: Connection, MemberPress module, WooCommerce Subscriptions module.
