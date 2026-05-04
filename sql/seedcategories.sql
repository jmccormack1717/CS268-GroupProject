-- Run once if your database was created before default categories were added.
USE techtrivia;

INSERT IGNORE INTO categories (name, description) VALUES
  ('General', 'General technology trivia'),
  ('Web', 'HTML, CSS, and the web'),
  ('Programming', 'Languages and code'),
  ('Databases', 'SQL and data storage');
