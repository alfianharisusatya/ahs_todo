CREATE DATABASE task_manager;

USE task_manager;

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    priority ENUM('Prioritas Rendah', 'Prioritas Sedang', 'Prioritas Tinggi') NOT NULL,
    assign_to VARCHAR(255) NOT NULL,
    status ENUM('todo', 'inprogress', 'done') NOT NULL DEFAULT 'todo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);