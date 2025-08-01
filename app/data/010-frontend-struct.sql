-- Table to store company data
DROP TABLE IF EXISTS coproacademy;
CREATE TABLE coproacademy (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(100) NOT NULL UNIQUE,
  label TEXT NOT NULL
) ENGINE = InnoDB;

-- Table to store FAQ entries
DROP TABLE IF EXISTS faq;
CREATE TABLE faq (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(100) NOT NULL UNIQUE,
  label VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAUlT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  revoked_at TIMESTAMP NULL DEFAULT NULL COMMENT 'Timestamp when the FAQ was revoked or deactivated'
) ENGINE = InnoDB;

-- Table to store service information
DROP TABLE IF EXISTS service;
CREATE TABLE service (
  id INT AUTO_INCREMENT PRIMARY KEY,
  label VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  link VARCHAR(255) NOT NULL,
  link_text VARCHAR(100) NOT NULL,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAUlT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  revoked_at TIMESTAMP NULL DEFAULT NULL COMMENT 'Timestamp when the service was revoked or deactivated'
) ENGINE = InnoDB;


DROP TABLE IF EXISTS benefit;
CREATE TABLE benefit (
  id INT AUTO_INCREMENT PRIMARY KEY,
  icon VARCHAR(50) NOT NULL COMMENT 'Emoji or icon class for the benefit',
  title VARCHAR(255) NOT NULL COMMENT 'Benefit title/heading',
  description TEXT NOT NULL COMMENT 'Benefit description text',
  sort_order INT NOT NULL DEFAULT 0 COMMENT 'Display order of benefits',
  is_active TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Enable/disable benefit display',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;

