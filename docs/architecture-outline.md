# Architecture Outline

This document turns the project proposal into a concrete implementation outline for a vanilla `HTML/CSS/JavaScript/PHP/MySQL` web app.

## 1. High-Level Structure

The application should be split into three simple layers:

### Presentation Layer

- Static pages written in HTML or PHP
- Shared navbar, header, and footer included across pages
- CSS for responsive layouts and visual consistency
- JavaScript for quiz interaction and form feedback

### Application Layer

- PHP request handling
- Authentication and session checks
- Quiz grading logic
- Admin authorization
- Form processing and validation

### Data Layer

- MySQL database
- Tables for users, categories, questions, and quiz attempts
- PHP database access through a shared connection file

## 2. Page Responsibilities

### Public Pages

- `index.html`: landing page, site intro, featured categories
- `about.html`: explains the client, problem, and project purpose
- `categories.html`: lets users browse quiz topics
- `contact.html`: contact or feedback information

### Quiz Pages

- `easy-quiz.php`: serves easy questions
- `medium-quiz.php`: serves medium questions
- `hard-quiz.php`: serves hard questions
- `leaderboard.php`: shows top scores and rankings

### Auth Pages

- `login.php`: signs users and admins in
- `register.php`: creates a normal user account
- `logout.php`: destroys the session and returns to home

### Admin Pages

- `admin-dashboard.php`: summary page for admin actions
- `manage-questions.php`: add, update, and delete trivia questions

## 3. Suggested Directory Map

```text
/
|-- index.html
|-- about.html
|-- categories.html
|-- contact.html
|-- login.php
|-- register.php
|-- logout.php
|-- easy-quiz.php
|-- medium-quiz.php
|-- hard-quiz.php
|-- leaderboard.php
|-- admin-dashboard.php
|-- manage-questions.php
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

## 4. Shared PHP Includes

To avoid repeating code across many pages, keep shared logic in small include files:

- `includes/db.php`: opens the MySQL connection
- `includes/config.php`: stores app constants and settings
- `includes/header.php`: common document head and site header
- `includes/footer.php`: footer content and shared script tags
- `includes/auth.php`: session checks and admin guard helpers
- `includes/functions.php`: reusable helper functions

This is especially useful because the project must contain many pages but still remain readable.

## 5. Data Model

## `users`

- Stores account information
- Supports both normal users and admins

Fields:

- `id`
- `username`
- `email`
- `password_hash`
- `role`
- `created_at`

## `categories`

- Stores quiz topics such as web, programming, hardware, or cybersecurity

Fields:

- `id`
- `name`
- `description`

## `questions`

- Stores all question content and answer choices

Fields:

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

## `quiz_attempts`

- Stores score history for users

Fields:

- `id`
- `user_id`
- `category_id`
- `difficulty`
- `score`
- `total_questions`
- `completed_at`

## 6. Main Request Flows

### Quiz Flow

1. User opens a quiz page.
2. PHP loads questions from the database by difficulty or category.
3. User selects answers in the browser.
4. Form is submitted to PHP.
5. PHP grades the attempt.
6. PHP stores the result in `quiz_attempts`.
7. User is redirected to a score or leaderboard view.

### Login Flow

1. User submits username/email and password.
2. PHP checks the `users` table.
3. If credentials are valid, PHP starts a session.
4. Role determines whether admin-only pages are accessible.

### Admin Question Management Flow

1. Admin logs in.
2. Admin opens `manage-questions.php`.
3. PHP checks the session role.
4. Admin submits add/edit/delete forms.
5. PHP validates the data.
6. PHP writes changes to the `questions` table.

## 7. Frontend Design Rules

The assignment rules strongly shape the architecture:

- Do not use React, Vue, Bootstrap, jQuery, or other libraries
- Use CSS Grid and Flexbox directly
- Use media queries for responsive behavior
- Keep image files in `images/`
- Keep stylesheets in `css/`
- Use descriptive `alt` text for every meaningful image
- Use at least two font families in the final design

Because libraries are not allowed, the best approach is a small, modular site with reusable includes and simple JavaScript files.

## 8. Security and Validation

Even for a class project, these basics should be included:

- Hash passwords with PHP password functions
- Validate and sanitize all form input on the server
- Escape output when displaying user-entered or database content
- Protect admin pages with session checks
- Use prepared statements for SQL queries

## 9. Build Strategy

### Phase 1

- Create the folder structure
- Add shared header, footer, and stylesheet
- Build the static pages

### Phase 2

- Create the database schema
- Add database connection code
- Build login and registration

### Phase 3

- Build quiz retrieval and answer submission
- Store scores in the database
- Display leaderboard results

### Phase 4

- Build admin dashboard and question management
- Test responsive layouts
- Validate HTML and CSS
- Clean up accessibility issues and naming conventions

## 10. Minimal Viable Version

If time becomes tight, the minimum strong submission should still include:

- 10+ pages
- shared navbar, header, and footer
- responsive CSS
- login system
- database-backed questions
- quiz scoring
- leaderboard
- admin question management

That version would satisfy the main project idea while staying inside the no-framework requirement.
