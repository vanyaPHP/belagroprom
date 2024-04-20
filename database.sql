CREATE DATABASE bank;
use bank;
CREATE TABLE priorities(priority_id INT NOT NULL AUTO_INCREMENT, priority_name VARCHAR(15), PRIMARY KEY (priority_id));
CREATE TABLE statuses(status_id INT NOT NULL AUTO_INCREMENT, status_name VARCHAR(15), PRIMARY KEY (status_id));
CREATE TABLE tasks(task_id INT NOT NULL AUTO_INCREMENT, task_description TEXT, status_id INT,
                   priority_id INT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                   updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (task_id),
                   FOREIGN KEY (status_id) REFERENCES statuses(status_id), FOREIGN KEY (priority_id) REFERENCES priorities (priority_id));
INSERT INTO priorities(priority_name) VALUES("критично");
INSERT INTO priorities(priority_name) VALUES("важно");
INSERT INTO priorities(priority_name) VALUES("нормально");
INSERT INTO priorities(priority_name) VALUES("не важно");
INSERT INTO statuses(status_name) VALUES("не исполнено");
INSERT INTO statuses(status_name) VALUES("исполнено");
INSERT INTO statuses(status_name) VALUES("отклонено");