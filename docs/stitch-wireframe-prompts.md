# Stitch prompts — wireframes

One prompt per screen. Every label below is the wording the built application actually
uses, so the wireframes will match the code rather than an idealised version of it.

**How to use these.** Stitch generates one screen per prompt. Paste **Block A** at the top
of every prompt, then one numbered screen block underneath it. If Stitch offers a fidelity
or style setting, set it to low fidelity or wireframe first — Block A forces it either
way, but the setting gets you there in one pass instead of two.

---

## Block A — paste at the top of every prompt

```
Low-fidelity WIREFRAME, not a finished visual design.

Style rules, follow exactly:
- Greyscale only. White background, mid-grey 1.5px outlines, light grey fills for
  placeholder blocks, dark grey text. No colour anywhere.
- No photographs and no illustrations. Show an image area as an empty rectangle with a
  thin diagonal cross through it.
- No shadows, no gradients, no rounded pills, no decorative flourishes.
- Icons only as simple single-weight line glyphs, and only where I ask for one.
- Use the exact wording I give you. Never substitute lorem ipsum or invented copy.
- Desktop frame 1280px wide. Content sits in a centred container with 40px side margins.
- All spacing in multiples of 4px. Cards have a 1px outline, 12px corner radius, 24px
  padding.

Every screen has this header, drawn as two stacked full-width bars with a 1px bottom rule:
- Bar one, 64px tall: on the left a small mark of two interlocking heart outlines followed
  by the wordmark "HopeBridge"; in the centre the text links "Home  Programs  About
  Impact" with the current page underlined; on the right "Login/Register" and a filled
  rectangular button labelled "Donate Now".
- Bar two, 48px tall with a light grey fill, ONLY on screens where I say the user is
  signed in: a small uppercase section label on the left, then text links.

Every screen has this footer: four columns — "HopeBridge" with the line "Connecting
compassion with community needs.", then "Quick Links", "Take Part", "Connect" — above a
thin rule and the line "© 2026 HopeBridge. Built for impact."
```

---

## 1. Home — `index.php`

```
Screen: Home. Signed out, so no second bar.

Below the header, top to bottom:

1. A hero band about 460px tall. The image area fills the whole band as a rectangle with a
   diagonal cross. Over the left half: a very large two-line headline "Together, We Can
   Make a Difference."; below it a paragraph "We connect compassionate donors with critical
   community needs. Every contribution builds a stronger, more resilient future for those
   who need it most."; below that two buttons side by side — a filled one "Donate Now" with
   a small heart glyph after the text, and an outlined one "Explore Programs".

2. A single wide card overlapping the bottom edge of the hero by about 64px, split into
   four equal columns by thin vertical hairlines. Each column is a large number above a
   small caption, centred: "1 / People Helped", "1,900 / JOD Donated", "4 / Active
   Programs", "2 / Donors".

3. A row with the heading "Featured programs" on the left and a text link "See all
   programs" on the right.

4. Three equal program cards in a row. Each card, top to bottom: an image rectangle with a
   diagonal cross, 192px tall, with a small outlined pill in its top-left corner reading
   "RELIEF"; a card title; two lines of description text; a line reading "Raised 800 JOD"
   on the left and "of 5,000 JOD" on the right; a thin horizontal progress bar about 20%
   filled; then a divider and two buttons side by side, a filled "Donate Now" and an
   outlined "Read More".
```

---

## 2. Programs — `programs.php`

```
Screen: Active Programs. Signed out.

1. A wide card containing, on the left, the heading "Active Programs" with the line
   "Discover and support initiatives making a real impact." underneath; and on the right, on
   one row, four controls of identical height: a text field with a magnifier glyph inside
   its left edge and the placeholder "Search programs...", a dropdown "All Categories", a
   dropdown "Sort By: Newest", and a filled button "Search".

2. Below it, a three-column grid of four program cards, so the fourth card starts a second
   row on its own.

   Every card has the same structure, top to bottom: an image rectangle with a diagonal
   cross, 192px tall, with a small outlined pill in its top-left corner holding a tiny line
   glyph and a category word; then a title; then two lines of description; then a line with
   a small two-person glyph reading "People helped: N"; then a line with the raised amount
   on the left and the goal on the right; then a thin progress bar filled to match; then a
   divider and two buttons side by side, a filled "Donate Now" and an outlined "Read More".

   Use these four, in this order:
   - HEALTH · "Medical Aid" · People helped: 0 · "Raised 200 JOD" / "of 6,000 JOD" · bar 3% filled
   - FOOD · "Emergency Food Parcels" · People helped: 0 · "Raised 400 JOD" / "of 8,000 JOD" · bar 5% filled
   - EDUCATION · "School Supplies" · People helped: 0 · "Raised 500 JOD" / "of 3,000 JOD" · bar 17% filled
   - RELIEF · "Winter Blankets" · People helped: 1 · "Raised 800 JOD" / "of 5,000 JOD" · bar 16% filled
```

---

## 3. One program — `program.php`

```
Screen: a single program, seen by a signed-in donor. Bar two is present and reads
"MY GIVING" then the links "My donations  Annual statement  Updates  Messages", none
underlined. In bar one, the right side shows "Nadia Rashed" with a small outlined pill
"DONOR" beside it, then the filled "Donate Now" button, then "Logout".

1. A small text link "← All programs" above everything.

2. A wide card: on the left the title "Winter Blankets" with the line "Warm blankets and
   heaters for families during the winter months." beneath it; on the far right a small
   outlined pill holding a tiny heart glyph and the word "RELIEF".

3. Two equal columns side by side, the same height:
   - Left: a tall image rectangle with a diagonal cross, filling the column height.
   - Right: a card containing, top to bottom, "Raised 800.00 JOD" on the left and "of
     5,000.00 JOD" on the right; a thin progress bar 16% filled; a line with a small
     two-person glyph reading "People helped: 1"; the sub-heading "Who this program is
     for"; the line "Families with a monthly income under 300 JOD living in an unheated
     home."; and a filled button "Donate to this program".

4. The heading "What your donation has done", then a single card occupying the left half of
   a two-column grid, with the right half empty. The card holds the bold title "First 120
   blankets delivered", a small grey date line "2026-08-31 08:24:46", and three lines of
   body text.

Note: that donate button only exists because the viewer is signed in as a donor. Signed
out, the same slot holds a tinted notice reading "Please log in to donate to this
program." — draw the signed-in version.
```

---

## 4. Sign in and register — `login.php`, `register.php`

```
Draw these as TWO separate screens side by side on one frame, each 448px wide and centred
in its own half. Signed out, so no second bar.

Screen A — Sign in:
A single card. Centred at the top, the interlocking-hearts mark, then the wordmark
"HopeBridge", then the line "Sign in to continue to your dashboard". Then a field labelled
"Email Address" showing the placeholder "you@example.com"; then a row with the label "Password" on the left and the text link "Forgot
password?" on the right, above a password field with a small eye glyph inside its right
edge; then a checkbox with the label "Remember me for 30 days"; then a full-width filled
button "Login"; then a horizontal rule with the word "OR" centred in it; then two
full-width outlined buttons, "Continue with Google" and "Continue with Facebook", each with
a small square glyph on the left; then the centred line "New to HopeBridge? Register here".

Screen B — Register:
A single card. Centred at the top, "Join HopeBridge." and the line "What type of account
are you creating?". Then two selectable cards side by side, each with a radio circle, a
bold word and a line of description: "Donor / I want to give to a program." and
"Beneficiary / I need help from a program." — show the Donor one as selected, with a
heavier outline. Then fields "Full Name", then "Email Address" showing the placeholder
"you@example.com", then "Password" with an eye glyph inside its right edge. Then a full-width filled button "Create my account", the OR rule, the same two
social buttons, and the centred line "Already have an account? Login".
```

---

## 5. Giving — `donate.php`, `donor_receipt.php`

```
Draw these as TWO screens stacked on one frame. Signed in as a donor, so bar two reads
"MY GIVING" then the links "My donations  Annual statement  Updates  Messages".

Screen A — Donate:
A text link "← Back to Winter Blankets". Then a wide card with the heading "Donate" and the
line "You are giving to Winter Blankets." Then a narrower card containing a field labelled
"Amount in JOD"; below it a row of four small outlined pill buttons "10 JOD", "25 JOD",
"50 JOD", "100 JOD"; and below that a filled button "Donate".

Screen B — Receipt:
A text link "← My donations". Then a wide card with the heading "Donation receipt" and the
line "Receipt #1". Then a card headed "HopeBridge" containing a two-column table with the
left column as row labels: "RECEIPT NUMBER / #1", "DATE / 2026-06-12 10:15:00", "DONOR /
Nadia Rashed", "EMAIL / donor@example.com", "PROGRAM / Winter Blankets", "AMOUNT / 250.00
JOD" with the amount in bold. Under the table the small line "Thank you for supporting
HopeBridge." Then a filled button "Print this receipt".
```

---

## 6. Annual statement — `donor_tax_report.php`

```
Screen: a donor's annual statement. Signed in as a donor, bar two reads "MY GIVING" with
"Annual statement" underlined.

1. A wide card holding only the heading "Annual statement 2026" and the line "Everything
   you gave in 2026, ready to print for your records." underneath. Nothing on the right —
   there is no year picker, because this donor has only given in one year.

2. A single wide card split into four equal columns by thin vertical hairlines, each a
   large number over a small caption, centred: "700 / JOD Given", "4 / Donations", "175 /
   Average Gift", "250 / Largest Gift".

3. A full-width tinted callout box on one line: "The cause you supported most in 2026 was
   Winter Blankets, with 400.00 JOD." — with the program name in bold.

4. A card headed "HopeBridge" with the small grey line "Annual statement for Nadia Rashed ·
   2026" beneath it, containing a four-column table. Header row: "Date | Program | Receipt
   | Amount". Body rows, exactly these:
   - 2026-06-12 10:15:00 | Winter Blankets | #1 | 250.00 JOD
   - 2026-07-03 18:40:00 | Emergency Food Parcels | #2 | 100.00 JOD
   - 2026-07-21 09:05:00 | Winter Blankets | #3 | 150.00 JOD
   - 2026-08-18 16:35:00 | Medical Aid | #6 | 200.00 JOD
   Then a final row in bold, styled like a header: "Total" in the first column, the middle
   two columns empty, and "700.00 JOD" in the last.

5. A filled button "Print this statement" below the card.

Note: once a donor has given in a second year, a dropdown of years and a "Show" button
appear on the right of the card in step 1. Do not draw them here.
```

---

## 7. Beneficiary — `beneficiary_profile.php`, `beneficiary_services.php`

```
Draw these as TWO screens stacked on one frame. Signed in as a beneficiary, so bar two
reads "MY SUPPORT" then the links "Help available  My requests  My profile  Messages".

Screen A — My profile, with "My profile" underlined in bar two:
A wide card with the heading "My profile" and the line "The admin reads these details to
check which programs you can use." Then a full-width tinted callout box reading "Your
account is approved. You can see what help is available." Then the heading "Recent updates
for you" above two narrow boxes, each with a thicker left edge, holding a line of text and
a small grey timestamp beneath it:
- "Your application for Winter Blankets was accepted."  ·  2026-06-06 10:30:00
- "Your account has been approved. You can now apply for help."  ·  2026-06-01 09:00:00
Then the heading "My details" above a card, about 480px wide, with the fields "Phone
number", "City", "How many people live in your home", "Monthly income in JOD", then a tall
text area labelled "Tell us about your situation", then a filled button "Save my details".

Screen B — Help available, with "Help available" underlined in bar two:
A wide card with the heading "Help available" and the line "Read who each program is for,
then apply for the ones that match your situation." Then a TWO-column grid of four cards,
in this order: "Winter Blankets", "School Supplies", "Emergency Food Parcels", "Medical
Aid". Each card: the program title, two lines of description, then the small bold
sub-heading "Who it is for" with one line of criteria in grey beneath it, then a text area
labelled "Why you need this help (optional)", then a filled button "Apply".

Note: the Apply control only exists because this beneficiary has been approved. One who is
still waiting sees a tinted notice reading "You can apply once the admin has approved your
account." and no form at all. Draw the approved version.
```

---

## 8. Administrator dashboard — `admin_dashboard.php`

```
Screen: the administrator's dashboard. Signed in as an administrator, so bar two reads
"ADMINISTRATION" then the links "Dashboard  Beneficiaries  Applications  Manage programs
Donations  Users  Messages", with Dashboard underlined.

1. A wide card with the heading "Dashboard" and the line "An overview of the whole charity."

2. A single wide card split into four equal columns by thin vertical hairlines: "1,900.00 /
   JOD Raised", "7 / Donations", "2 / Donors", "2 / Waiting for You".

3. Two stacked callout boxes: "1 beneficiary profile(s) are waiting to be checked. Review
   them." and "1 application(s) for help are waiting for a decision. Review them."

4. The heading "Money by month" above a table. Header row "Month | Donations | Total",
   then exactly:
   - 2026-08 | 3 | 900.00 JOD
   - 2026-07 | 3 | 750.00 JOD
   - 2026-06 | 1 | 250.00 JOD

5. The heading "Who gives the most" above a table. Header row "Donor | Times | Total |
   Average gift | Last gift", then exactly, with each donor cell holding a name above a
   smaller grey email address:
   - Omar Haddad / giver@example.com | 3 | 1,200.00 JOD | 400.00 JOD | 2026-08-25 08:50:00
   - Nadia Rashed / donor@example.com | 4 | 700.00 JOD | 175.00 JOD | 2026-08-18 16:35:00

6. The heading "How each program is doing" above a table. Header row "Program | Donations |
   Raised | Goal | People helped", then exactly:
   - Winter Blankets | 3 | 800.00 JOD | 5,000.00 JOD | 1
   - School Supplies | 1 | 500.00 JOD | 3,000.00 JOD | 0
   - Emergency Food Parcels | 2 | 400.00 JOD | 8,000.00 JOD | 0
   - Medical Aid | 1 | 200.00 JOD | 6,000.00 JOD | 0
```

---

## 9. Administrator management — `admin_beneficiaries.php`, `admin_programs.php`

```
Draw these as TWO screens stacked on one frame. Signed in as an administrator, bar two as
in screen 8.

Screen A — Beneficiaries, with "Beneficiaries" underlined in bar two:
A wide card with the heading "Beneficiaries" and the line "Check the details people have
given and decide who is eligible." Then a vertical stack of two applicant cards — the one
still waiting comes first, because pending sorts above decided.

Each card: a name with a small outlined status pill beside it; a grey line holding an
email, a phone number and a city separated by middle dots; a small two-column table with
the row labels "People in the home" and "Monthly income"; a paragraph in the person's own
words; a field labelled "Note for this person (optional)"; and two buttons side by side,
a filled "Approve" and an outlined "Reject".

Use these two, in this order:
- "Rania Odeh" · PENDING · waiting@example.com · 0791111111 · Irbid · 4 people · 210.00 JOD
  · "My husband is ill and I am the only one working."
- "Khaled Mansour" · APPROVED · family@example.com · 0790000000 · Zarqa · 6 people ·
  180.00 JOD · "I lost my job last year and I have four children at school."

Screen B — Manage programs, with "Manage programs" underlined in bar two:
A wide card with the heading "Manage programs" and the line "Add a program, switch one off,
or write a report for the donors."

Then the heading "All programs" above a table. Header row "Program | Category | Picture |
Raised | Goal | Shown on the site" plus a final unlabelled column. Four body rows —
"Winter Blankets / Relief", "School Supplies / Education", "Emergency Food Parcels / Food",
"Medical Aid / Health". In every row: the Picture cell holds a small dropdown with a small
outlined "Save" button beneath it; the "Shown on the site" cell holds a small pill reading
"YES"; and the last cell holds a small outlined "Hide" button.

Then the heading "Add a program" above a form card, about 480px wide, with the fields
"Name of the program", "What it does" as a text area, "Category", "Picture" as a dropdown,
"Who it is for" as a text area, "Goal in JOD", and a filled button "Add the program".

Then the heading "Write a report for the donors" above a second form card the same width,
with "Which program" as a dropdown, "Title" as a field, "What happened" as a tall text
area, and a filled button "Publish the report".
```

---

## If Stitch drifts

It will sometimes add colour or a photograph despite Block A. Two things fix it:

- Re-send with `Redraw in greyscale only. Replace every photograph with an empty rectangle
  crossed by a diagonal line.`
- Ask for one screen at a time. The stacked pairs above (4, 5, 7, 9) are the ones most
  likely to come back over-designed; splitting them into separate prompts usually settles it.

Keep the wording exactly as written. The point of these wireframes is that an assessor can
hold them beside the running site and see the same labels in the same places.
