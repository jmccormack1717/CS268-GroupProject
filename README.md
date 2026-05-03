# Tech Trivia Quiz Website

This project is a multi-page trivia site about technology. Users pick a category and difficulty, take a short quiz, and store scores that appear on a leaderboard. Administrators can log in and maintain the question bank.

The project is intentionally built with a restricted stack:

- `HTML` for page structure
- `CSS` for layout and responsive design
- `JavaScript` for client-side quiz interaction
- `PHP` for server-side logic
- `MySQL` for persistent data storage

No frontend frameworks or third-party UI libraries should be used.

For the **graded rubric** and a **pre-submission checklist**, see [`docs/course-expectations.md`](docs/course-expectations.md).

## Project Goals

- Build a multi-page website with at least 10 meaningful pages
- Support responsive layouts for desktop and mobile screens
- Use a real database backend for users, questions, and scores
- Provide admin tools for managing quiz content
- Keep the code organized, readable, and valid under course rules

## Proposed Features

### User Features

- Browse quiz categories
- Choose quiz difficulty
- Take quizzes and submit answers
- View score results after each quiz
- View leaderboard rankings
- Create an account or sign in

### Admin Features

- Log in through a protected admin flow
- Add trivia questions
- Edit existing questions
- Delete outdated or incorrect questions
- Manage categories and quiz metadata if needed

## Planned Pages

The assignment requires at least 10 separate pages. This project plan uses the following pages:

1. `index.html` - Redirects to `index.php` (canonical home with shared nav and account bar)
2. `about.html` / `about.php` - Project background (`about.php` has full site chrome; `.html` redirects)
3. `categories.html` / `categories.php` - Category overview text (same `.html` redirect pattern)
4. `easyquiz.php` - Easy difficulty quiz
5. `mediumquiz.php` - Medium difficulty quiz
6. `hardquiz.php` - Hard difficulty quiz
7. `leaderboard.php` - Score rankings
8. `contact.html` / `contact.php` - Contact page (same pattern)
9. `login.php` - User and admin login
10. `register.php` - Account creation
11. `admindashboard.php` - Admin control panel
12. `managequestions.php` - Add, edit, and delete questions

If you want to keep the total page count closer to 10, `register.php` or `contact.html` could be merged later, but keeping them separate usually makes the site clearer.

## Recommended Folder Structure

```text
/
|-- index.html
|-- about.html
|-- categories.html
|-- contact.html
|-- login.php
|-- register.php
|-- easyquiz.php
|-- mediumquiz.php
|-- hardquiz.php
|-- leaderboard.php
|-- admindashboard.php
|-- managequestions.php
|-- css/
|   |-- reset.css
|   `-- style.css
|-- js/
|   |-- quiz.js
|   |-- forms.js
|   `-- admin.js
|-- images/
|   `-- ...
|-- includes/
|   |-- db.php
|   |-- header.php
|   |-- footer.php
|   |-- auth.php
|   |-- functions.php
|   `-- config.php
`-- sql/
    `-- schema.sql
```

This structure aligns well with the course requirements that images stay in `images/` and stylesheets stay in `css/`.

## Architecture Summary

### Frontend

- Use plain HTML for page markup
- Use one shared stylesheet for consistent navbar, footer, spacing, and typography
- Use CSS Flexbox and/or CSS Grid for layout
- Use media queries for mobile responsiveness
- Use vanilla JavaScript for quiz behavior, form validation, and score display

### Backend

- Use PHP to render dynamic quiz, login, admin, and leaderboard pages
- Use reusable files in `includes/` to avoid duplicated code
- Handle sessions for login state and admin authorization
- Validate all form inputs on the server before database updates

### Database

- Use MySQL to store users, questions, categories, quiz attempts, and scores
- Keep admin credentials and question management separate from public quiz access
- Use primary keys and foreign keys to keep records connected correctly

## Suggested Database Tables

### `users`

- `id`
- `username`
- `email`
- `password_hash`
- `role` (`user` or `admin`)
- `created_at`

### `categories`

- `id`
- `name`
- `description`

### `questions`

- `id`
- `category_id`
- `difficulty`
- `question_text`
- `choice_a`
- `choice_b`
- `choice_c`
- `choice_d`
- `correct_choice`
- `created_by`

### `quiz_attempts`

- `id`
- `user_id`
- `category_id`
- `difficulty`
- `score`
- `total_questions`
- `completed_at`

## Core User Flow

1. User opens the home page and reads about the site.
2. User chooses a category or difficulty.
3. User takes a quiz and submits answers.
4. PHP grades the quiz and stores the result in MySQL.
5. User sees their score and can visit the leaderboard.
6. Admin signs in and manages questions from the dashboard.

## Course Requirement Checklist

- At least 10 meaningful pages: planned
- Shared header/navbar/footer: planned
- Responsive layouts: planned with media queries
- Images with alt text: required for all content images
- Organized folders: planned with `css/` and `images/`
- No frameworks/libraries: planned
- Valid HTML and CSS: should be checked before submission
- Multiple font faces: should be included in the final styles
- User-to-database interaction: login, registration, leaderboard, admin question management
- At least one database: MySQL planned

## Local Development Setup

This project is best run in a local PHP and MySQL environment such as:

- `XAMPP`
- `WAMP`
- `MAMP`
- a local Apache + PHP + MySQL setup

General workflow:

1. Place the project in your local web server directory if needed.
2. Create a MySQL database for the app.
3. Import `sql/schema.sql` in phpMyAdmin (**Import** tab) or via the MySQL client. It creates the **`techtrivia`** database, tables, and default categories. Until you do this, PHP pages will fail with **Unknown database 'techtrivia'**. If you already have an old database without categories, also run `sql/seed_categories.sql` once.
4. Update database credentials in `includes/config.php` (host, database name, user, password) if yours differ from the defaults.
5. Start Apache and MySQL.
6. Open the site through your local server, not directly by double-clicking the files.
7. **Admin access:** New accounts are `user` only. After you register, promote your account in MySQL, for example: `UPDATE users SET role = 'admin' WHERE username = 'yourname';` then log in to open `admindashboard.php`.

## Implementation Notes

- Keep file names lowercase and alphanumeric where possible to match the assignment rules.
- Reuse shared PHP includes for navbar, header, and footer to keep the design consistent.
- Use server-side validation even if JavaScript validation is also present.
- Store passwords using PHP password hashing functions.
- Escape output when rendering database content back to the page.

**Quizzes** (`easyquiz.php`, `mediumquiz.php`, `hardquiz.php`) require a logged-in user. Each run loads `QUIZ_QUESTION_COUNT` questions (default `5` in `includes/config.php`) from a chosen category and difficulty; the database must have at least that many rows for the pair or the page shows an error. **Leaderboard** (`leaderboard.php`) also requires a logged-in user and reads from `quiz_attempts`.

## Next Build Order

1. Create the shared layout files and global stylesheet
2. Build the static pages first (`index`, `about`, `categories`, `contact`)
3. Create the database schema and connection file
4. Build authentication (`login`, `register`, session handling)
5. Build quiz pages and result storage
6. Build leaderboard
7. Build admin dashboard and question management
8. Validate HTML/CSS and test on mobile and desktop sizes
