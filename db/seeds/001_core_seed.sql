SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM users;
DELETE FROM roles;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO roles (role_id, role_name) VALUES
    (1, 'guest'),
    (2, 'member'),
    (3, 'librarian'),
    (4, 'dba');

INSERT INTO users (role_id, username, password_hash, status) VALUES
    (3, 'librarian_demo', '$2y$10$co2v36Rsl4cezZhN9QTFY.nM31FTv6tvwOL6fG3N/rxhxQimMJRmq', 'active');
