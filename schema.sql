-- HopeBridge Charity Platform - database
-- Run once:  mysql -u root < schema.sql

DROP DATABASE IF EXISTS hopebridge;
CREATE DATABASE hopebridge;
USE hopebridge;

-- All three user types live in one table and are told apart by "role"
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255),                          -- empty when the user signed up with Google or Facebook
    role VARCHAR(20) NOT NULL,                      -- donor / beneficiary / admin
    provider VARCHAR(20) NOT NULL DEFAULT 'local',  -- local / google / facebook
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- The extra details a beneficiary gives so the admin can check they are eligible
CREATE TABLE beneficiaries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    phone VARCHAR(30),
    city VARCHAR(80),
    household_size INT,
    monthly_income DECIMAL(8,2),
    situation TEXT,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',  -- pending / approved / rejected
    admin_note TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- The charitable programs donors give to and beneficiaries apply for
CREATE TABLE programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    category VARCHAR(40) NOT NULL DEFAULT 'General',   -- Education / Health / Food / Relief
    image VARCHAR(120),                                -- a file name inside the images folder, or empty
    eligibility TEXT,
    goal_amount DECIMAL(10,2) NOT NULL,
    active TINYINT NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Money given by a donor to one program
CREATE TABLE donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT NOT NULL,
    program_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES users(id),
    FOREIGN KEY (program_id) REFERENCES programs(id)
);

-- A beneficiary asking for help from one program
CREATE TABLE requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    beneficiary_id INT NOT NULL,
    program_id INT NOT NULL,
    note TEXT,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',  -- pending / approved / rejected
    admin_note TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (beneficiary_id) REFERENCES beneficiaries(id),
    FOREIGN KEY (program_id) REFERENCES programs(id)
);

-- Progress reports the admin writes so donors can see what their money did
CREATE TABLE updates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    body TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES programs(id)
);

-- Short automatic messages, written by the system when something changes
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    body VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- "Remember me" - one row per browser the user chose to stay signed in on.
-- Only the hash of the token is stored, so a stolen copy of this table is useless.
CREATE TABLE remember_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- "Forgot password" - one row per reset that was asked for.
-- Again only the hash is kept, and each row can be used once.
CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    used TINYINT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Private messages between the admin and any other user
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    body TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id)
);


-- ---------- sample data (all names and emails are invented) ----------

-- admin@hopebridge.jo  /  admin123
INSERT INTO users (name, email, password, role) VALUES
('Site Admin', 'admin@hopebridge.jo', '$2y$12$U9E8nHyRvQvOY4B7YxtlZ.GCm9AyxRU6bsXSgynug9EFMlxWjoFBe', 'admin');

-- donor@example.com  /  pass123
INSERT INTO users (name, email, password, role) VALUES
('Nadia Rashed', 'donor@example.com', '$2y$12$bE3q5iEbIhryA0i4toMx1eJMfqKKWmXCn3K3vZesudv1NnrqzPmiK', 'donor');

-- family@example.com  /  pass123   (an already approved beneficiary)
INSERT INTO users (name, email, password, role) VALUES
('Khaled Mansour', 'family@example.com', '$2y$12$bE3q5iEbIhryA0i4toMx1eJMfqKKWmXCn3K3vZesudv1NnrqzPmiK', 'beneficiary');

INSERT INTO beneficiaries (user_id, phone, city, household_size, monthly_income, situation, status) VALUES
(3, '0790000000', 'Zarqa', 6, 180.00, 'I lost my job last year and I have four children at school.', 'approved');

INSERT INTO programs (title, description, category, image, eligibility, goal_amount) VALUES
('Winter Blankets',
 'Warm blankets and heaters for families during the winter months.',
 'Relief', 'blankets.jpg',
 'Families with a monthly income under 300 JOD living in an unheated home.',
 5000.00),
('School Supplies',
 'Bags, books and stationery so children can start the school year ready.',
 'Education', 'classroom.jpg',
 'Families with at least one child in school and a monthly income under 400 JOD.',
 3000.00),
('Emergency Food Parcels',
 'A monthly box of basic food for families who cannot cover their groceries.',
 'Food', 'food-parcels.jpg',
 'Any family with a monthly income under 250 JOD.',
 8000.00),
('Medical Aid',
 'Help with the cost of medicine and hospital visits.',
 'Health', 'clinic.jpg',
 'Families with a medical cost they cannot pay and an income under 400 JOD.',
 6000.00);

INSERT INTO updates (program_id, title, body) VALUES
(1, 'First 120 blankets delivered',
    'This week we delivered 120 blankets and 30 heaters to families in Zarqa and Mafraq. Thank you to everyone who gave.'),
(3, 'Food parcels reach 85 families',
    'Eighty-five families received their monthly parcel this month. Each parcel covers rice, oil, sugar, lentils and tinned food for four weeks.');

-- A second donor and a second beneficiary, so the reports have something in them.
-- giver@example.com  /  pass123
INSERT INTO users (name, email, password, role) VALUES
('Omar Haddad', 'giver@example.com', '$2y$12$bE3q5iEbIhryA0i4toMx1eJMfqKKWmXCn3K3vZesudv1NnrqzPmiK', 'donor');

-- waiting@example.com  /  pass123   (a beneficiary the admin has not checked yet)
INSERT INTO users (name, email, password, role) VALUES
('Rania Odeh', 'waiting@example.com', '$2y$12$bE3q5iEbIhryA0i4toMx1eJMfqKKWmXCn3K3vZesudv1NnrqzPmiK', 'beneficiary');

INSERT INTO beneficiaries (user_id, phone, city, household_size, monthly_income, situation, status) VALUES
(5, '0791111111', 'Irbid', 4, 210.00, 'My husband is ill and I am the only one working.', 'pending');

-- Donations spread over three months so the monthly report has rows
INSERT INTO donations (donor_id, program_id, amount, created_at) VALUES
(2, 1, 250.00, '2026-06-12 10:15:00'),
(2, 3, 100.00, '2026-07-03 18:40:00'),
(2, 1, 150.00, '2026-07-21 09:05:00'),
(4, 2, 500.00, '2026-07-28 14:22:00'),
(4, 3, 300.00, '2026-08-09 11:00:00'),
(2, 4, 200.00, '2026-08-18 16:35:00'),
(4, 1, 400.00, '2026-08-25 08:50:00');

-- One application already decided, one still waiting for the admin
INSERT INTO requests (beneficiary_id, program_id, note, status, admin_note, created_at) VALUES
(1, 1, 'We have no heating and four children at home.', 'approved', 'Approved - blankets delivered in June.', '2026-06-05 12:00:00'),
(1, 3, 'Groceries have been hard to cover this month.', 'pending', NULL, '2026-08-20 19:10:00');

INSERT INTO notifications (user_id, body, created_at) VALUES
(3, 'Your account has been approved. You can now apply for help.', '2026-06-01 09:00:00'),
(3, 'Your application for Winter Blankets was accepted.', '2026-06-06 10:30:00');
