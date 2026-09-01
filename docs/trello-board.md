# Trello board — HopeBridge

The board has to be created from your own Trello account. This file is what to put in it,
arranged so it takes a couple of minutes rather than half an hour.

**Board name:** `HopeBridge — PHP & SQL Project`

**Lists, left to right:** `Backlog` · `In progress` · `Review` · `Done`

---

## The fast way to enter it

Trello's card composer splits a multi-line paste into separate cards. So for each list:

1. Click **Add a card**.
2. Paste one whole block below.
3. Trello asks whether to add them as separate cards — say yes.

If your Trello does not offer that, the blocks still work pasted one line at a time.

Add the descriptions afterwards, and only to the cards that need one — they are further
down this file. A card whose title says enough does not need a description.

---

### Backlog — paste this

```
Payment provider
Send email properly
Multi-currency donations
SMS and email reminders
Photographs for the remaining programs
Per-donor giving preferences
```

### In progress — paste this

```
Google and Facebook sign-in
```

### Review — paste this

```
Wireframes and mockups
Presentation deck
```

### Done — paste this

```
Database schema
Registration and sign-in
Stay signed in
Forgotten password
Donor: browse programs
Donor: give to a program
Donor: history and receipts
Donor: progress updates
Beneficiary: profile and eligibility
Beneficiary: apply for help
Beneficiary: track applications
Private messaging
Admin: approve beneficiaries and applications
Admin: dashboard and reports
Admin: manage programs
Admin: access control
Responsive layout
Design system
Annual statement
Accessibility pass
Publish to GitHub
```

---

## Labels

Four are enough, and they map to the brief: **Donor**, **Beneficiary**, **Administrator**,
and **Blocked**. Put *Blocked* on the two cards waiting on credentials — it is worth an
assessor seeing that the reason is access, not effort.

---

## Descriptions

Only for the cards where the title does not carry the whole story.

| Card | Description |
| --- | --- |
| Database schema | Ten tables: users, beneficiaries, programs, donations, requests, updates, notifications, messages, remember_tokens, password_resets. `schema.sql` rebuilds the whole thing including example data. |
| Registration and sign-in | Account type chosen first. `password_hash()` for storage. A wrong email and a wrong password give the same message, so the form cannot be used to discover who has an account. |
| Stay signed in | Random 32-byte token in a cookie, only its SHA-256 hash in the database. HttpOnly, SameSite=Lax, thirty days. Signing out deletes both. |
| Forgotten password | One-hour single-use link. Asking again cancels the older one. Changing the password deletes every remembered-browser token for that account. |
| Donor: browse programs | Search, category filter and three sort orders in one GET form, so any result is a shareable address. The sort order is chosen from a fixed list in PHP, never taken from the address bar. |
| Donor: progress updates | Reports scoped by a join back through the donor's own donations, so a donor only sees programs they actually funded. |
| Donor: history and receipts | Every gift listed with a printable receipt. The receipt query filters by donor as well as by receipt number, so one donor cannot open another's. |
| Beneficiary: apply for help | Eligibility printed beside each program. Applying is blocked in PHP until the profile is approved, and limited to one open application per program. |
| Admin: approve beneficiaries and applications | Approve or refuse, with a note back. Every decision writes a notification the beneficiary sees on their profile. |
| Admin: dashboard and reports | Totals, money by month, who gives most with frequency and average gift, and how each program is doing against its goal. |
| Admin: access control | Only a donor account can be promoted. An administrator cannot change their own access, which is what makes removing the last one impossible. |
| Responsive layout | Verified at 375, 390, 700, 900 and 1280px. Every control at least 44px tall on a phone. A table pins its action column so it cannot scroll out of reach. |
| Design system | Teal and clay, one type scale, everything on a 4px grid, no inline styles anywhere in the PHP. |
| Annual statement | The extra feature. One page per year: every gift itemised with its receipt number, plus total, average, largest and the cause supported most. Printable. Added up by SQL from the same rows the receipts come from. |
| Accessibility pass | Skip link, visible focus rings, heading levels that do not skip, `prefers-reduced-motion` respected, hover styles behind `@media (hover: hover)` so they do not stick on touch. |
| Publish to GitHub | https://github.com/MayaHelmi/hopebridge-charity-platform |
| Wireframes and mockups | Mockups in `docs/mockups/`. Wireframes drawn against the built application and linked from the README. Sixteen Stitch prompts in `docs/stitch-wireframe-prompts.md` for regenerating them. |
| Presentation deck | Thirteen slides, including one on what is not finished. Needs a rehearsal pass for timing. |
| Google and Facebook sign-in | **Blocked.** The whole flow is written in `oauth.php`, including a CSRF state check. Waiting on developer credentials from Google Cloud and Meta — the keys in `config.php` are empty, so it has never run against the real services. |
| Payment provider | **Blocked.** Donating records a row; no money moves. A provider would be wired into `donate.php` before this could take real donations. |
| Send email properly | There is no mail server, so reset links are written to a file outside the web root. One `file_put_contents` call becomes a send-mail call. |
| Multi-currency donations | Amounts are JOD throughout. Would need a currency column on donations and the rate at the time of giving. |
| Per-donor giving preferences | The dashboard shows which programs are most funded, but not that a particular donor favours one. A `GROUP BY donor, program` away. |
| Photographs for the remaining programs | All four programs have photographs now. This card is for any program added later — the generation prompts are in `docs/image-prompts.md`. |

---

## Once it exists

Paste the board's share link into the deliverables table at the top of the README, where
the placeholder currently sits.
