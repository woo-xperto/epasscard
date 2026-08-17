# EpassCard — Unified Modules & Popular Integrations Roadmap

**Status:** Draft for product planning  
**Date:** 2026-07-20  
**Plugin:** `epasscard` (WordPress connector for Apple Wallet / Google Wallet)  
**Install signals:** WordPress.org `active_installs` where available (fetched Jul 2026). Premium-only products use vendor / market estimates and are marked.

---

## 0. Next version — locked module set (6 new)

**Decision (2026-07-20):** Keep the long-term roadmap as written. For the **next version**, ship **exactly these six** new modules (plus keep MemberPress / UMP / WooCommerce Subscriptions).

| # | Category | Plugin | .org / signal | Module slug (proposed) | Why this pair |
| --- | --- | --- | --- | --- | --- |
| 1 | Membership | **Paid Memberships Pro** | ~100,000+ (free core) | `paid-memberships-pro` | **Shipped** — module + loader |
| 2 | Membership | **Simple Membership** | **40,000+** | `simple-membership` | **Shipped** — module + loader (QA pending) |
| 3 | Events | **Event Tickets** (TEC add-on) | **90,000+** | _(merged)_ | **Removed as standalone module (1.0.7)** — attendees issue via The Events Calendar |
| 3b | Events | **The Events Calendar** | **700,000+** | `the-events-calendar` | **Shipped** — event mapping; Event Tickets attendees; venue fields; attendees list UI |
| 4 | Events | **Events Manager** | **70,000+** | `events-manager` | **Shipped** — event mapping; booking status hooks |
| 5 | Gift cards | **PW WooCommerce Gift Cards** | **20,000+** | `pw-gift-cards` | **Shipped** — product mapping; create/balance/deactivate hooks |
| 6 | Gift cards | **YITH WooCommerce Gift Cards** | **10,000+** | `yith-gift-cards` | **Shipped** — product mapping; generation + status/balance hooks |

**Already shipping (unchanged):** `memberpress`, `ultimate-membership-pro`, `woocommerce-subscriptions`.

**After this version, module count:** 3 existing + 6 new = **9 integrations**.

**Explicitly deferred (still on roadmap, not this version):** Ultimate Member, Paid Member Subscriptions, Restrict Content Pro, Amelia, Bookly, Tickera, Smart Coupons, Points & Rewards, LMS, Pass Studio full Phase 0 (can land partially if time allows — see note below).

**Build note for next version**

- Prefer **same `EPC_Module` pattern** as today (no full Pass Studio rewrite required to ship these six).
- Optionally add a thin `epc_issue_pass()` facade while building (low risk, helps later Phase 0).
- Full Pass Studio + Woo Product → Pass remains Phase 0 on the long-term plan; do not block these six modules on it.

**Global pass-field rule — lifetime expiry**

When an **expiry / end date** source field is mapped but the source has no real end (lifetime membership, until cancelled, empty event end, `0000-00-00`, SWPM `noexpire`, MemberPress lifetime sentinel), always send **now + 99 years** via `epc_format_pass_expiry_timestamp()` / `epc_format_pass_expiry_datetime()` in `epc-dependencies.php`. Never send blank or “No expiry” text to the pass API for mapped expiry fields.

**Suggested build order inside the next version**

1. Paid Memberships Pro  
2. Simple Membership  
3. Event Tickets  
4. Events Manager  
5. PW Gift Cards  
6. YITH Gift Cards  

Est. effort for six modules only (reuse current module base): **~45–70 hours** coding + basic tests (not including full Phase 0 Studio).

---

## 1. Goal

Ship wallet-pass support for the **most popular WordPress plugins** (typically **1,000+ active installs** or a clear commercial customer base), while building one **unified Pass Studio UI** so site owners do not need a different setup flow per integration.

**Positioning**

> EpassCard is the wallet-pass layer for WordPress — not another membership, tickets, or gift-card plugin.

**Two tracks (run in parallel)**

| Track | Purpose |
| --- | --- |
| **A — Popular modules** | First-class adapters for high-demand plugins (polished, supported, marketed) |
| **B — Unified core + UI** | One Pass Intent engine + WooCommerce bridge + PHP/REST API so anything else can connect |

Track B reduces pressure to build endless Track A modules. Track A wins SEO, trust, and “Works with X” listings.

---

## 2. Current state (baseline)

Already shipped:

| Module | Slug | Notes |
| --- | --- | --- |
| MemberPress | `memberpress` | Premium; large commercial base |
| Ultimate Membership Pro | `ultimate-membership-pro` | Commercial |
| WooCommerce Subscriptions | `woocommerce-subscriptions` | Premium Woo extension |

Existing foundations to reuse:

- `EPC_Module` abstract + `epc_register_module()`
- Field mapping UI, pass sync lifecycle, email, My Account
- Hooks: `epc_before_sync_pass`, `epc_pass_synced`, mapping filters

**Gap:** Core is still framed as “membership modules.” Commerce / tickets / gifts need the same UX with different trigger language (order paid, ticket issued, gift redeemed).

### 2.1 Migration stance — keep modules, evolve core (not a rewrite)

**Decision: do not replace MemberPress / UMP / WooCommerce Subscriptions with a brand-new system.**

| Keep as-is (near term) | Evolve gradually | Do not do |
| --- | --- | --- |
| Module classes, hooks, field resolvers | Add thin adapter methods (`get_triggers()`, logo) for Pass Studio | Big-bang rewrite of `EPC_Module` |
| Saved mappings, `epc_passes` rows, source IDs | Route sync through `epc_issue_pass()` wrapper around existing `EPC_Pass_Service::sync_pass()` | Force sites to re-map fields |
| Per-module admin screens (until Studio is default) | Studio becomes the preferred UI; old screens remain or redirect | Break live pass issuance during migration |
| `epc_register_module()` | Same registry; new modules use the same base | Separate “v2 modules” product line |

**Recommended path**

1. **Phase 0a** — Add `epc_issue_pass()` as a facade over current `sync_pass()`. Existing modules keep calling their current flow (or switch call site only — same DB identity).
2. **Phase 0b** — Pass Studio lists existing modules as Sources (read their slug, entities, mapping). No rewrite of MemberPress logic.
3. **Phase 0c** — Optionally deprecate duplicate per-module settings pages in favor of Studio links (“Open in Pass Studio”).
4. **Later** — When touching a module for a feature, slim it toward the thin-adapter contract. No deadline to rewrite all three at once.

**Compatibility promise for customers**

- Existing mappings and issued passes keep working.
- No mandatory re-onboarding after upgrade.
- New plugins (PMPro, Event Tickets, …) ship as additional `EPC_Module` classes — same pattern you already use.

**When a full rewrite *would* be justified (not now)**

- Only if `EPC_Module` becomes impossible to extend for tickets/gifts (e.g. hard-coded “membership only” assumptions everywhere). Even then: extract shared sync + UI first; migrate one module at a time behind a flag.

---

## 3. Unified product (Track B) — build this once

### 3.1 Pass Intent API (internal + public)

Single contract every module and third party uses:

```text
epc_issue_pass( array (
  'source'      => 'plugin_slug',   // namespace (required)
  'source_id'   => '123',           // unique in that source
  'entity_id'   => 'plan_or_product_id',
  'user_id'     => 0,               // or resolve from email
  'email'       => 'a@b.com',
  'template_id' => 'tpl_xxx',
  'fields'      => array( 'name' => '...', 'qr' => '...' ),
  'action'      => 'upsert',        // upsert | void | notify
) );
```

Also expose:

- `epc_update_pass()` / `epc_void_pass()`
- REST: `POST /wp-json/epasscard/v1/passes` (application password / nonce / capability)
- Document for agencies: 10-line snippet + “Works with EpassCard” badge

### 3.2 WooCommerce Universal Bridge (highest leverage)

WooCommerce: **7,000,000+** active installs. Many gift-card, ticket, booking, and membership plugins already create WC orders/products.

**Admin UI: “Product → Pass” rules**

| Setting | Example |
| --- | --- |
| When | Order status becomes Processing / Completed |
| Product / category | Product ID or category |
| Template | EpassCard template picker |
| Field map | Order meta, customer, product meta, line item |
| Void when | Refunded / cancelled / failed |

This alone covers a large share of gift cards, FooEvents-style tickets, digital downloads, and custom products **without** a dedicated module.

### 3.3 Pass Studio UI (easy + attractive)

One admin experience for all sources. Suggested IA:

```text
EpassCard
├── Connection          (API key — exists)
├── Pass Studio         (NEW hub)
│   ├── Templates       (from API, preview cards)
│   ├── Automations     (unified list of rules)
│   └── Sources         (detected plugins + Woo bridge)
├── Passes              (issued list — exists)
├── Notifications
└── Developers          (PHP/REST snippets, webhooks)
```

**Automations screen (hero UX)**

1. **Pick a source** — cards with logos: MemberPress, WooCommerce, Event Tickets, Gift Cards, “Custom / REST”, “Any Woo product”.
2. **Pick a trigger** — chips: Signup · Renew · Order paid · Ticket created · Gift issued · Manual.
3. **Pick a template** — visual template thumbnails from EpassCard API.
4. **Map fields** — drag source → pass field (keep current mapper; polish).
5. **Delivery** — email / My Account / order email toggle.
6. **Test** — “Issue test pass to my email”.

Design principles:

- One wizard for every module (modules only supply triggers + source fields).
- Show **live pass preview** (Apple/Google mock) while mapping.
- Empty state: “No automations yet — connect a popular plugin or use WooCommerce products.”
- Detect installed plugins and show **Recommended** badges with install-ready CTAs.

### 3.4 Module contract (simplify adapters)

Each popular plugin module becomes thin:

1. `is_available()` / logo / label  
2. `get_triggers()` — human labels + WP hooks  
3. `get_source_fields()` / `get_mappable_entities()`  
4. On trigger → resolve field bag → `epc_issue_pass()`  

No direct HTTP to EpassCard API from modules.

---

## 4. Target plugins (Track A)

**Inclusion rule:** ~**1,000+** WordPress.org active installs **or** clear paid ecosystem (Woo.com / MemberPress-class).  
Numbers below are approximate WordPress.org buckets unless noted. Re-check before each release marketing page.

### 4.1 Membership & access (pass type: membership / ID card)

| Priority | Plugin | Install signal | Why |
| --- | --- | --- | --- |
| P0 Done | **MemberPress** | Premium (large commercial base) | Already shipped; keep improving |
| P0 Done | **Ultimate Membership Pro** | Premium | Already shipped |
| P0 | **Paid Memberships Pro** | ~100,000+ (widely cited; .org free core) | Largest free membership footprint |
| P1 | **Ultimate Member** | **200,000+** | Huge community/profile base; pair with paid gate |
| P1 | **Paid Member Subscriptions** (Cozmoslabs) | **10,000+** | Strong free→pro funnel |
| P1 | **Simple Membership** | **40,000+** | High installs, simpler API surface |
| P1 | **Restrict Content** / **Restrict Content Pro** | Free **9,000+**; Pro commercial | Clean subscription model |
| P2 | **WooCommerce Memberships** | Woo.com premium | Natural if Woo bridge exists |
| P2 | **ProfilePress** / **WP User Manager** | WUM **10,000+** | Profile + membership hybrids |
| P3 | WishList Member, MemberMouse, SureMembers | Premium niches | Demand-driven only |

### 4.2 Subscriptions & commerce (pass type: subscription / loyalty)

| Priority | Plugin | Install signal | Why |
| --- | --- | --- | --- |
| P0 Done | **WooCommerce Subscriptions** | Woo.com premium | Already shipped |
| P0 | **WooCommerce core bridge** | **7,000,000+** | Universal Product → Pass (Track B) |
| P1 | **Easy Digital Downloads** | **40,000+** | Non-Woo digital sellers |
| P2 | **WooCommerce Memberships** | Woo.com | Overlaps 4.1 |

### 4.3 Events & tickets (pass type: event ticket / badge)

| Priority | Plugin | Install signal | Why |
| --- | --- | --- | --- |
| P0 | **Event Tickets** (The Events Calendar) | **90,000+** | Dominant free tickets plugin |
| P0 | **The Events Calendar** (context) | **700,000+** | Ecosystem companion; tickets module is the hook |
| P1 | **Events Manager** | **70,000+** | Large booking/registration base |
| P1 | **Amelia** | **90,000+** | Appointments + events; strong SMB |
| P1 | **Bookly** | **60,000+** | Appointment passes / visit cards |
| P2 | **Tickera** | **2,000+** | Dedicated ticketing; wallet-native audience |
| P2 | **EventON** | **6,000+** | Calendar-heavy sites |
| P2 | **FooEvents** | Woo premium | WC product tickets → may ride Woo bridge first |
| P3 | Modern Events Calendar, Eventin, Event Espresso | Mixed / premium | After P0–P1 demand |
| Internal | **Events Rocket** | Your suite | Strategic cross-sell when M1+ ready |

### 4.4 Gift cards, coupons, loyalty (pass type: gift / coupon / loyalty)

| Priority | Plugin | Install signal | Why |
| --- | --- | --- | --- |
| P0 | **PW WooCommerce Gift Cards** | **20,000+** | Very popular WC gift cards |
| P0 | **YITH WooCommerce Gift Cards** | **10,000+** | Strong brand + free→pro |
| P1 | **Smart Coupons for WooCommerce** (WebToffee) | **30,000+** | Coupons / store credit → wallet coupon |
| P1 | **Advanced Coupons** | **20,000+** | Store credit & coupons |
| P1 | **Points and Rewards for WooCommerce** | **7,000+** | Loyalty balance on pass |
| P2 | **myCred** | **10,000+** | Points ecosystem |
| P2 | Official **WooCommerce Gift Cards** | Woo.com | Demand + Woo Marketplace listing |
| P3 | Gift Up!, other gift plugins | Mixed | Prefer Woo bridge + REST first |

### 4.5 LMS / courses (pass type: student ID / course pass)

| Priority | Plugin | Install signal | Why |
| --- | --- | --- | --- |
| P1 | **Tutor LMS** | **100,000+** | Massive free LMS |
| P1 | **LearnPress** | **70,000+** | High installs |
| P2 | **LifterLMS** | **10,000+** | Membership + courses |
| P2 | **Sensei LMS** | **10,000+** | Automattic ecosystem |
| P2 | **LearnDash** | Premium (major brand) | High willingness to pay for wallet |

### 4.6 Forms & donations (optional / later — issue on form submit)

| Priority | Plugin | Install signal | Notes |
| --- | --- | --- | --- |
| P2 | **Fluent Forms** | **700,000+** | Great for “form → pass” without full module |
| P2 | **WPForms Lite** | **5,000,000+** | Same pattern; Pro paid |
| P2 | **Formidable Forms** | **300,000+** | |
| P3 | **GiveWP** | **100,000+** | Donor card / receipt pass |
| P3 | **Charitable** | **10,000+** | |

Forms are ideal candidates for a **generic “Form confirmation → Pass”** automation in Pass Studio rather than deep plugins.

---

## 5. Release plan (modules one after another)

Assume ~2–4 weeks per deep module after Track B foundation, faster once Pass Studio exists.

### Phase 0 — Foundation (do first)

**Theme:** Unified core so every later module is thin.

| Deliverable | Outcome |
| --- | --- |
| `epc_issue_pass` / update / void | Single sync entry |
| REST pass endpoints | Agencies + headless |
| Pass Studio shell + Automations wizard UI | Attractive unified UX |
| WooCommerce Product → Pass rules | Covers long-tail commerce |
| Module interface v2 (`get_triggers`, logos) | Consistent cards in Studio |
| Developer “Works with EpassCard” doc | Ecosystem story |

**Exit criteria:** Can issue a pass from (1) existing MemberPress flow, (2) a Woo product rule, (3) a 10-line PHP call — all through the same service.

### Phase 1 — Membership expansion (highest wallet-card demand)

| Order | Module | Est. effort | Marketing angle |
| --- | --- | --- | --- |
| 1 | Paid Memberships Pro | M | “PMPro → Apple/Google Wallet” |
| 2 | Simple Membership | S | Volume installs |
| 3 | Paid Member Subscriptions | S–M | |
| 4 | Ultimate Member (levels / paid extensions) | M | Community sites |
| 5 | Restrict Content Pro | S–M | |

### Phase 2 — Events & tickets

| Order | Module | Est. effort | Notes |
| --- | --- | --- | --- |
| 1 | Event Tickets (+ TEC) | M–L | QR ticket = killer demo |
| 2 | Events Manager | M | |
| 3 | Amelia (events + appointments) | M | |
| 4 | Tickera | S–M | Wallet-native buyers |
| 5 | Bookly | M | Appointment card |

Ship Event Tickets early: strongest narrative vs PassKit alternatives.

### Phase 3 — Gift cards & loyalty

| Order | Module | Est. effort | Notes |
| --- | --- | --- | --- |
| 1 | PW Gift Cards | M | |
| 2 | YITH Gift Cards | M | |
| 3 | Smart Coupons / Advanced Coupons | M | Coupon wallet passes |
| 4 | Points & Rewards (+ optional myCred) | M | Balance on pass face |

Many gift flows may already work via **Woo Product → Pass** after Phase 0; dedicated modules add nicer field maps and marketing logos.

### Phase 4 — LMS & stretch

| Order | Module | Notes |
| --- | --- | --- |
| 1 | Tutor LMS | Student / course pass |
| 2 | LearnPress | |
| 3 | LearnDash / LifterLMS | Premium demand |
| 4 | GiveWP donor pass | Optional |
| 5 | Events Rocket | Internal suite synergy |
| 6 | Fluent Forms / WPForms “form → pass” | Generic automation |

### Suggested calendar (flexible)

| Quarter | Focus |
| --- | --- |
| Q1 | Phase 0 foundation + Pass Studio v1 + Woo bridge |
| Q2 | Phase 1 (PMPro → RCP) + Event Tickets |
| Q3 | Rest of Phase 2 + gift cards (PW, YITH) |
| Q4 | Loyalty + LMS + form automation + polish |

Adjust order by: support tickets asking for X, Appsero telemetry, and keyword search volume (`{plugin} apple wallet`).

---

## 6. Prioritization scorecard (use for each candidate)

Score 1–5 each; ship if total ≥ 16 or strategic exception.

| Criterion | Weight idea |
| --- | --- |
| Active installs / customer base | Demand |
| Clear lifecycle hooks (signup, order, ticket) | Feasibility |
| Pass type fit (membership / ticket / gift) | Product fit |
| SEO / competitor gap | Growth |
| Overlap with Woo bridge | Avoid duplicate work |
| Maintenance risk (API churn) | Cost |

**Skip or defer:** plugins with &lt;1k installs unless a paying partner funds the module.

---

## 7. Attractive UI — concrete Pass Studio concepts

### 7.1 Source gallery (first screen)

Grid of cards:

- Logo + name + “Detected” / “Not installed”  
- One-click “Create automation”  
- Badge: Popular · Recommended · New  

### 7.2 Automation list

Table/cards: Source · Trigger · Template · Passes issued · Status (Active/Paused) · Last sync.

### 7.3 Mapping with preview

Split view:

- Left: source fields  
- Right: live Apple Wallet / Google Wallet mock updating as fields map  

### 7.4 Empty / onboarding

3-step welcome after Connection:

1. Choose what you sell (Membership / Tickets / Gift cards / Other)  
2. Detect plugins or offer Woo products  
3. Issue a test pass  

---

## 8. Packaging & commercial options

| Approach | Pros | Cons |
| --- | --- | --- |
| **All modules in core** (feature-flagged) | Simple install, SEO “compatible with” | Bigger plugin ZIP |
| **Core + add-on packs** (Membership Pack, Events Pack, Gift Pack) | Clear upsell | More products to support |
| **Core free connectors + Pro modules** | Freemium growth | Split UX risk |

Recommendation: keep **Pass Studio + Woo bridge + PHP/REST in core**; ship **popular modules in core** initially (like today). Later split heavy packs if ZIP size or Pro gating needs it.

---

## 9. Success metrics

| Metric | Target idea |
| --- | --- |
| Time to first test pass (new install) | &lt; 10 minutes |
| Automations created via Studio vs raw module screens | Studio becomes default |
| % of issuances via Woo bridge / REST (long-tail) | Growing share without new modules |
| Module-specific landing page conversions | One page per P0/P1 plugin |
| Support: “Does it work with X?” | Answer = Studio source card or REST snippet |

---

## 10. Out of scope (for this roadmap)

- Building a membership / ticketing / gift-card product inside EpassCard  
- Native mobile check-in apps (leave to Events Rocket / partners)  
- Guaranteeing every WordPress plugin  
- Deep integrations for abandoned / &lt;1k-install plugins without partner funding  

---

## 11. Immediate next steps

1. **Next version modules are locked** — see §0 (6 plugins). Start build order: PMPro → Simple Membership → Event Tickets → Events Manager → PW → YITH.  
2. Phase 0 (Pass Studio / Woo bridge) stays on the long-term plan; optional thin `epc_issue_pass()` while coding modules.  
3. Add marketing page stubs: “EpassCard for {Plugin}” for each of the six.  
4. Re-verify install counts on wordpress.org the week before release announcement.  
5. After next version ships: revisit Phase 0 + deferred modules (Amelia, Ultimate Member, coupons, etc.).

---

## Appendix A — Quick reference: already vs planned

| Status | Plugins |
| --- | --- |
| Shipped | MemberPress, Ultimate Membership Pro, WooCommerce Subscriptions, Paid Memberships Pro, Simple Membership, Event Tickets, Events Manager, PW Gift Cards, YITH Gift Cards |
| **Next version remaining** | *(none — locked six complete)* |
| Phase 0 (foundation, parallel/later) | Pass Intent API, REST, Pass Studio, Woo Product → Pass |
| Phase 1 remainder | Paid Member Subscriptions, Ultimate Member, Restrict Content Pro |
| Phase 2 remainder | Amelia, Tickera, Bookly |
| Phase 3 remainder | Smart/Advanced Coupons, Points & Rewards |
| Phase 4 | Tutor, LearnPress, LearnDash/LifterLMS, forms, GiveWP, Events Rocket |

## Appendix B — Install snapshot (Jul 2026, WordPress.org)

| Plugin slug | Active installs (approx.) |
| --- | --- |
| woocommerce | 7,000,000+ |
| wpforms-lite | 5,000,000+ |
| the-events-calendar | 700,000+ |
| fluentform | 700,000+ |
| ultimate-member | 200,000+ |
| buddypress | 100,000+ |
| tutor | 100,000+ |
| give | 100,000+ |
| event-tickets | 90,000+ |
| ameliabooking | 90,000+ |
| learnpress | 70,000+ |
| events-manager | 70,000+ |
| bookly-responsive-appointment-booking-tool | 60,000+ |
| simple-membership | 40,000+ |
| easy-digital-downloads | 40,000+ |
| wt-smart-coupons-for-woocommerce | 30,000+ |
| pw-woocommerce-gift-cards | 20,000+ |
| advanced-coupons-for-woocommerce-free | 20,000+ |
| userswp | 20,000+ |
| paid-member-subscriptions | 10,000+ |
| yith-woocommerce-gift-cards | 10,000+ |
| mycred | 10,000+ |
| lifterlms | 10,000+ |
| sensei-lms | 10,000+ |
| charitable | 10,000+ |
| restrict-content | 9,000+ |
| points-and-rewards-for-woocommerce | 7,000+ |
| eventon-lite | 6,000+ |
| tickera-event-ticketing-system | 2,000+ |

Premium-only (no public .org count): MemberPress, WooCommerce Subscriptions/Memberships/Gift Cards, LearnDash, FooEvents, Ultimate Membership Pro, Restrict Content Pro — include by **market importance**, not install bucket alone.
