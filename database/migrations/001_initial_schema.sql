CREATE TABLE IF NOT EXISTS roles (
    role_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS permissions (
    permission_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    permission_name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS role_permissions (
    role_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    CONSTRAINT fk_role_permissions_role FOREIGN KEY (role_id) REFERENCES roles (role_id) ON DELETE CASCADE,
    CONSTRAINT fk_role_permissions_permission FOREIGN KEY (permission_id) REFERENCES permissions (permission_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS users (
    user_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id BIGINT UNSIGNED NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_users_status CHECK (status IN ('active', 'inactive', 'locked')),
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles (role_id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS members (
    member_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    contact_info VARCHAR(255) NULL,
    address_ciphertext TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_members_user FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS books (
    book_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    isbn VARCHAR(20) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    publisher VARCHAR(255) NULL,
    publication_year SMALLINT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_books_title (title)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS authors (
    author_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS book_authors (
    book_id BIGINT UNSIGNED NOT NULL,
    author_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (book_id, author_id),
    CONSTRAINT fk_book_authors_book FOREIGN KEY (book_id) REFERENCES books (book_id) ON DELETE CASCADE,
    CONSTRAINT fk_book_authors_author FOREIGN KEY (author_id) REFERENCES authors (author_id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS book_copies (
    copy_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id BIGINT UNSIGNED NOT NULL,
    accession_number VARCHAR(100) NOT NULL UNIQUE,
    status VARCHAR(20) NOT NULL DEFAULT 'available',
    `condition` VARCHAR(20) NOT NULL DEFAULT 'good',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_book_copies_status CHECK (status IN ('available', 'borrowed', 'reserved', 'lost', 'damaged')),
    CONSTRAINT chk_book_copies_condition CHECK (`condition` IN ('new', 'good', 'worn', 'damaged')),
    CONSTRAINT fk_book_copies_book FOREIGN KEY (book_id) REFERENCES books (book_id) ON DELETE RESTRICT,
    INDEX idx_book_copies_book_status (book_id, status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reservations (
    reservation_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    member_id BIGINT UNSIGNED NOT NULL,
    book_id BIGINT UNSIGNED NOT NULL,
    reserved_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    fulfilled_at TIMESTAMP NULL,
    cancelled_at TIMESTAMP NULL,
    CONSTRAINT chk_reservations_status CHECK (status IN ('active', 'fulfilled', 'cancelled', 'expired')),
    CONSTRAINT fk_reservations_member FOREIGN KEY (member_id) REFERENCES members (member_id) ON DELETE RESTRICT,
    CONSTRAINT fk_reservations_book FOREIGN KEY (book_id) REFERENCES books (book_id) ON DELETE RESTRICT,
    INDEX idx_reservations_queue (book_id, status, reserved_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS borrowings (
    borrowing_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    copy_id BIGINT UNSIGNED NOT NULL,
    member_id BIGINT UNSIGNED NOT NULL,
    borrow_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    due_date DATE NOT NULL,
    return_date TIMESTAMP NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    return_condition VARCHAR(20) NULL,
    CONSTRAINT chk_borrowings_status CHECK (status IN ('active', 'returned', 'lost', 'damaged')),
    CONSTRAINT fk_borrowings_copy FOREIGN KEY (copy_id) REFERENCES book_copies (copy_id) ON DELETE RESTRICT,
    CONSTRAINT fk_borrowings_member FOREIGN KEY (member_id) REFERENCES members (member_id) ON DELETE RESTRICT,
    INDEX idx_borrowings_member_status (member_id, status),
    INDEX idx_borrowings_copy_status (copy_id, status),
    INDEX idx_borrowings_due_status (due_date, status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS fines (
    fine_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    borrowing_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    reason VARCHAR(255) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'unpaid',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    settled_at TIMESTAMP NULL,
    waived_at TIMESTAMP NULL,
    waived_by BIGINT UNSIGNED NULL,
    CONSTRAINT chk_fines_amount CHECK (amount >= 0),
    CONSTRAINT chk_fines_status CHECK (status IN ('unpaid', 'partially_paid', 'paid', 'waived')),
    CONSTRAINT fk_fines_borrowing FOREIGN KEY (borrowing_id) REFERENCES borrowings (borrowing_id) ON DELETE RESTRICT,
    CONSTRAINT fk_fines_waived_by FOREIGN KEY (waived_by) REFERENCES users (user_id) ON DELETE RESTRICT,
    INDEX idx_fines_status_created (status, created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS payments (
    payment_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fine_id BIGINT UNSIGNED NOT NULL,
    amount_paid DECIMAL(10, 2) NOT NULL,
    payment_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    payment_method VARCHAR(30) NOT NULL,
    receipt_reference VARCHAR(100) NOT NULL UNIQUE,
    recorded_by BIGINT UNSIGNED NOT NULL,
    CONSTRAINT chk_payments_amount CHECK (amount_paid > 0),
    CONSTRAINT fk_payments_fine FOREIGN KEY (fine_id) REFERENCES fines (fine_id) ON DELETE RESTRICT,
    CONSTRAINT fk_payments_recorded_by FOREIGN KEY (recorded_by) REFERENCES users (user_id) ON DELETE RESTRICT,
    INDEX idx_payments_fine_date (fine_id, payment_date)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS transaction_logs (
    transaction_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(100) NOT NULL,
    entity_id BIGINT UNSIGNED NULL,
    event_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    success BOOLEAN NOT NULL,
    details JSON NULL,
    CONSTRAINT fk_transaction_logs_user FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE SET NULL,
    INDEX idx_transaction_logs_user_time (user_id, event_time)
) ENGINE=InnoDB;
