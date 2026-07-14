# Passbook vs Apple Wallet: What Changed and What Membership Sites Need to Know

**Suggested URL:** `https://epasscard.com/blog/passbook-vs-apple-wallet/`  
**Primary keywords:** passbook vs apple wallet, passbook apple wallet, passbook  
**Secondary:** apple wallet pass, pkpass, create apple wallet pass, digital wallet pass  
**Meta title:** Passbook vs Apple Wallet — What Changed in 2026 | EpassCard  
**Meta description:** Apple Passbook became Apple Wallet in 2015. The pkpass format stayed the same. Here is what membership site owners need to know about Passbook, Apple Wallet, and issuing digital cards today.

---

People still Google "Passbook" in 2026. Usually because an old tutorial referenced it, or a member said "can you add this to my Passbook?"

Here is the short answer: **Passbook is Apple Wallet now.** Same file format. Same pkpass files. Different app name.

If you are setting up digital membership cards for a WordPress site, you only need to care about Apple Wallet and Google Wallet today. But understanding the Passbook history helps when members use old terminology — and when you find outdated documentation.

---

## The timeline

| Year | What happened |
|------|---------------|
| **2012** | Apple launches Passbook in iOS 6 |
| **2015** | Apple renames Passbook to **Wallet** (iOS 9) |
| **2016+** | Apple Pay cards, keys, and IDs added to Wallet app |
| **2024–2026** | Google rebrands Google Pay passes to **Google Wallet** |

Nothing about the underlying pass file format changed when Passbook became Wallet. Your `.pkpass` files work the same.

---

## Passbook vs Apple Wallet: what is actually different?

| | Passbook (2012–2015) | Apple Wallet (2015–now) |
|---|---------------------|------------------------|
| **App name** | Passbook | Wallet |
| **Pass file format** | `.pkpass` | `.pkpass` (unchanged) |
| **Add button text** | "Add to Passbook" | "Add to Apple Wallet" |
| **Supported passes** | Boarding passes, coupons, tickets, loyalty cards | Same types + more (keys, IDs in supported regions) |
| **Push updates** | Yes | Yes |
| **Works on** | iPhone | iPhone, iPad, Apple Watch |

If you find a blog post from 2013 about "creating Passbook passes," the technical steps for signing pkpass files still apply. Replace every "Passbook" mention with "Apple Wallet" and update any screenshots.

---

## Why people still search "Passbook"

Three reasons:

1. **Old habits.** Long-time iPhone users remember the orange Passbook icon.
2. **Outdated content.** Forum posts and Stack Overflow answers from 2012–2014 rank in Google.
3. **Enterprise docs.** Some internal company guides were written once and never updated.

When a member asks to add their card to Passbook, they mean Apple Wallet. Send them the Apple Wallet link. It works.

---

## Passbook, Apple Wallet, and Google Wallet for membership sites

Modern membership sites should issue passes for **both** platforms:

- **Apple Wallet** (the Passbook successor) for iPhone users
- **Google Wallet** for Android users

EpassCard issues both from one template. Members pick the button for their phone. You do not maintain separate workflows.

---

## Is the pkpass format going away?

No. Apple continues to invest in Wallet — hotel keys, driver's licenses, event tickets, loyalty cards. The pkpass specification gets occasional updates (new field types, NFC support), but the core format is stable.

Google uses its own system (Wallet Objects API), not pkpass. Any pass platform worth using handles both.

---

## Setting up Apple Wallet passes on WordPress (the modern way)

Forget about Passbook-specific tutorials. Here is the current workflow:

1. Create a pass template in [EpassCard](https://app.epasscard.com)
2. Install the [EpassCard WordPress plugin](https://wordpress.org/plugins/epasscard/)
3. Connect MemberPress, WooCommerce Subscriptions, or Ultimate Membership Pro
4. Map membership fields to pass fields
5. Members receive an **Add to Apple Wallet** link on signup

Passes update when membership data changes. No pkpass files to manage manually.

Detailed walkthrough: [How to add a membership card to Apple Wallet](/blog/how-to-add-membership-card-to-apple-wallet/)

---

## Passbook vs Apple Wallet vs a PDF membership card

| | PDF card | Passbook-era pass | Apple Wallet pass (today) |
|---|----------|-------------------|---------------------------|
| **Updates automatically** | No | Yes | Yes |
| **Push notifications** | No | Yes | Yes |
| **Lives on lock screen** | No | Yes | Yes |
| **Works on Android** | Yes (email) | No | Via Google Wallet separately |
| **Gets lost in email** | Often | Less often | Rarely |

PDFs made sense in 2010. Wallet passes make sense for any membership site serious about retention.

---

## Frequently asked questions

### Can I still create "Passbook passes"?

You create Apple Wallet passes. Same pkpass format, same result. Any tool that says "Passbook" in 2026 means Apple Wallet.

### Did Apple Wallet replace Passbook completely?

Yes. The Passbook app was renamed and expanded. There is no separate Passbook app on modern iPhones.

### Do old Passbook passes still work?

Passes added before the rename continued working. Apple Wallet is backward-compatible with existing pkpass files.

### What about Google Passbook?

There was never a Google Passbook. Google had Google Wallet, then Google Pay, then Google Wallet again. Android users add passes through **Google Wallet** today.

### I found a Passbook PHP library on GitHub. Is it still valid?

The signing logic may still work, but check certificate handling and maintenance dates. Unmaintained pkpass libraries are risky for production. A pass platform is safer for membership sites.

---

**Issue Apple Wallet passes (the thing Passbook became):** [app.epasscard.com](https://app.epasscard.com) · [WordPress plugin](https://wordpress.org/plugins/epasscard/)
