# How to Add a Membership Card to Apple Wallet (WordPress Guide)

**Suggested URL:** `https://epasscard.com/blog/how-to-add-membership-card-to-apple-wallet/`  
**Primary keywords:** how to add membership card to apple wallet, create apple wallet pass, apple wallet membership card  
**Secondary:** digital membership card, membership wallet pass, apple wallet pass plugin  
**Meta title:** How to Add a Membership Card to Apple Wallet on WordPress | EpassCard  
**Meta description:** Step-by-step: issue a digital membership card to Apple Wallet and Google Wallet from WordPress. Works with MemberPress, WooCommerce Subscriptions, and UMP.

---

Members ask for this constantly: "Can I put my membership card in Apple Wallet?"

If you run a WordPress membership site, the answer should be yes — and it should happen automatically when they sign up, not after they email support.

Here is how to set that up.

---

## What you need before you start

- WordPress 6.5 or newer
- A membership plugin: **MemberPress**, **Ultimate Membership Pro**, or **WooCommerce Subscriptions**
- An [EpassCard account](https://app.epasscard.com)
- The [EpassCard WordPress plugin](https://wordpress.org/plugins/epasscard/) installed
- A pass template created in EpassCard (membership card layout with your logo, colors, and fields)

Plan for about 45–60 minutes on your first setup. After that, new members get passes without you touching anything.

---

## Step 1: Design your membership card in EpassCard

Log into [app.epasscard.com](https://app.epasscard.com) and create a pass template.

Include fields you want on the card:

- Member name
- Membership level or plan name
- Member ID or account number
- Expiration or renewal date
- QR code or barcode (for check-in)

Pick colors and upload your logo. This template is what members see on their phone — treat it like a physical card design.

Save the template. You will map WordPress data to these fields in Step 4.

---

## Step 2: Install and connect the WordPress plugin

1. Install **EpassCard** from WordPress.org or upload the plugin folder
2. Activate it
3. Go to **EpassCard → Connection** in wp-admin
4. Sign in with your EpassCard account or paste your API key
5. Confirm the connection shows as active

If the connection fails, double-check your API key and that your server can reach `api.epasscard.com`.

---

## Step 3: Enable your membership integration

On the Connection page, open **Integrations** and enable the module you use:

| Your plugin | Enable this module |
|-------------|-------------------|
| MemberPress | MemberPress |
| Ultimate Membership Pro | Ultimate Membership Pro |
| WooCommerce Subscriptions | WooCommerce Subscriptions |

Save. Only enable what you actually use — unused modules stay out of your way.

---

## Step 4: Map membership fields to pass fields

This is where the membership card gets its data.

**For MemberPress:** Go to **EpassCard → MemberPress**. For each membership product, choose your pass template and map fields:

- MemberPress "Full Name" → pass "Member Name"
- MemberPress "Membership Title" → pass "Plan"
- MemberPress "Expires At" → pass "Expiry Date"
- MemberPress "User ID" or custom field → pass QR code value

**For WooCommerce Subscriptions:** Same idea under **EpassCard → WooCommerce Subscriptions** — map subscription and customer fields.

**For Ultimate Membership Pro:** Map under the UMP section.

Save each mapping. Different membership tiers can use different pass templates — a "Gold" member can get a different card design than "Basic."

---

## Step 5: Test with a real signup

Create a test membership (use a coupon for 100% off if you need to).

When the signup completes:

1. EpassCard creates the pass via API
2. The member gets a link — by email (if enabled) or in their account area
3. They tap **Add to Apple Wallet** on iPhone or **Add to Google Wallet** on Android
4. The membership card appears in their wallet app

Open Apple Wallet on your test iPhone and confirm the name, dates, and QR code look right.

---

## Step 6: Turn on member access and reminders (optional)

**Member account page:** Members can open passes from the Wallet Passes tab in their account — useful when they delete the email.

**Push reminders:** Under EpassCard notification settings, configure reminders for subscription expiry, renewal, or trial ending. These show as lock screen notifications tied to the pass.

**Email delivery:** Enable automatic pass-link emails on creation if you want every new member to get the link without visiting their account.

---

## What members see on their phone

After adding the pass:

- Card shows on the lock screen when relevant (near your location, if configured)
- QR code or barcode ready for scanning at check-in
- Pass updates when their membership data changes in WordPress
- Push notifications for expiry or renewal reminders

They do not need your website open. They do not need a separate app.

---

## Apple Wallet vs Google Wallet for members

Issue both. The same pass template and mapping produce links for each platform. iPhone users tap Apple Wallet; Android users tap Google Wallet. One setup covers your whole membership base.

---

## Common problems and fixes

**Member says they never got a pass link**
Check the Issued Passes table in wp-admin. If no pass exists, the mapping may be missing for that membership product. If a pass exists, resend the link manually.

**Wrong expiry date on the card**
Check your field mapping. Make sure you mapped the correct MemberPress or subscription date field — not "signup date" when you meant "expiration date."

**QR code does not scan**
Verify the mapped value is not empty and uses a format your scanner expects. Test with the same scanner you use at check-in.

**Pass did not update after renewal**
Confirm the renewal event triggers a sync. Check the API log under EpassCard if updates fail silently.

---

## Frequently asked questions

### Does MemberPress include Apple Wallet support?

No. MemberPress does not ship wallet pass features. You need a plugin like EpassCard to connect MemberPress memberships to Apple Wallet and Google Wallet.

### Can members add the card themselves without admin help?

Yes — once automatic issuance is configured, every new member gets a self-service link. They tap one button to add the card.

### Do I need a Mac or Xcode to create Apple Wallet passes?

No. EpassCard generates the pkpass file from your template. You design in the browser; no Apple developer tools required on your end.

### What about Passbook? Is that different?

Passbook was renamed to Apple Wallet in 2015. Same pkpass format, same "Add to Wallet" flow. Older members may still say "Passbook."

### Can I issue passes to existing members?

Yes. From the admin Issued Passes table or member screen, create a pass manually or bulk-process members who signed up before you enabled EpassCard.

---

**Next steps:** [Start free at app.epasscard.com](https://app.epasscard.com) · [MemberPress integration details](/memberpress-apple-wallet-google-wallet/) · [Install WordPress plugin](https://wordpress.org/plugins/epasscard/)
