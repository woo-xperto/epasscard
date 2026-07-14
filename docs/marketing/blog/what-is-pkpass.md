# What Is a PKPass File? (And Why WordPress Site Owners Should Care)

**Suggested URL:** `https://epasscard.com/blog/what-is-pkpass/`  
**Primary keywords:** pkpass, what is pkpass, pkpass generator, create pkpass  
**Secondary:** apple wallet pass, pkpass wordpress, pkpass plugin, pkpass barcode  
**Meta title:** What Is PKPass? Apple Wallet Pass Files Explained | EpassCard  
**Meta description:** PKPass is the file format behind Apple Wallet passes. Learn what is inside a .pkpass file, how pkpass generation works, and how WordPress sites issue passes without building a pkpass server.

---

If you have ever added a boarding pass to your iPhone, you have used a pkpass file. You just did not know it.

**PKPass** (written `.pkpass`) is the file format Apple Wallet uses for digital passes. Event tickets, loyalty cards, coupons, membership cards — on iPhone, they are all pkpass files under the hood.

This post explains what that means for WordPress site owners who want to issue Apple Wallet passes without becoming Apple developers.

---

## PKPass in plain language

A `.pkpass` file is a signed ZIP archive. Inside it:

- **pass.json** — the pass data (name, dates, fields, colors)
- **Images** — logo, icon, strip image, background
- **manifest.json** — checksums of every file
- **signature** — cryptographic proof the pass came from a registered pass issuer

When someone taps "Add to Apple Wallet," their phone downloads this bundle, verifies the signature, and displays the card.

If the signature is invalid or expired, Apple Wallet refuses the pass. That is why you cannot just rename a PDF to `.pkpass` and expect it to work.

---

## PKPass vs Google Wallet passes

Apple and Google use different systems.

| | Apple Wallet (PKPass) | Google Wallet |
|---|----------------------|---------------|
| **Format** | `.pkpass` ZIP file | Google Wallet Objects API |
| **Issuer certificate** | Apple Pass Type ID certificate required | Google Cloud project + issuer account |
| **Distribution** | URL or email attachment | "Save to Google Wallet" JWT link |
| **Updates** | Push via APNS to registered devices | Google Wallet API update |

Most pass platforms — including EpassCard — handle both. You design one template; the platform outputs pkpass for Apple and the Google equivalent for Android.

---

## Do you need to build a pkpass generator?

For a WordPress membership site: almost certainly not.

A **pkpass generator** at scale requires:

1. Apple Developer account ($99/year)
2. Pass Type ID certificate
3. Signing server that bundles and signs each pass
4. Push notification certificate for pass updates
5. Hosting for pass URLs
6. Code to map your member data into pass.json

PassKit, EpassCard, and similar platforms already run this infrastructure. You provide the template and member data; they return a signed pkpass.

The WordPress plugin's job is simpler: when a member signs up, send the right fields to the API and give the member a link.

---

## PKPass and WordPress: how it connects

Search "pkpass wordpress" and you will find scattered forum threads from people trying to hand-roll signing in PHP. It is possible. It is also fragile — certificate expiry, Apple API changes, and push notification edge cases eat maintenance time.

The practical path for membership sites:

1. **EpassCard** runs the pkpass signing and hosting
2. **[EpassCard WordPress plugin](https://wordpress.org/plugins/epasscard/)** maps MemberPress, WooCommerce Subscriptions, or UMP fields
3. Member receives an Apple Wallet link; pkpass generation happens on EpassCard servers

You never touch the ZIP file. You do not manage certificates. You map fields and test the result.

---

## What goes inside a membership card pkpass?

A typical membership pass includes:

```
{
  "formatVersion": 1,
  "passTypeIdentifier": "pass.com.yourcompany.membership",
  "serialNumber": "member-12345",
  "teamIdentifier": "YOUR_TEAM_ID",
  "organizationName": "Your Association",
  "description": "Membership Card",
  "logoText": "Member Since 2024",
  "foregroundColor": "rgb(255, 255, 255)",
  "backgroundColor": "rgb(0, 80, 160)",
  "generic": {
    "primaryFields": [{ "key": "member", "label": "MEMBER", "value": "Jane Smith" }],
    "secondaryFields": [{ "key": "plan", "label": "PLAN", "value": "Gold" }],
    "auxiliaryFields": [{ "key": "expires", "label": "EXPIRES", "value": "Dec 31, 2026" }]
  },
  "barcode": {
    "format": "PKBarcodeFormatQR",
    "message": "MEMBER-12345",
    "messageEncoding": "iso-8859-1"
  }
}
```

EpassCard builds this from your template. The WordPress plugin supplies the values — member name, plan, expiry, QR data — from your membership plugin.

---

## PKPass QR codes and barcodes

Passes support QR codes, PDF417, Aztec, and Code 128 barcodes. For membership check-in, QR is the most common choice.

Map a unique member ID or subscription ID to the barcode field. Front desk scans it; your check-in system validates the ID against your member database.

If the barcode field is empty in your mapping, the pass generates without a scannable code. Always test with your actual scanner hardware.

---

## PKPass updates and push notifications

Static passes are useless for memberships. When someone renews, the expiry date on their phone should change.

Apple Wallet supports pass updates through push notifications. When membership data changes in WordPress:

1. Plugin tells EpassCard API to update the pass
2. EpassCard signs a new pkpass version
3. Apple pushes a silent notification to the member's device
4. Wallet downloads the updated pass

The member does not re-tap a link. The card on their phone just updates.

---

## PKPass hosting and download links

Each pkpass needs a URL Apple Wallet can fetch. Hosting must:

- Serve files over HTTPS
- Return correct MIME type (`application/vnd.apple.pkpass`)
- Handle traffic spikes (imagine 500 members adding passes after a launch email)

Pass platforms handle hosting. Self-hosting pkpass files on shared WordPress hosting is technically possible but not something I recommend for production membership sites.

---

## When you might need custom pkpass development

Consider custom pkpass work if:

- You issue millions of passes with unique per-pass logic
- You need passes tied to hardware (NFC access control)
- You are building a pass platform, not using one

For a MemberPress site with a few hundred members: use a pass platform and a WordPress plugin. Your time is better spent on membership content than certificate rotation.

---

## Frequently asked questions

### Is pkpass only for Apple?

Yes. `.pkpass` is Apple's format. Google Wallet uses a different API. Issue both from EpassCard so you cover iPhone and Android.

### Can I edit a pkpass file manually?

You can unzip and edit pass.json for testing, but changing anything breaks the signature. You would need to re-sign with your certificate. Use the template editor instead.

### What is the difference between pkpass and Passbook?

Passbook was the old iOS app name. Apple renamed it to Wallet in 2015. People still search "passbook" — the file format never changed.

### Does WooCommerce need pkpass support?

Only if you want Apple Wallet passes for subscription or membership products. The [EpassCard WooCommerce Subscriptions module](/woocommerce-subscriptions-wallet-pass/) handles pkpass generation through the API.

### Is there a free pkpass generator?

EpassCard offers a free tier to get started. Apple Developer account fees ($99/year) apply to whoever holds the signing certificate — with EpassCard, that is included in the platform.

---

**Issue pkpass passes from WordPress without building a signing server:** [app.epasscard.com](https://app.epasscard.com) · [WordPress plugin](https://wordpress.org/plugins/epasscard/)
