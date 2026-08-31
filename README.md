# HopeBridge

A charity platform built with plain **PHP 8** and **MySQL**, for the Orange Digital
Center PHP & SQL project. Three kinds of user share one site: **donors** who give,
**beneficiaries** who ask for help, and **administrators** who run the charity.

No framework, no Composer, no build step. Flat PHP files, one stylesheet, and a
`schema.sql` that creates the database and fills it with example data.

---

## Design

The interface follows the **HopeBridge** design system: deep teal `#00685f` for the
brand and primary actions, clay `#b05e3d` reserved for *Donate Now*, Inter
throughout, a 1280px container, and every margin, padding and gap on a **4 point
grid**. Cards are 12px rounded with a soft `0 4px 12px` shadow.

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

Wireframes and the user-story traceability table (every lettered story in the brief
mapped to the screen that satisfies it):
<https://claude.ai/code/artifact/ee7581e7-0fd3-4300-9faf-c33d9b030992>

> **Note:** that wireframe document still shows the earlier layout and the earlier
> *Amal Charity* name. It needs to be redrawn against the HopeBridge design above
> before it is handed in.

---

## Version control

This folder is a git repository, committed as `MayaHelmi`. **No remote is configured and
nothing has been pushed** — creating the GitHub repository and pushing is yours to do.

## Running it

You need PHP 8 and MySQL running locally.

Create the database and the example data:

```bash
mysql -u root < schema.sql
```

Start the site:

```bash
php -S localhost:8000
```

Then open <http://localhost:8000>.

If your MySQL root user has a password, put it in `config.php`.

### Following a password reset

There is no mail server, so a reset link is not emailed. It is appended to
`hopebridge-outbox.txt` in the folder **above** this one — open that file and follow the
newest link. The site itself deliberately says nothing about this, because a visitor
should only ever see "a reset link has been sent".

### Accounts to log in with

| Role | Email | Password |
| --- | --- | --- |
| Administrator | admin@hopebridge.jo | admin123 |
| Donor | donor@example.com | pass123 |
| Donor | giver@example.com | pass123 |
| Beneficiary (approved) | family@example.com | pass123 |
| Beneficiary (waiting to be approved) | waiting@example.com | pass123 |

Every name, email and amount in `schema.sql` is invented for the demo.

---

## The pages

**Public**

| Page | What it does |
| --- | --- |
| `index.php` | Home. Hero, four live numbers counted from the database, three featured programs. |
| `programs.php` | Every active program, with search, a category filter and three sort orders. |
| `program.php` | One program: photo, progress, who it is for, and its published reports. |
| `about.php` | What the platform is and how the three roles work. |
| `impact.php` | Live totals, a per-program table, and every report the admins have published. |
| `login.php` / `register.php` | Email and password, **Remember me**, plus the Google and Facebook buttons. |
| `forgot.php` | Ask for a password reset link. |
| `reset.php` | Choose a new password from that link. |

**Donor**

| Page | What it does |
| --- | --- |
| `donate.php` | Choose an amount and give to a program. |
| `donor_donations.php` | Everything this donor has given, with a link to each receipt. |
| `donor_receipt.php` | A printable receipt for one donation. |
| `donor_updates.php` | Reports about the programs this donor actually gave to. |

**Beneficiary**

| Page | What it does |
| --- | --- |
| `beneficiary_profile.php` | Their details, their status, and their notifications. |
| `beneficiary_services.php` | What help is available and how to apply. |
| `beneficiary_requests.php` | Every application and what happened to it. |

**Administrator**

| Page | What it does |
| --- | --- |
| `admin_dashboard.php` | Totals, money by month, who gives the most, how each program is doing. |
| `admin_beneficiaries.php` | Approve or refuse the people asking for help. |
| `admin_requests.php` | Decide each application, with the details needed to judge it. |
| `admin_programs.php` | Add programs, hide them, and publish reports for donors. |
| `admin_donations.php` | Every donation received. |
| `admin_users.php` | Who can reach the admin side. |

`messages.php` is shared: donors and beneficiaries write to the admin, and the admin
picks who to reply to.

---

## The database

Ten tables: `users`, `beneficiaries`, `programs`, `donations`, `requests`,
`updates`, `notifications`, `messages`, `remember_tokens`, `password_resets`.
See `schema.sql`.

Two decisions worth knowing about:

- **`config.php` reads the role from the database on every request**, not only at
  login. An administrator whose access is taken away loses it immediately instead of
  keeping it until they log out.
- **There is deliberately no "you cannot remove the last administrator" check.** You
  are not allowed to change your own access, so anyone you are able to demote implies
  a second administrator already exists. The check would be unreachable.

---

## Security

- Passwords are stored with `password_hash()`; the real password is never saved.
- Every query uses a **PDO prepared statement**. The sort order on `programs.php`
  comes from a fixed list rather than the address bar.
- Everything a user typed is printed through `htmlspecialchars()`.
- Every page checks the session role before showing anything, and a donor cannot open
  another donor's receipt.
- The session id is regenerated on login, so a session id somebody already knew is useless.

## Navigation

The site has **two bars**, and the split is what stops anyone getting stuck.

The **site bar** is identical for everybody, signed in or not: Home, Programs, About,
Impact. An administrator deep in the Users page can still reach the public site in one
click. It also names who is signed in and in which role, so nobody has to guess which
account they are looking at.

The **section bar** appears only once you are logged in, and holds the pages belonging to
your role under a heading — *My giving*, *My support* or *Administration*. Because every
page in a role sits in that bar, each one is a single click from all of its siblings.

Pages you drill *into* — a single program, the donate form, one receipt — also carry a
back link naming where you came from, rather than relying on the browser button.

On a phone the site bar collapses into the menu button, while the section bar stays
visible and slides sideways, so the page you are on is never hidden behind a menu.

## Movement

One curve for the whole site, `cubic-bezier(0.2, 0, 0, 1)` — quick to leave, slow to
settle, no bounce — and two speeds: 120ms for things you touch, 220ms for panels.

The page performs **one** entrance: the title band arrives, the cards follow on a short
stagger, and that is all. There is no scroll-triggered fade on every element and nothing
loops. Program cards deepen their shadow on hover but do **not** lift or zoom, because
the card as a whole is not clickable — only the two buttons inside it are, and it must
not pretend otherwise.

Every hover style sits behind `@media (hover: hover)`. A touch screen has no hover, but a
browser will still apply those styles on tap and leave them stuck until you touch
something else; behind that query they never fire on a phone. A faint teal
`-webkit-tap-highlight-color` acknowledges the press instead of the browser's grey box.

Anyone whose system asks for reduced motion gets none of it; `prefers-reduced-motion`
collapses every animation and transition, and printing disables them too.

## JavaScript

There is one small script, `script.js`, and it does one thing: the **eye button** next to
each password box, which shows and hides what has been typed. The buttons are written into
the page with the `hidden` attribute already on them and the script is what removes it, so
somebody with JavaScript switched off never sees a button that would not work.

The two pictures, `images/eye.svg` and `images/eye-off.svg`, are set in `style.css`, so the
button itself is an empty tag and the script only has to add or remove a class. The meaning
is carried by the `aria-label`, which changes with the state, because the button has no
words in it.

Everything else on the site, including the mobile menu, works without JavaScript.

### Staying logged in, and forgotten passwords

**Remember me** keeps a random 32 byte token in a cookie and only its **SHA-256 hash**
in `remember_tokens`, so a stolen copy of the table cannot be used to log in as anybody.
The cookie is `HttpOnly` (JavaScript cannot read it) and `SameSite=Lax` (not sent from
another site), and it lasts 30 days. Logging out deletes both the cookie and the row.

**Forgot password** works the same way: a one-hour, single-use token whose hash is stored
in `password_resets`. Asking for a new link cancels any older unused one, and changing
the password deletes every "remember me" token for that account, so a password change
really does sign other browsers out.

`forgot.php` always shows the same message whether or not the address is registered, and
it writes a line to the outbox either way, so the page cannot be used to discover which
email addresses have accounts.

### Verified

Checked by hand against the running site:

- All 23 pages load with **no PHP warnings or notices** — 45 page/role combinations
  covering anonymous, donor, approved beneficiary, waiting beneficiary and administrator,
  including with missing and nonsense parameters.
- Signed-out and wrong-role visitors are redirected away from every private page.
- A donor opening someone else's receipt is refused.
- A beneficiary who has not been approved cannot apply; the insert does not happen.
- `?sort=' OR 1=1--` does not change the query.
- A `<script>` tag stored in a program category comes back escaped.
- Donating writes the row and the totals move.
- Laid out correctly at 375px, 768px and 1440px.
- **Tap targets measured at 390px wide.** Every control on the donor pages is at least
  44px tall except the logo, which is 114px wide and is not a primary action. Table
  links and the back link use padding with a matching negative margin, so the target
  grows without the row getting taller.
- Every spacing value in the stylesheet is a multiple of 4, and every font size is on
  the type scale. There are **no inline styles left in any PHP file**.
- On a phone, a table's action column stays pinned to the right edge while the rest of
  the row scrolls under it, so *View*, *Save* and *Make admin* are never scrolled out of
  reach. Tables whose last column is only data are left alone — checked on all six.
- Measured **zero layout shift** on load: images have no width and height attributes but
  their containers are sized in CSS, so nothing jumps into place.
- **Remember me:** no cookie and no row when the box is unticked; with it ticked the
  cookie signs the browser back in after the session is thrown away; the raw token is
  not present anywhere in the database; logging out removes the row and the stale
  cookie is then refused.
- **Forgot password:** a real and an unknown address produce identical pages; the link
  opens the form; a made-up token, a used token and an expired token are all refused;
  mismatched and too-short passwords are rejected; after a reset the old password fails,
  the new one works, and remembered sessions are gone.
- The outbox file cannot be read over HTTP — checked with a canary string against five
  path-traversal attempts.
- A `../../../etc/passwd` in the program picture field is reduced to a bare file name.

### Not real

- **Payment is simulated.** Donating writes a row in `donations`. No money moves and no
  payment provider is involved. The interface does not claim otherwise — there is no card
  form and no "payment successful" wording anywhere — but it no longer carries a notice
  saying so either, because the site is meant to present as a finished product. **Before
  this is ever put in front of real donors, a payment provider has to be wired in.**
- **There is no mail server, so reset links are not emailed.** They are appended to
  `hopebridge-outbox.txt` in the folder *above* the website, which the web server cannot
  serve. In production that one `file_put_contents` call becomes a send-mail call and
  nothing else changes.
- **Google and Facebook login is written but has never been run against real keys.**
  `oauth.php` does the full flow with a CSRF `state` check, but the keys in
  `config.php` are empty, so the buttons show a setup message. The Facebook Graph
  version is pinned to `v19.0` and may be out of date.

---

## Pictures

- `images/logo.png` is the HopeBridge mark, two interlocking hearts. It was supplied with a
  transparent background, so it sits directly on the bar with nothing behind it, and it is
  used three ways: beside the wordmark in the top bar, above the sign-in card, and as the
  browser tab icon.

  It was resized, and its two colours were corrected. As supplied the hearts were
  `#02464C` and `#CF512A`, which are close to the brand but not the same; they are now
  exactly `--primary` `#00685F` and `--accent` `#B05E3D`, so the mark matches the wordmark
  beside it and the Donate button. Each visible pixel was mapped to whichever heart it
  belonged to and its transparency left alone, so the edges are as smooth as they were.
- `images/icons/` holds the interface icons, one small SVG each: the magnifier in the
  search field, the people mark beside "People helped", the heart on the hero's Donate
  button, the bars on the small-screen menu button, and one per programme category. They
  are set as CSS backgrounds rather than written into the markup, so a page carries no
  icon code, and a category the icons do not cover falls back to a plain tag.
- `images/` holds the pictures the site itself uses. `planting.jpg` is the illustration
  on the About page. `hero.jpg` is the original download and is left untouched;
  `hero-crop.jpg` is the same picture with the AI rendering artefact trimmed off the top,
  and that is the one the home page uses.
- `images/programs/` holds the program photographs. **Drop a `.jpg` or `.png` in
  there and it appears in the picture menu** on *Manage programs*, for both new
  programs and existing ones.
- All four example programs have a photograph, and so does the About page. All five were
  generated from the prompts in [docs/image-prompts.md](docs/image-prompts.md) and are set
  in the Levant, to match the Jordanian towns and JOD amounts in the data. Each was resized
  to 1400px wide and otherwise left exactly as supplied.
- A program with **no** picture falls back to its category on a teal panel, so a missing
  or renamed file never leaves a broken image on the page.
