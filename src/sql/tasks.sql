CREATE DATABASE IF NOT EXISTS tasksdb;

GRANT ALL PRIVILEGES ON tasksdb.* TO 'testuser'@'%';
FLUSH PRIVILEGES;

USE tasksdb;

CREATE TABLE IF NOT EXISTS tasks (
  id int NOT NULL AUTO_INCREMENT,
  body char(64) NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO tasks (body) VALUES
('Finish the project documentation'),
('Review the codebase for bugs'),
('Prepare for the client meeting'),
('Deploy the latest build to production');
