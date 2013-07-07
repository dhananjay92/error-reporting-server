USE phpmyadmin-error-report;

DROP TABLE IF EXISTS reports;
CREATE TABLE reports (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    error_message VARCHAR(150),
    error_name VARCHAR(50),
    pma_version VARCHAR(50),
    browser_name VARCHAR(30),
    browser_version VARCHAR(30),
    user_os VARCHAR(30),
    server_software VARCHAR(100),
    status VARCHAR(20),
    steps TEXT,
    stacktrace TEXT,
    full_report TEXT,
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL
);

DROP TABLE IF EXISTS developers;
CREATE TABLE developers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    github_id INT UNSIGNED,
    name VARCHAR(50),
    email VARCHAR(70),
    gravatar_id VARCHAR(100),
    access_token VARCHAR(100),
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL
);
