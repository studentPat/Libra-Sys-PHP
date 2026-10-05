INSERT INTO roles (role_name) VALUES
    ('guest'),
    ('member'),
    ('librarian'),
    ('dba')
ON DUPLICATE KEY UPDATE role_name = VALUES(role_name);

INSERT INTO permissions (permission_name) VALUES
    ('catalog.read'),
    ('member.self.read'),
    ('borrowing.manage'),
    ('library.manage'),
    ('reports.read'),
    ('database.manage')
ON DUPLICATE KEY UPDATE permission_name = VALUES(permission_name);

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.role_id, p.permission_id
FROM roles r
JOIN permissions p
WHERE (r.role_name = 'guest' AND p.permission_name = 'catalog.read')
   OR (r.role_name = 'member' AND p.permission_name IN ('catalog.read', 'member.self.read'))
   OR (r.role_name = 'librarian' AND p.permission_name IN ('catalog.read', 'member.self.read', 'borrowing.manage', 'library.manage', 'reports.read'))
   OR (r.role_name = 'dba' AND p.permission_name = 'database.manage')
ON DUPLICATE KEY UPDATE role_id = VALUES(role_id);
