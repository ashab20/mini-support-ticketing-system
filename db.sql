CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name TEXT,
    email VARCHAR(30) UNIQUE INDEX,
    password_hash VARCHAR(255),
    role ENUM('Admin', 'User') DEFAULT 'User'
);

CREATE TABLE departments (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(30)
);

CREATE TABLE tickets (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100),
    description TEXT,
    status ENUM('open', 'closed', 'in_progress') DEFAULT 'open',
    user_id INTEGER,
    department_id INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INTEGER,
    updated_by INTEGER,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (department_id) REFERENCES departments(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

CREATE TABLE ticket_notes (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    ticket_id INTEGER,
    user_id INTEGER,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    FOREIGN KEY (ticket_id) REFERENCES tickets(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
