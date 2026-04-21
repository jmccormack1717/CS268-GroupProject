# Course expectations (assignment rules)

This document records the **graded requirements** for the custom website project so implementation choices stay inside the rules. It complements [`architecture-outline.md`](architecture-outline.md) and the root [`README.md`](../README.md), which describe *how* we build the trivia site.

---

## Client and purpose

1. **Client** — The site must serve a specific client (family, business, student group, etc.). The client cannot be yourself, the instructor, or classmates.

---

## Structure and navigation

2. **Headers** — Every page must include a header.

3. **Page count** — At least **ten** separate pages, each with a meaningful purpose. *(Typical penalty: 1 point per missing page.)*

4. **Navigation** — Pages must include navigation links.

15. **Navbar** — The site must include a **consistent** navigation bar.

16. **Header and footer** — Pages should include a **header** and **footer** section in addition to the navbar.

---

## Layout, responsiveness, and presentation

5. **Responsive design** — Distinct layouts for **large** and **small** screens (e.g. multi-column vs single column). **CSS media queries** are encouraged.

11. **Alignment** — Content should be neatly aligned with clearly visible axes.

12. **Typography** — Use **at least two different font faces** (e.g. headings often sans-serif).

17. **Layout mechanisms** — Implement **Flexbox**, **CSS Grid**, or **both**.

---

## Media and assets

6. **Images** — Include images with descriptive **`alt`** attributes (accessibility and crawlers).

7. **File organization** — Non-HTML resources must be organized: **`images/`** for images, **`css/`** for stylesheets.

---

## Technology restrictions

8. **No libraries or frameworks** — The site **must not** use libraries or frameworks. **Do not use** Bootstrap, jQuery, React, Angular, Vue, or **any other third-party tools**. Re-implementing *ideas* from those tools in plain HTML/CSS/JS is allowed; **using the tools is not**.

---

## Quality, validation, and naming

9. **Validation** — Every page and stylesheet must validate **without warnings or errors** using the [W3C HTML](https://validator.w3.org/) and [W3C CSS](https://jigsaw.w3.org/css-validator/) validators.

10. **File naming** — File names must be **entirely lowercase** and contain **only alphanumeric characters** (no spaces or mixed case in filenames).

14. **Code quality** — Source code must be readable, consistently formatted, and use meaningful names for classes and IDs.

---

## External links

13. **External links** — External links must open in a **new tab or window** (e.g. `target="_blank"` with `rel="noopener noreferrer"` for security), not replace the current page.

---

## Database

18. **Database interfaces** — The site must include interfaces for **user interaction with a database** (e.g. login, account management).

19. **Database storage** — At least **one** database must store information (e.g. user login info, account info).

---

## How this project maps to these rules

| Expectation | Project plan |
|-------------|----------------|
| 10+ pages | Planned pages are listed in `README.md` (12 named pages; minimum 10 required). |
| `images/`, `css/` | Required by spec; keep all images and stylesheets in those folders. |
| No frameworks | Vanilla HTML, CSS, JavaScript, PHP, MySQL only—no Composer/npm UI packages for course deliverables. |
| Flex/Grid + media queries | Documented in `architecture-outline.md` §7. |
| DB + user-facing DB UI | Login, registration, leaderboard, quizzes, admin question management. |
| Validators | Run HTML/CSS validators before major submissions; fix errors and warnings. |
| Lowercase alphanumeric filenames | Match existing plan (`login.php`, `style.css`, etc.); avoid renames that break this rule. |

If a future change conflicts with this file, **treat these course expectations as authoritative** for grading, then reconcile the architecture doc or README.
