# Trello board — HopeBridge

The board itself has to be created from your own Trello account; this file is what to
put in it. Every card below describes work that was actually done, in the order it was
done, so the board reads as a true record rather than a story invented afterwards.

**Board name:** `HopeBridge — PHP & SQL Project`

**Lists:** `Backlog` · `In progress` · `Review` · `Done`

---

## Done

Move these straight to **Done**. They are finished and verifiable in the repository.

| Card | Description to paste |
| --- | --- |
| Database schema | Ten tables covering users, beneficiaries, programs, donations, requests, updates, notifications, messages, remember tokens and password resets. `schema.sql` rebuilds the whole thing including example data. |
| Registration and sign-in | Account type chosen first, `password_hash()` for storage, identical message for a wrong email and a wrong password so the form cannot be used to discover accounts. |
| Stay signed in | Random token in a cookie, only its SHA-256 hash in the database. HttpOnly, SameSite=Lax, thirty days. Signing out deletes both. |
| Forgotten password | One-hour single-use reset link. Asking again cancels the older one. Changing the password signs every remembered browser out. |
| Donor: browse programs | Search, category filter and three sort orders, all in one GET form so results are shareable. |
| Donor: give to a program | Amount plus four suggested figures. Writes a donation and goes straight to the receipt. |
| Donor: history and receipts | Every gift listed, each with a printable receipt. A receipt query filters by donor as well as number. |
| Donor: progress updates | Reports scoped by a join back through the donor's own donations, so they only see programs they funded. |
| Beneficiary: profile and eligibility | Register once, fill in circumstances, see the approval state and any note from the administrator. |
| Beneficiary: apply for help | Eligibility printed beside each program. Applying is blocked in PHP until approved, and limited to one open application per program. |
| Beneficiary: track applications | Every application with its status and the charity's reply. |
| Private messaging | Two-way channel between any user and the administrator. |
| Admin: approvals | Approve or refuse profiles and applications, with a note back. Every decision writes a notification. |
| Admin: dashboard and reports | Totals, money by month, who gives most with frequency and average, and how each program is doing against its goal. |
| Admin: manage programs | Add, hide, set category and picture, publish progress reports. |
| Admin: access control | Only a donor account can be promoted. An administrator cannot change their own access, so the last one can never be removed. |
| Responsive layout | Verified at 375, 390, 700, 900 and 1280px. Every control at least 44px on a phone. |
| Design system | Teal and clay, one type scale, everything on a 4px grid, no inline styles anywhere. |
| Annual statement (extra feature) | One page per year: every gift itemised, total, average, largest, and the cause supported most. Printable. |
| Accessibility pass | Skip link, visible focus rings, heading levels that do not skip, `prefers-reduced-motion` respected. |
| Publish to GitHub | https://github.com/MayaHelmi/hopebridge-charity-platform |

---

## Review

| Card | Description to paste |
| --- | --- |
| Wireframes and mockups | Mockups committed under `docs/mockups/`; wireframes drawn against the built application and linked from the README. Needs a read-through before submission. |
| Presentation deck | Thirteen slides including an honest slide on what is not finished. Needs a rehearsal pass for timing. |

---

## In progress

| Card | Description to paste |
| --- | --- |
| Google and Facebook sign-in | The whole flow is written in `oauth.php`, including a CSRF state check. Blocked on developer credentials from Google Cloud and Meta — the keys in `config.php` are empty, so it has never been run against the real services. |

---

## Backlog

| Card | Description to paste |
| --- | --- |
| Payment provider | Donating records a row; no money moves. Would need a provider wired into `donate.php` before this could take real donations. |
| Send email properly | Reset links are written to a file outside the web root because there is no mail server. One `file_put_contents` call becomes a send-mail call. |
| Multi-currency | Amounts are JOD throughout. Would need a currency column on donations and a rate at the time of giving. |
| SMS and email reminders | Notify donors of new reports and beneficiaries of decisions, rather than waiting for them to log in. |
| Photographs for every program | Two categories still fall back to a coloured panel. Prompts for generating matching images are in `docs/image-prompts.md`. |

---

## A note on the labels

If you want labels, three are enough and they map to the brief: **Donor**, **Beneficiary**,
**Administrator**. Add a fourth, **Blocked**, for the two cards waiting on credentials —
it is worth an assessor seeing that the reason is access, not effort.
