# Stitch prompts — wireframes

Sixteen prompts covering all 24 pages. Every label below is the wording the built
application actually uses, so the wireframes will match the code rather than an idealised
version of it.

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

## 10. Messages — `messages.php`

```
Draw these as TWO screens stacked on one frame. The same page serves both sides, and the
difference between them is the point.

Screen A — the administrator's view. Bar two reads "ADMINISTRATION" with "Messages"
underlined; bar one shows "Site Admin" with an "ADMIN" pill, then "Logout".
A wide card with the heading "Messages" and the line "Choose who you want to write to."
Then a row of four outlined pill buttons, the first one filled to show it is selected:
"Khaled Mansour (beneficiary)", "Nadia Rashed (donor)", "Omar Haddad (donor)",
"Rania Odeh (beneficiary)".
Then the heading "Conversation with Khaled Mansour".
Then a dashed-outline empty box reading "No messages yet. Write the first one below."
Then a card about 640px wide with a text area labelled "Your message" and a filled button
"Send".

Screen B — the beneficiary's view. Bar two reads "MY SUPPORT" with "Messages" underlined;
bar one shows "Khaled Mansour" with a "BENEFICIARY" pill, then "Logout".
A wide card with the heading "Messages" and the line "Write to the charity if you have a
question or need more support. Only you and the admin can read this."
There is NO row of people to choose from — a beneficiary only ever writes to the charity.
Then the heading "Conversation with Site Admin", the same dashed empty box, and the same
composer card.

For reference, once a conversation exists each message is a small card holding a grey line
with the sender and a timestamp above the message text, and the viewer's own messages sit
on a lightly tinted card instead of a white one.
```

---

## 11. Applications — `admin_requests.php`

```
Screen: the administrator's queue of applications. Bar two reads "ADMINISTRATION" with
"Applications" underlined; bar one shows "Site Admin" with an "ADMIN" pill, then "Logout".

1. A wide card with the heading "Applications for help" and the line "Each application,
   with the details you need to decide."

2. A vertical stack of two application cards, the undecided one first.

   Each card: the program name with a small outlined status pill beside it; a grey line
   holding the applicant's name, email, city and the date applied, separated by middle
   dots; a second grey line reading "Home: N people · income NNN.NN JOD"; the applicant's
   own words as a paragraph; a field labelled "Reply for this person (optional)"; and two
   buttons side by side, a filled "Accept" and an outlined "Refuse".

   Use these two, in this order:
   - "Emergency Food Parcels" · PENDING · Khaled Mansour · family@example.com · Zarqa ·
     applied 2026-08-20 19:10:00 · Home: 6 people · income 180.00 JOD ·
     "Groceries have been hard to cover this month."
   - "Winter Blankets" · APPROVED · Khaled Mansour · family@example.com · Zarqa ·
     applied 2026-06-05 12:00:00 · Home: 6 people · income 180.00 JOD ·
     "We have no heating and four children at home."
```

---

## 12. A donor's lists — `donor_donations.php`, `donor_updates.php`

```
Draw these as TWO screens stacked on one frame. Signed in as a donor: bar two reads
"MY GIVING" then "My donations  Annual statement  Updates  Messages"; bar one shows
"Nadia Rashed" with a "DONOR" pill, the filled "Donate Now" button, then "Logout".

Screen A — My donations, with "My donations" underlined:
A wide card with the heading "My donations" and the line "You have given 700.00 JOD in
total. Thank you." Then a full-width table. Header row "Number | Program | Amount | Date |
Receipt", then exactly:
- #6 | Medical Aid | 200.00 JOD | 2026-08-18 16:35:00 | View
- #3 | Winter Blankets | 150.00 JOD | 2026-07-21 09:05:00 | View
- #2 | Emergency Food Parcels | 100.00 JOD | 2026-07-03 18:40:00 | View
- #1 | Winter Blankets | 250.00 JOD | 2026-06-12 10:15:00 | View
Each "View" is a text link.

Screen B — Updates, with "Updates" underlined:
A wide card with the heading "Updates" and the line "What has happened in the programs you
gave to." Then a vertical stack of two cards, each with a bold report title, a small grey
line holding the program name and a timestamp, and three lines of body text:
- "Food parcels reach 85 families" · Emergency Food Parcels · 2026-08-31 08:24:46
- "First 120 blankets delivered" · Winter Blankets · 2026-08-31 08:24:46
```

---

## 13. A beneficiary's applications — `beneficiary_requests.php`

```
Screen: the applications one beneficiary has sent. Bar two reads "MY SUPPORT" with
"My requests" underlined; bar one shows "Khaled Mansour" with a "BENEFICIARY" pill, then
"Logout", and no Donate button.

1. A wide card with the heading "My requests" and the line "Every application you have sent
   and where it has got to."

2. A vertical stack of two cards, the undecided one first. Each card: the program name with
   a small outlined status pill beside it; a grey line reading "Applied on" and a
   timestamp; a line beginning "What you wrote:" followed by the applicant's own words; and
   for a decided application, a tinted box beginning "Reply from the charity:".

   Use these two, in this order:
   - "Emergency Food Parcels" · PENDING · applied 2026-08-20 19:10:00 · "Groceries have
     been hard to cover this month." · no reply box
   - "Winter Blankets" · APPROVED · applied 2026-06-05 12:00:00 · "We have no heating and
     four children at home." · reply box reading "Reply from the charity: Approved -
     blankets delivered in June."
```

---

## 14. The public pages — `about.php`, `impact.php`

```
Draw these as TWO screens stacked on one frame. Signed out, so no second bar.

Screen A — About, with "About" underlined in bar one:
A wide card with the heading "About HopeBridge" and the line "Connecting compassion with
community needs." Then a two-column row: on the left a card headed "What this platform
does" with two paragraphs; on the right a tall image rectangle with a diagonal cross.
Then the heading "The three kinds of account" above three equal cards titled "Donors",
"Beneficiaries" and "Administrators", each with a paragraph and a text link at the very
bottom — "Register as a donor", "Apply for help", "Administrator login" — and the three
links must sit on the same line as each other even though the paragraphs differ in length.
Then the heading "How a donation travels" above three equal cards titled "1. Choose",
"2. Give", "3. See it work", each with two lines of text.

Screen B — Impact, with "Impact" underlined in bar one:
A wide card with the heading "Our impact" and the line "Every number here is counted from
our own records, as it stands today." Then a single wide card split into four equal columns
by thin vertical hairlines: "1 / People Helped", "1,900 / JOD Donated", "7 / Donations
Made", "2 / Registered Donors".
Then the heading "How each program is doing" above a table. Header row "Program | Category
| Raised | Goal | People helped", then:
- Winter Blankets | Relief | 800.00 JOD | 5,000.00 JOD | 1
- School Supplies | Education | 500.00 JOD | 3,000.00 JOD | 0
- Emergency Food Parcels | Food | 400.00 JOD | 8,000.00 JOD | 0
- Medical Aid | Health | 200.00 JOD | 6,000.00 JOD | 0
Then the heading "Reports from the field" above a two-column grid of two cards, each with a
bold report title, a grey program-and-date line, and three lines of body text:
- "Food parcels reach 85 families" · Emergency Food Parcels · 2026-08-31 08:24:46
- "First 120 blankets delivered" · Winter Blankets · 2026-08-31 08:24:46
```

---

## 15. The administrator's tables — `admin_donations.php`, `admin_users.php`

```
Draw these as TWO screens stacked on one frame. Signed in as an administrator, bar two
reads "ADMINISTRATION" with the relevant link underlined; bar one shows "Site Admin" with
an "ADMIN" pill, then "Logout".

Screen A — Donations, with "Donations" underlined:
A wide card with the heading "Donations" and the line "Everything that has been given to
the charity." Then a full-width table. Header row "Number | Donor | Program | Amount |
Date", with each Donor cell holding a name above a smaller grey email address. Seven body
rows, newest first, exactly these:
- #7 | Omar Haddad / giver@example.com | Winter Blankets | 400.00 JOD | 2026-08-25 08:50:00
- #6 | Nadia Rashed / donor@example.com | Medical Aid | 200.00 JOD | 2026-08-18 16:35:00
- #5 | Omar Haddad / giver@example.com | Emergency Food Parcels | 300.00 JOD | 2026-08-09 11:00:00
- #4 | Omar Haddad / giver@example.com | School Supplies | 500.00 JOD | 2026-07-28 14:22:00
- #3 | Nadia Rashed / donor@example.com | Winter Blankets | 150.00 JOD | 2026-07-21 09:05:00
- #2 | Nadia Rashed / donor@example.com | Emergency Food Parcels | 100.00 JOD | 2026-07-03 18:40:00
- #1 | Nadia Rashed / donor@example.com | Winter Blankets | 250.00 JOD | 2026-06-12 10:15:00

Screen B — Users, with "Users" underlined:
A wide card with the heading "Users" and the line "Everyone with an account, and who can
reach the admin pages." Then a tinted notice reading "Only donor accounts can be made
admins. Beneficiary records hold private information, so those accounts are kept out of
the admin side." Then a table. Header row "Name | Email | Role | Signed up with | Access",
then exactly these five rows:
- Site Admin | admin@hopebridge.jo | admin | local | the small grey words "this is you"
- Nadia Rashed | donor@example.com | donor | local | a small outlined button "Make admin"
- Omar Haddad | giver@example.com | donor | local | a small outlined button "Make admin"
- Khaled Mansour | family@example.com | beneficiary | local | an em dash
- Rania Odeh | waiting@example.com | beneficiary | local | an em dash
```

---

## 16. Password reset — `forgot.php`, `reset.php`

```
Draw these as TWO screens side by side on one frame, each 448px wide and centred in its own
half. Signed out, so no second bar.

Screen A — Forgot password:
A single card. Centred at the top, "Forgot password?" and the line "Enter your email address
and we will send you a link to choose a new one." Then a field labelled "Email Address"
showing the placeholder "you@example.com", then a full-width filled button "Send me a reset
link", then the centred line "Remembered it? Back to login".

Screen B — Choose a new password:
A single card. Centred at the top, "New password" and the line "Choose the password you
want to use from now on." Then a field labelled "New password" with a small eye glyph
inside its right edge, then a field labelled "Type it again" with the same glyph, then a
full-width filled button "Save my new password", then the centred line "Changed your mind?
Back to login".

For reference, once the form on screen A is submitted the card replaces the form with a
tinted box reading "If that email address has an account, a reset link has been sent to it.
The link stops working after one hour." — and an expired or reused link turns screen B into
a tinted warning reading "This link does not work any more. It may have been used already,
or it may be more than an hour old." above a link "Ask for a new link".
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
