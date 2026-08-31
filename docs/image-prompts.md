# Prompts for the programme photographs

> **Status, 31 Aug 2026 — every photograph on the site is done.** Maya generated all five
> from the prompts below and chose the Levant option, so the whole set is consistent with
> the Jordanian towns and JOD amounts in the data.
>
> The four programme photographs are in `images/programs/` as `blankets.jpg`,
> `food-parcels.jpg`, `classroom.jpg` and `clinic.jpg`. The About page photograph is
> `images/planting.jpg`. All five were resized to 1400px wide and otherwise left exactly
> as supplied.
>
> The prompts are kept here so the set can be regenerated or extended later. Nothing is
> outstanding.

These are the prompts that produced the programme photographs.

**Target size:** wide landscape, 16:9 or wider. The existing photos are 1408 × 768.
The card crops them to roughly 2:1, so keep the subject near the centre.

**Where to put the result:** save into `images/programs/` as a `.jpg` or `.png`. It then
appears automatically in the **Picture** menu on *Manage programs*, and you can attach it
to a program from there — no code change needed.

Every prompt ends with the same guard clause. Keep it. The supplied hero image arrived
with a fake, garbled browser toolbar rendered into the top of the picture, which had to
be cropped off; that line is there to stop it happening again.

---

## 1. Winter Blankets — save as `blankets.jpg`

> A warm documentary photograph of aid workers handing thick folded wool blankets to a
> family outside a modest concrete home on a cold winter day. Overcast winter light with
> a low warm sun breaking through from the side. Neat stacks of folded blankets in muted
> teal, cream and terracotta tones. Genuine, dignified expressions, candid, nobody
> looking at the camera. Photojournalistic style, photorealistic, shallow depth of field,
> natural colour grading. Wide landscape composition with the subject centred and calm
> space at the edges.
>
> No text, no lettering, no signage, no watermarks, no logos, no user interface elements,
> no browser or app chrome anywhere in the image.

## 2. Emergency Food Parcels — save as `food-parcels.jpg`

> A warm documentary photograph of volunteers packing cardboard food boxes at a community
> distribution point. Rice, cooking oil, lentils, sugar and tinned goods visible in open
> boxes on a long trestle table. Soft daylight from a large side window, warm golden tone,
> fine dust visible in the light. Volunteers in plain clothes working together, hands in
> motion, natural candid expressions. Muted teal and terracotta accents in the clothing.
> Photojournalistic style, photorealistic, shallow depth of field, natural colour grading.
> Wide landscape composition with the subject centred.
>
> No text, no lettering, no signage, no watermarks, no logos, no user interface elements,
> no browser or app chrome anywhere in the image.

---

## Optional — a note on where the photographs are set

Worth deciding before you generate. The seed data is Jordanian: amounts are in JOD and
the families are in Zarqa, Mafraq and Irbid. The two photographs that came with the
design read as sub-Saharan Africa, so the four cards will not look like one charity if
the new two are set in Jordan.

Either set the new two to match the existing pair, or regenerate all four for the Levant.
If you want the second option, add this sentence to each of the two prompts above and use
the two below as well:

> Set in an urban Jordanian neighbourhood in the Levant, Middle Eastern people, low-rise
> pale stone and concrete buildings, dry hills in the far distance.

### 3. School Supplies — replacement for `classroom.jpg`

> A warm documentary photograph of children in a bright primary school classroom opening
> new school bags and stationery. Pale stone walls, large windows with soft golden
> daylight, simple wooden desks. Children absorbed in what they are doing, candid, nobody
> looking at the camera. Muted teal and terracotta accents. Photojournalistic style,
> photorealistic, shallow depth of field. Wide landscape composition.
>
> No text, no lettering, no signage, no watermarks, no logos, no user interface elements,
> no browser or app chrome anywhere in the image.

### 4. Medical Aid — replacement for `clinic.jpg`

> A warm documentary photograph of a small community medical clinic, a nurse in a simple
> white coat preparing medicines at a counter while a colleague sorts supplies behind her.
> Clean, modest room, pale walls, soft daylight from a side window, warm tone. Candid and
> unposed. Muted teal accents in the room and clothing. Photojournalistic style,
> photorealistic, shallow depth of field. Wide landscape composition.
>
> No text, no lettering, no signage, no watermarks, no logos, no user interface elements,
> no browser or app chrome anywhere in the image.

---

## 5. The About page photograph — `images/planting.jpg`

Used by `about.php` as an illustration beside the opening card. It replaced the
reforestation picture that came with the original design, which read as sub-Saharan
Africa and was the last image on the site not matching the Levant set.

> A warm documentary photograph of neighbours working together on a small community
> project in an urban Jordanian neighbourhood in the Levant — tending a shared garden
> plot beside low-rise pale stone buildings, dry hills in the far distance. Middle
> Eastern people of mixed ages working side by side, candid and unposed, nobody looking
> at the camera. Late afternoon golden light. Muted teal and terracotta accents in the
> clothing. Photojournalistic style, photorealistic, shallow depth of field. Wide
> landscape composition.
>
> No text, no lettering, no signage, no watermarks, no logos, no user interface elements,
> no browser or app chrome anywhere in the image.

---

## After you have the files

1. Put them in `images/programs/`.
2. Log in as the administrator and open **Manage programs**.
3. Pick the file in the **Picture** column for that program and press **Save**.

Nothing else needs changing. If a picture ever goes missing the card falls back to the
teal category panel on its own.
