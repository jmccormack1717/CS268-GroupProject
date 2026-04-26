-- Tech Trivia - MySQL schema
-- Import after creating a database user, or adjust USE below.
-- Typical local setup: import via phpMyAdmin or `mysql -u root -p < sql/schema.sql`

CREATE DATABASE IF NOT EXISTS techtrivia
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE techtrivia;

-- ---------------------------------------------------------------------------
-- users
-- ---------------------------------------------------------------------------
CREATE TABLE users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(64) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- categories
-- ---------------------------------------------------------------------------
CREATE TABLE categories (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(128) NOT NULL,
  description VARCHAR(512) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_categories_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- questions
-- ---------------------------------------------------------------------------
CREATE TABLE questions (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  category_id INT UNSIGNED NOT NULL,
  difficulty ENUM('easy', 'medium', 'hard') NOT NULL,
  question_text TEXT NOT NULL,
  choice_a VARCHAR(512) NOT NULL,
  choice_b VARCHAR(512) NOT NULL,
  choice_c VARCHAR(512) NOT NULL,
  choice_d VARCHAR(512) NOT NULL,
  correct_choice CHAR(1) NOT NULL COMMENT 'a, b, c, or d (lowercase)',
  created_by INT UNSIGNED DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_questions_category_difficulty (category_id, difficulty),
  CONSTRAINT fk_questions_category
    FOREIGN KEY (category_id) REFERENCES categories (id)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_questions_created_by
    FOREIGN KEY (created_by) REFERENCES users (id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- quiz_attempts
-- ---------------------------------------------------------------------------
CREATE TABLE quiz_attempts (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED NOT NULL,
  difficulty ENUM('easy', 'medium', 'hard') NOT NULL,
  score INT UNSIGNED NOT NULL,
  total_questions INT UNSIGNED NOT NULL,
  completed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_quiz_attempts_user (user_id),
  KEY idx_quiz_attempts_completed (completed_at),
  KEY idx_quiz_attempts_leaderboard (difficulty, score),
  CONSTRAINT fk_quiz_attempts_user
    FOREIGN KEY (user_id) REFERENCES users (id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_quiz_attempts_category
    FOREIGN KEY (category_id) REFERENCES categories (id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: grant admin after registering once (replace username).
-- UPDATE users SET role = 'admin' WHERE username = 'yourname';

-- Default categories (INSERT IGNORE: safe if names already exist).
INSERT IGNORE INTO categories (name, description) VALUES
  ('General', 'General technology trivia'),
  ('Web', 'HTML, CSS, and the web'),
  ('Programming', 'Languages and code'),
  ('Databases', 'SQL and data storage');
