SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE book_authors;
TRUNCATE TABLE book_copies;
TRUNCATE TABLE books;
TRUNCATE TABLE authors;
TRUNCATE TABLE users;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO users (username, email, password_hash, role) VALUES
('librarian_demo', 'librarian@example.com', '$2y$10$2qiRz2n2f6wNl3es27wYQel/5F5Iy0ecM8f85.y1xwA5aZ43SlL9G', 'librarian'),
('member_demo', 'member@example.com', '$2y$10$2qiRz2n2f6wNl3es27wYQel/5F5Iy0ecM8f85.y1xwA5aZ43SlL9G', 'member');

INSERT INTO authors (name) VALUES
('Jane Austen');

INSERT INTO books (isbn, title, published_year) VALUES
('9780141439518', 'Pride and Prejudice', 1813);

INSERT INTO book_authors (book_id, author_id) VALUES
(1, 1);

INSERT INTO book_copies (book_id, accession_number, status) VALUES
(1, 'LIB-PP-0001', 'available');
