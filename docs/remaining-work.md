# What is left (team checklist)

Backend, database auth, quizzes, leaderboard, shared header/nav, account strip, and PHP static shells are in place. This list is what still matters for a **strong final submission** and a smooth group demo.

---

## Marissa (layout, static polish, rubric)

- **Footer content** (`includes/footer.php`): optional real footer text (copyright, course name, client line). File is wired; it is empty on purpose for now.
- **Second font** (course rule): add another font family (e.g. system sans for body) so headings and body differ clearly.
- **Images + `alt`**: add any required imagery under `images/` with meaningful `alt` text.
- **HTML/CSS validators**: run [W3C HTML](https://validator.w3.org/) and [CSS](https://jigsaw.w3.org/css-validator/) on all pages; fix errors and warnings.
- **External links**: any off-site link must use `target="_blank"` and `rel="noopener noreferrer"`.
- **Responsive pass**: confirm nav and forms at a narrow viewport; adjust media queries if anything breaks.

---

## Yuheng (JavaScript)

- **`js/quiz.js`**: client-side answer feedback, focus states, or light UX on quiz pages (server still grades everything).
- **`js/forms.js`**: optional non-blocking hints on login/register if you want parity with server validation.
- **`js/admin.js`**: only if admin pages need client behavior beyond plain forms.

No npm or frameworks—vanilla JS only.

---

## Everyone

- **Content**: replace placeholder copy on About / Contact / Categories with real client-facing text where the rubric expects it.
- **Demo data**: enough questions per category and difficulty for `QUIZ_QUESTION_COUNT` (see `includes/config.php`) so quizzes always load during grading.
- **End-to-end test**: register, log in, take each difficulty, check leaderboard, admin CRUD, log out—on a real `http://localhost/...` URL (not `file://`).

---

## Reference docs

- Full rules: [`course-expectations.md`](course-expectations.md)  
- Structure and flows: [`architecture-outline.md`](architecture-outline.md)  
- Setup: root [`README.md`](../README.md)
