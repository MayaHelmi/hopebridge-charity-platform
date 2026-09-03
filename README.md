# HopeBridge

A charity website built with plain **HTML, CSS and a little JavaScript**, for the
Orange Digital Center project.

| Deliverable | Link |
| --- | --- |
| Repository | <https://github.com/MayaHelmi/hopebridge-charity-platform> |
| Empathy maps &amp; journeys | <https://claude.ai/code/artifact/5d09f5fe-6aa4-4578-8dc7-573b25607512> |
| Mockups | [docs/mockups/](docs/mockups/) — four screens plus the design system |
| Wireframes | <https://claude.ai/code/artifact/e30ecb73-492b-4ee4-95c5-8d6df0c2e06c> |
| Presentation slides | <https://claude.ai/code/artifact/2e9acccb-8b28-4044-a67f-694e05d1dc80> |
| Trello board | *to be created — the cards to enter are in [docs/trello-board.md](docs/trello-board.md)* |

Three kinds of user share one site: **donors** who give, **beneficiaries** who ask for
help, and **administrators** who run the charity. Every screen belonging to all three
is here as a finished page.

No framework, no build step: 28 HTML pages, one stylesheet, one small script.

---

## ⚠️ This used to be a PHP and MySQL application

Up to commit `eb48c14` this project was a working PHP 8 + MySQL application with real
accounts, sessions, an approval workflow and a database. **That has been replaced by this
HTML and CSS version, so the following no longer exist:**

- registering, logging in and logging out, and the password hashing behind them
- the "remember me" cookies and the forgotten-password links
- Google and Facebook sign-in
- the MySQL database and all eight of its tables
- saving anything at all — donating, applying for help, approving a beneficiary,
  publishing a report or sending a message

Every page now shows **saved example information**. Pressing a button that used to send
something to the server shows a short note saying so, rather than looking broken.

The PHP version is still in this repository's history. To read it or bring it back:

```bash
git checkout eb48c14
```

---

## Running it

There is nothing to install and no database to create. **Double-click
`index.html`** and the whole site works — every link, picture and stylesheet is
relative, so it runs straight off the disk.

If you would rather serve it over `http://`, run this **from inside this folder**:

```bash
php -S localhost:8000
```

Then open <http://localhost:8000>. PHP is only acting as a plain file server here;
there is no PHP left in the project.

> On macOS, `python3 -m http.server` may refuse to start from a folder inside
> `Desktop` with *Operation not permitted*, because that Python has not been granted
> access to the Desktop. The command above avoids that.

Because these are plain files, this version **can** be published on GitHub Pages, which
the PHP version could not.

## Looking around

There is no login, so the front door to each role's pages is on
[login.html](login.html), under **OR LOOK AROUND**:

| Role | Starts at |
| --- | --- |
| Donor | [donor-donations.html](donor-donations.html) |
| Beneficiary | [beneficiary-profile.html](beneficiary-profile.html) |
| Administrator | [admin-dashboard.html](admin-dashboard.html) |

Inside a role, the second navigation bar links that role's pages to each other. The top
bar always returns to the four public pages.

---

## Design

The interface follows the **HopeBridge** design system: deep teal `#00685f` for the
brand and primary actions, clay `#b05e3d` reserved for *Donate Now*, Inter throughout,
a 1280px container, and every margin, padding and gap on a **4 point grid**. Cards are
12px rounded with a soft `0 4px 12px` shadow.

### Mockups

| Screen | Mockup |
| --- | --- |
| Home | [01-home.png](docs/mockups/01-home.png) |
| Programs | [02-programs.png](docs/mockups/02-programs.png) |
| Login | [03-login.png](docs/mockups/03-login.png) |
| Register | [04-register.png](docs/mockups/04-register.png) |

The full colour, type, spacing and component specification is in
[docs/mockups/DESIGN-SYSTEM.md](docs/mockups/DESIGN-SYSTEM.md).

### Wireframes

Every screen drawn as structure rather than pixels, with numbered notes on the decisions
that are not obvious from the drawing:
<https://claude.ai/code/artifact/e30ecb73-492b-4ee4-95c5-8d6df0c2e06c>

⚠️ The wireframes and slides were written for the PHP version, so where they describe
logging in, the database or saving a record, they describe the old application rather
than these pages.

---

## The pages

**Public — four pages anybody can reach**

| Page | What it shows |
| --- | --- |
| [index.html](index.html) | The landing page: the hero, what the charity does, and the programs in brief. |
| [programs.html](programs.html) | Every active program, with the search, category and sort controls. |
| [program-1.html](program-1.html) … [program-4.html](program-4.html) | One program: its picture, goal, who it is for, and its progress reports. |
| [about.html](about.html) | Who HopeBridge is and the three kinds of account. |
| [impact.html](impact.html) | The totals: money raised, families helped, programs running. |

**Signing in — the forms, without anything behind them**

[login.html](login.html) · [register.html](register.html) · [forgot.html](forgot.html)

**Donor**

| Page | What it shows |
| --- | --- |
| [donor-donations.html](donor-donations.html) | Every donation with a running total. |
| [donor-statement.html](donor-statement.html) | The annual statement, grouped by program. |
| [donor-receipt.html](donor-receipt.html) | A printable receipt for one donation. |
| [donor-updates.html](donor-updates.html) | Progress reports for the programs this donor funded. |
| [donate.html](donate.html) | Choosing an amount. |
| [donor-messages.html](donor-messages.html) | A conversation with the charity. |

**Beneficiary**

| Page | What it shows |
| --- | --- |
| [beneficiary-services.html](beneficiary-services.html) | What help exists and who each program is for. |
| [beneficiary-requests.html](beneficiary-requests.html) | Every application and what was decided. |
| [beneficiary-profile.html](beneficiary-profile.html) | The eligibility details, the approval state, and notifications. |
| [beneficiary-messages.html](beneficiary-messages.html) | A private conversation with the charity. |

**Administrator**

| Page | What it shows |
| --- | --- |
| [admin-dashboard.html](admin-dashboard.html) | Money raised, money by month, who gives most, how each program is doing. |
| [admin-beneficiaries.html](admin-beneficiaries.html) | The profiles waiting to be checked. |
| [admin-requests.html](admin-requests.html) | The applications waiting for a decision. |
| [admin-programs.html](admin-programs.html) | The programs, the add form, and the report form. |
| [admin-donations.html](admin-donations.html) | Every donation. |
| [admin-users.html](admin-users.html) | Everyone with an account, and who can reach the admin pages. |
| [admin-messages.html](admin-messages.html) | Writing to any donor or beneficiary. |

---

## Navigation

Two bars. The **site bar** is the same on every page, so no role is ever trapped. The
**section bar** appears on a role's pages and links that role's pages to each other, so
each one is a single click from all of its siblings. Both are wrapped in one sticky
element, because the top bar changes height when its links wrap.

## Loading

Pictures sit on a shimmering panel that the picture paints over once it arrives. It is
pure CSS on `.photo:has(img)`, with nothing to switch off — and it is deliberately
scoped to `:has(img)`, so a program with no photograph keeps its plain teal panel
instead of shimmering for ever.

## Movement

Content settles in on load. Everything is behind `prefers-reduced-motion`, and all
`:hover` rules are behind `@media (hover: hover)` so a tap does not leave a stuck
hover state on a phone.

## JavaScript

Three small pieces in [script.js](script.js), and the site works without any of them:

1. the eye button that shows and hides a typed password,
2. removing the shimmer once a picture has loaded,
3. the note explaining that a button has nothing to send to.

## Responsive

One column on a phone, two on a tablet, the full grid on a desktop. Tap targets are at
least 44px. Wide tables scroll inside their own box with the last column pinned, so no
data is dropped and the page itself never scrolls sideways.

## Pictures

Programme photographs live in `images/programs/`; the rest of the furniture is in
`images/`.

---

## Version control

Committed as `MayaHelmi` and pushed to
<https://github.com/MayaHelmi/hopebridge-charity-platform>.
