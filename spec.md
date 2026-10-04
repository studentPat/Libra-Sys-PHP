## Problem Statement

The project currently has a course-aligned design paper, but not an implementation-ready specification. The paper defines the library domain, MySQL schema expectations, security demonstrations, and workflows, yet developers still need a single actionable contract covering user behavior, business rules, PHP page/form interactions, testing seams, and completion evidence.

The system must be implementable as a course-level library management system for one library branch using PHP server-rendered pages, PDO, MySQL, and a browser-based UI. It must support ordinary library operations while demonstrating transaction management and recovery, database encryption, database authorization and privileges, and query optimization.

## Solution

Build LibraSys as a PHP 8.3+ server-rendered web application using PHP sessions, request guards, form handlers, PDO prepared statements, and MySQL 8.x with InnoDB. PHP renders HTML and CSS pages directly; JavaScript is optional and limited to progressive enhancement.

The system will provide guest catalog browsing, member account and borrowing workflows, librarian management workflows, DBA-facing administration where appropriate, audit logging, fines and payments, reports, and executable course demonstrations. The primary testing seam is complete browser workflows through PHP-rendered pages and form handlers against a MySQL test database, supplemented by focused database transaction, locking, encryption, privilege, and query-plan tests.

## User Stories

1. As a guest, I want to search the public catalog, so that I can find books without signing in.
2. As a guest, I want to view book details and availability, so that I can decide whether to visit the library.
3. As a guest, I want unauthorized pages to redirect or display an access-denied response, so that protected information remains private.
4. As a member, I want to sign in with my credentials, so that I can use member-only library services.
5. As a member, I want to sign out, so that my session cannot be reused by another person.
6. As a member, I want my session to expire safely, so that abandoned sessions do not remain active indefinitely.
7. As a member, I want to view and update my own profile, so that my contact information remains current.
8. As a member, I want sensitive contact or address data protected, so that private information is not stored or displayed as plaintext unnecessarily.
9. As a member, I want to view my active and historical borrowings, so that I can track library items.
10. As a member, I want to borrow an available book copy, so that I can take it home.
11. As a member, I want the system to enforce borrowing limits and account status rules, so that borrowing remains fair and controlled.
12. As a member, I want the system to prevent duplicate active loans for the same copy, so that inventory remains accurate.
13. As a member, I want to return a borrowed copy, so that the item becomes available for the next borrower.
14. As a member, I want to provide the return condition, so that lost or damaged copies can be handled correctly.
15. As a member, I want overdue, lost, or damaged fines calculated according to documented rules, so that charges are transparent.
16. As a member, I want to reserve a book, so that I can join its reservation queue when no suitable copy is available.
17. As a member, I want duplicate active reservations for the same book prevented, so that my queue position is not duplicated.
18. As a member, I want to cancel an active reservation, so that I can leave a queue I no longer need.
19. As a member, I want to see my reservation status and expiration, so that I know whether a reservation can be fulfilled.
20. As a member, I want to view my fines and payment history, so that I understand my account balance.
21. As a member, I want to pay an eligible fine, so that my account can return to good standing.
22. As a member, I want overpayments rejected, so that payment records remain correct.
23. As a member, I want to be prevented from viewing another member's profile, loans, fines, or payments, so that ownership rules are enforced.
24. As a librarian, I want to create and update books, so that the catalog remains accurate.
25. As a librarian, I want to manage multiple authors for a book, so that catalog relationships are represented correctly.
26. As a librarian, I want to add and update physical copies, so that the inventory reflects accession numbers and conditions.
27. As a librarian, I want to manage member records, so that account status and library operations can be maintained.
28. As a librarian, I want to process borrowing and return workflows, so that physical circulation is recorded consistently.
29. As a librarian, I want to manage reservations, so that the queue is fulfilled in order.
30. As a librarian, I want to review, record, and settle fines and payments, so that financial records are auditable.
31. As a librarian, I want to view operational reports, so that I can monitor borrowing, overdue items, reservations, and unpaid fines.
32. As a librarian, I want unauthorized DBA operations rejected, so that database administration remains separated from library operations.
33. As a DBA, I want to manage MySQL roles, privileges, views, and security configuration, so that database access follows least privilege.
34. As a DBA, I want to verify denied access for restricted identities, so that database boundaries are demonstrated.
35. As a DBA, I want audit records retained for important business and security events, so that actions can be investigated.
36. As a project evaluator, I want to see atomic borrow, return, and payment workflows, so that transaction behavior is demonstrable.
37. As a project evaluator, I want to see rollback and failure evidence, so that atomicity and recovery behavior are verifiable.
38. As a project evaluator, I want to see concurrent borrowing and locking results, so that race-condition handling is demonstrated.
39. As a project evaluator, I want to see password hashing, protected sensitive fields, and key-boundary evidence, so that encryption-related claims are supported.
40. As a project evaluator, I want to see MySQL grants, revokes, views, and denied-access tests, so that database authorization is demonstrable.
41. As a project evaluator, I want to see before-and-after query plans and measurements, so that optimization work is supported by evidence.
42. As a project maintainer, I want validation errors, conflicts, failed authorization, transaction errors, and payment status displayed without stack traces, so that the application is understandable and safe to operate.

## Implementation Decisions

- Use PHP 8.3+ with server-rendered pages rather than a separate API layer or single-page application.
- Use PHP sessions for authentication state and request guards plus role checks for authorization.
- Use PDO with prepared statements for all application database access.
- Use MySQL 8.x with InnoDB for all business tables.
- Use a restricted `libra_web` MySQL identity for the PHP application. Do not use a separate database account per member.
- Keep reporting, migration, and audit identities separate where the course demonstration requires them.
- Use `password_hash()` and `password_verify()` with Argon2id or bcrypt. Store password hashes only; never store plaintext or reversibly encrypted passwords.
- Use environment-provided secrets or a secret store for encryption keys. Never commit secrets or store keys beside ciphertext in the database.
- Use TLS for PHP-to-MySQL connections in deployed environments.
- Represent roles and application permissions with `roles`, `permissions`, and `role_permissions`, while using MySQL grants for the database boundary.
- Use the relational entities `roles`, `permissions`, `role_permissions`, `users`, `members`, `books`, `authors`, `book_authors`, `book_copies`, `reservations`, `borrowings`, `fines`, `payments`, and `transaction_logs`.
- Enforce unique usernames, emails, ISBNs, and accession numbers.
- Enforce documented foreign-key delete behavior. Do not cascade-delete audit, payment, or fine history.
- Use fixed-precision non-negative monetary values such as `DECIMAL(10,2)`.
- Restrict status fields with documented lookup values or MySQL `CHECK` constraints.
- Enforce one active borrowing per copy, no duplicate active reservation for a member/book pair, and no total payments exceeding a fine.
- Use `borrowings` consistently as the borrowing table name.
- Implement borrow, return, and payment workflows with explicit transactions, row locks where needed, validation before mutation, audit insertion, commit on success, and rollback on failure.
- Make bounded transaction retries idempotent so retries cannot duplicate loans, fines, payments, or audit events.
- For an unavailable copy, display a conflict rather than silently selecting another copy.
- Render catalog, member, librarian, report, and administration pages from PHP. Use JavaScript only for progressive enhancement.
- Use pagination for catalog and report pages and avoid unbounded result sets or loading entire tables.
- Add workload indexes for catalog search, copy availability, reservations, member loans, overdue loans, fines, payments, and audit history.
- Capture query-plan evidence with `EXPLAIN` or `EXPLAIN ANALYZE`, representative data volume, repeated measurements, and documented limitations.
- Keep multiple library branches, digital books, interlibrary loans, acquisitions, automated notifications, online payment gateways, predictive analytics, enterprise key management, and multi-region disaster recovery out of scope.

## Testing Decisions

- Test externally visible behavior through complete browser workflows: guest catalog access, login/logout, member profile and ownership rules, borrowing, returns, reservations, fines, payments, librarian operations, and reports.
- Use a MySQL test database with representative seed data and deterministic fixtures.
- Test that protected pages and form handlers enforce session state, role checks, and resource ownership.
- Test validation failures for invalid credentials, inactive members, unavailable copies, invalid conditions, duplicate reservations, invalid statuses, non-positive payments, and overpayments.
- Test borrow, return, and payment atomicity by forcing a failure after an earlier mutation and verifying rollback.
- Test concurrent attempts to borrow one copy and verify row-lock/isolation behavior produces one valid result without duplicate active loans.
- Test bounded deadlock retry behavior and verify idempotency.
- Test password hashes with verification behavior rather than inspecting implementation details.
- Test sensitive-field protection by verifying ciphertext storage and denied decryption without the permitted key or application path.
- Test MySQL privilege boundaries with positive and negative access cases for application, reporting, migration, audit, and DBA identities.
- Test query optimization with representative data, plans before and after tuning, rows examined, execution time, and repeated-run methodology.
- Prefer browser-level tests for user-visible behavior and database integration tests for transaction, constraint, privilege, and query-plan behavior.
- Existing repository test prior art is not yet available because the repository currently contains the project paper and configuration rather than application code. Establish the first test harness at the highest workflow seam once implementation begins.

## Out of Scope

- Multiple library branches
- Digital books or online reading
- Interlibrary loans and acquisitions
- Automated SMS, email, and push notifications
- Online payment gateways and accounting integrations
- Predictive analytics
- Enterprise key-management infrastructure
- Multi-region disaster recovery
- A separate JSON API or single-page frontend
- Per-member MySQL accounts
- Production-scale RPO/RTO guarantees

## Further Notes

- This specification is derived from `LibraSys-Course-Aligned-Project-Paper.md` and turns its course requirements into implementation behavior and acceptance-oriented user stories.
- The system is intentionally scoped for a course-level project, not a complete enterprise library platform.
- Every database topic must have an executable demonstration, an expected result, and recorded evidence.
- The implementation should preserve clear separation between application roles and MySQL identities.
- The next implementation decomposition should prioritize schema/DDL, authentication and sessions, catalog and copy management, circulation workflows, reservations, fines/payments, reports, and course evidence scripts.
