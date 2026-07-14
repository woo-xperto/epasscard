# Google Wallet Integration for WordPress Membership Sites

**Suggested URL:** `https://epasscard.com/blog/google-wallet-integration-wordpress/`  
**Primary keywords:** google wallet integration, google wallet pass, create google wallet pass  
**Secondary:** google wallet wordpress, google wallet membership card, google wallet api  
**Meta title:** Google Wallet Integration for WordPress | EpassCard  
**Meta description:** How to issue Google Wallet passes from WordPress for MemberPress and WooCommerce Subscriptions. Same setup as Apple Wallet — one template, both platforms.

---

Apple Wallet gets most of the attention in membership site forums. But roughly half your members probably carry Android phones. If you only issue Apple passes, they get a worse experience — or no card at all.

**Google Wallet integration** on WordPress does not have to be a separate project. Done right, you set up once and members on both platforms get a pass from the same membership event.

---

## What Google Wallet passes look like for members

A member finishes signup on your MemberPress site. They receive an email or account page link with two buttons:

- **Add to Apple Wallet** (iPhone)
- **Add to Google Wallet** (Android)

They tap the one that matches their phone. The membership card appears in Google Wallet — name, plan, expiry date, QR code for check-in.

When their membership renews in WordPress, the pass updates. When expiry approaches, they can get a push notification on their Android lock screen.

Same experience as Apple. Different API under the hood.

---

## Google Wallet vs Apple Wallet for site owners

| | Apple Wallet | Google Wallet |
|---|--------------|---------------|
| **Backend format** | `.pkpass` (signed ZIP) | Google Wallet Objects API |
| **Developer setup** | Apple Pass Type ID certificate | Google Cloud issuer account |
| **Member action** | Tap "Add to Apple Wallet" | Tap "Add to Google Wallet" |
| **Updates** | APNS push to device | Google Wallet API |
| **QR / barcode** | Supported | Supported |
| **WordPress plugin support** | EpassCard ✅ | EpassCard ✅ |

You do not choose one. Issue both.

---

## How Google Wallet integration works with WordPress

EpassCard handles the Google Wallet API, certificate management, and pass hosting. The WordPress plugin handles membership events and field mapping.

**The flow:**

1. Member subscribes via MemberPress, WooCommerce Subscriptions, or Ultimate Membership Pro
2. WordPress plugin sends member data to EpassCard API
3. EpassCard creates passes for Apple Wallet and Google Wallet
4. Member gets links for both platforms
5. When membership data changes, plugin triggers a pass update

No Google Cloud project on your end. No JWT signing in your theme's functions.php.

---

## Setup steps (Google Wallet + Apple Wallet together)

### 1. Create your pass template

In [app.epasscard.com](https://app.epasscard.com), design a membership card template. Include fields for name, plan, expiry, and a QR code. EpassCard generates both Apple and Google versions from this design.

### 2. Install the WordPress plugin

Get [EpassCard from WordPress.org](https://wordpress.org/plugins/epasscard/). Connect your account under **EpassCard → Connection**.

### 3. Enable your membership module

Turn on MemberPress, WooCommerce Subscriptions, or Ultimate Membership Pro — whichever you use.

### 4. Map fields

Under the integration settings, map membership fields to pass template fields. Example for MemberPress:

- Full name → Member Name
- Membership title → Plan
- Expiration date → Expires
- User ID → QR code value

### 5. Test on a real Android device

Sign up a test member. Open the Google Wallet link on an Android phone. Confirm the card displays correctly and the QR code scans.

Also test on iPhone to confirm the Apple Wallet link works from the same mapping.

---

## Google Wallet pass types for membership sites

Google Wallet supports several pass categories. For WordPress membership sites, the most relevant:

| Pass type | Use case |
|-----------|----------|
| **Generic pass** | General membership cards, association IDs |
| **Loyalty card** | Points-based or tiered membership programs |
| **Offer** | Coupons and promotional passes |
| **Event ticket** | Conference badges, workshop admission |
| **Gift card** | Prepaid membership credits |

Your EpassCard template determines the pass type. The WordPress plugin supplies the member-specific data.

---

## Google Wallet QR codes and check-in

For gyms, associations, and events, the QR code on the pass is the main check-in mechanism.

Map a unique identifier — MemberPress user ID, subscription ID, or a custom field — to the barcode/QR field in your template. Train front desk staff to scan from Google Wallet the same way they would from Apple Wallet.

Test with your actual scanner. Some older barcode readers handle QR from phone screens poorly. Better to find out before launch day.

---

## Google Wallet push notifications

Google Wallet passes support notifications for updates and reminders. Configure reminder rules in the EpassCard WordPress plugin:

- Subscription expiring soon
- Renewal due
- Trial ending
- Card expiration

Members on Android see these on their lock screen — tied to the pass, not a generic app notification they might disable.

---

## Common Google Wallet integration mistakes

**Only testing on iPhone.** Always test the Google Wallet link on a physical Android device before launch.

**Assuming Google Pay = Google Wallet.** Google rebranded. Members may say "Google Pay" when they mean the wallet app. The pass goes in Google Wallet.

**Separate templates for each platform.** One EpassCard template covers both. Do not maintain two designs unless you want different branding per platform.

**Forgetting existing members.** Members who joined before you enabled passes need manual issuance or a bulk process. New signups are automatic; old members are not retroactive unless you create passes for them.

---

## Google Wallet API: do you need direct access?

Most WordPress site owners do not.

Direct **Google Wallet API** integration makes sense if you are building a custom app or a pass platform. For MemberPress and WooCommerce Subscriptions sites, the EpassCard API abstracts this — you map fields in WordPress, the platform talks to Google.

Developers who want programmatic access can use `epc_get_api_key()` and the EpassCard API docs for custom workflows outside the standard modules.

---

## Frequently asked questions

### Does EpassCard support Google Wallet and Apple Wallet from one setup?

Yes. One template, one field mapping, both platforms. Members choose the link for their device.

### Can WooCommerce Subscriptions customers get Google Wallet passes?

Yes. Enable the WooCommerce Subscriptions module and map subscription fields. Passes issue on activation and renewal.

### Is Google Wallet available in all countries?

Google Wallet pass support varies by region. Check Google's current country list. Apple Wallet has its own regional availability.

### How is this different from a PWA or mobile app?

Google Wallet passes live inside the wallet app already on the phone. No Play Store download, no separate login.

### What if a member switches from iPhone to Android?

They need to add the Google Wallet pass on their new device. The old Apple Wallet pass stays on the old phone but should be revoked if the membership is transferred or cancelled.

---

**Set up Google Wallet and Apple Wallet together:** [app.epasscard.com](https://app.epasscard.com) · [WordPress plugin](https://wordpress.org/plugins/epasscard/) · [Digital wallet passes guide](/blog/digital-wallet-passes-wordpress/)
