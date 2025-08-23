-- ===============================================
-- The script will drop all tables in the database
-- It is useful for resetting the structure during 
-- development.
--
-- WARNING : ALL DATA AND TABLES WILL BE DESTROYED
--
-- Use with caution, never production environments
-- ===============================================
-- 0. DROP TABLES
-- ===============================================
-- 1. TABLE STRUCTURE
-- ===============================================
-- 2. TIMESTAMP COLUMNS
-- ===============================================
-- 3. DATA CONSTRAINTS
-- ===============================================
-- 4. INDEXES
-- ===============================================


SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS
  `article`,
  `booking`,
  `contact_request`,
  `event`,
  `newsletter_subscription`,
  `operator_session`,
  `operator`,
  `training_program`,
  `training`,
  `trainer`,
  `taxonomy`
;

SET FOREIGN_KEY_CHECKS = 1;


-- ===============================================
-- 1. TABLE STRUCTURE
-- ===============================================
DROP TABLE IF EXISTS operator;
CREATE TABLE operator (
    id              INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    label           VARCHAR(255) NOT NULL,
    username        VARCHAR(255) NOT NULL,
    password_hash   VARCHAR(255) NOT NULL,
    status          TINYINT NULL DEFAULT NULL, -- null inactive, 1: active, 0: suspended
    -- status          ENUM('active','inactive','suspended') NOT NULL DEFAULT 'active',
    last_login_at   DATETIME    NULL
) ENGINE=InnoDB;


DROP TABLE IF EXISTS taxonomy;
CREATE TABLE taxonomy (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(55) NOT NULL,
    label VARCHAR(50) NOT NULL,
    parent_id INT UNSIGNED NULL,
    sort_order SMALLINT UNSIGNED NOT NULL
) ENGINE=InnoDB;


DROP TABLE IF EXISTS trainer;
CREATE TABLE trainer (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(105) NOT NULL,
    label VARCHAR(255) NOT NULL,
    title VARCHAR(255) NULL,
    bio TEXT NULL,
    avatar VARCHAR(255) NULL,
    email VARCHAR(100) NULL,
    hire_date DATE NULL
) ENGINE=InnoDB;

DROP TABLE IF EXISTS training;
CREATE TABLE training (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(205) NOT NULL,
    label VARCHAR(200) NOT NULL,
    subtitle VARCHAR(500) NULL,
    content TEXT NOT NULL,
    level_id INT UNSIGNED NOT NULL,
    duration_days SMALLINT UNSIGNED NOT NULL,
    duration_hours SMALLINT UNSIGNED NOT NULL,
    price_ht DECIMAL(8, 2) NOT NULL,
    start_date DATE NOT NULL,
    places_max SMALLINT UNSIGNED NULL,
    objectives TEXT NULL,
    prerequisites TEXT NULL,
    pause TEXT NULL,
    parking TEXT NULL,
    avatar VARCHAR(255) NULL,
    trainer_id INT UNSIGNED NULL,
    certification BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE = InnoDB;


DROP TABLE IF EXISTS training_program;
CREATE TABLE training_program (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(155) NOT NULL,
    label VARCHAR(150) NOT NULL,
    content TEXT NULL,
    training_id INT UNSIGNED NOT NULL,
    day_number SMALLINT UNSIGNED NOT NULL,
    time_start TIME NOT NULL,
    time_end TIME NOT NULL,
    objectives TEXT NULL
) ENGINE = InnoDB;


DROP TABLE IF EXISTS article;
CREATE TABLE article (
    id				    INT UNSIGNED		NOT NULL AUTO_INCREMENT PRIMARY KEY,
    slug			    VARCHAR(205)		NOT NULL,
    label			    VARCHAR(200)		NOT NULL,
    summary			    TEXT			    NULL,
    content			    LONGTEXT		    NOT NULL,
    category_id			INT UNSIGNED		NOT NULL,
    reading_time		SMALLINT UNSIGNED	NULL,
    featured		    BOOLEAN			    NOT NULL DEFAULT FALSE,
    section1_label		VARCHAR(200)		NULL,
    section1_content	LONGTEXT		    NULL,
    section2_label		VARCHAR(200)		NULL,
    section2_content	LONGTEXT		    NULL,
    section3_label		VARCHAR(200)		NULL,
    section3_content	LONGTEXT		    NULL,
    section4_label		VARCHAR(200)		NULL,
    section4_content	LONGTEXT		    NULL,
    section5_label		VARCHAR(200)		NULL,
    section5_content	LONGTEXT		    NULL
) ENGINE=InnoDB;


DROP TABLE IF EXISTS event;
CREATE TABLE event (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(205) NOT NULL,
    label VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    event_date DATETIME NOT NULL,
    duration_minutes SMALLINT UNSIGNED NOT NULL,
    price_ht DECIMAL(8,2) NULL,
    places_max SMALLINT UNSIGNED NULL,
    avatar VARCHAR(255) NULL,
    speaker VARCHAR(100) NULL,
    location VARCHAR(200) NULL,
    online BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB;


DROP TABLE IF EXISTS contact_request;
CREATE TABLE contact_request (
    id              INT UNSIGNED     NOT NULL AUTO_INCREMENT PRIMARY KEY,
    label           VARCHAR(200) NOT NULL,
    content         TEXT             NOT NULL,
    email           VARCHAR(255)     NOT NULL,
    phone           VARCHAR(33)      NULL,
    company         VARCHAR(100)     NULL,
    subject_id      INT UNSIGNED     NOT NULL,
    consented_at    TIMESTAMP        NULL DEFAULT NULL,
    status_id       INT UNSIGNED     NULL DEFAULT NULL,
    ip_address      VARCHAR(45)      NULL DEFAULT NULL,
    user_agent      TEXT             NULL DEFAULT NULL,
    session_id      VARCHAR(128)     NULL DEFAULT NULL
) ENGINE=InnoDB;


DROP TABLE IF EXISTS booking;
CREATE TABLE booking (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT PRIMARY KEY,
    event_id        INT UNSIGNED    NULL,
    training_id     INT UNSIGNED    NULL,
    email           VARCHAR(360)    NOT NULL,
    status_id       INT UNSIGNED    NOT NULL
) ENGINE=InnoDB;


DROP TABLE IF EXISTS newsletter_subscription;
CREATE TABLE newsletter_subscription (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(360) NOT NULL
) ENGINE=InnoDB;


DROP TABLE IF EXISTS operator_session;
CREATE TABLE operator_session (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    operator_id INT UNSIGNED NOT NULL,
    ip_address VARBINARY(16) NOT NULL,
    user_agent TEXT NULL,
    last_activity TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ===============================================
-- 2. TIMESTAMP COLUMNS
-- ===============================================
ALTER TABLE operator
    ADD COLUMN created_at TIMESTAMP NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN enabled_at TIMESTAMP NULL        DEFAULT NULL,
    ADD COLUMN revoked_at TIMESTAMP NULL        DEFAULT NULL;

ALTER TABLE taxonomy
    ADD COLUMN created_at TIMESTAMP NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN enabled_at TIMESTAMP NULL        DEFAULT NULL,
    ADD COLUMN revoked_at TIMESTAMP NULL        DEFAULT NULL;

ALTER TABLE trainer
    ADD COLUMN created_at TIMESTAMP NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN enabled_at TIMESTAMP NULL        DEFAULT NULL,
    ADD COLUMN revoked_at TIMESTAMP NULL        DEFAULT NULL;

ALTER TABLE article
    ADD COLUMN created_at TIMESTAMP NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN enabled_at TIMESTAMP NULL        DEFAULT NULL,
    ADD COLUMN revoked_at TIMESTAMP NULL        DEFAULT NULL;
ALTER TABLE event
    ADD COLUMN created_at TIMESTAMP NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN enabled_at TIMESTAMP NULL        DEFAULT NULL,
    ADD COLUMN revoked_at TIMESTAMP NULL        DEFAULT NULL;

ALTER TABLE training
    ADD COLUMN created_at TIMESTAMP NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN enabled_at TIMESTAMP NULL        DEFAULT NULL,
    ADD COLUMN revoked_at TIMESTAMP NULL        DEFAULT NULL;

ALTER TABLE training_program
    ADD COLUMN created_at TIMESTAMP NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN enabled_at TIMESTAMP NULL        DEFAULT NULL,
    ADD COLUMN revoked_at TIMESTAMP NULL        DEFAULT NULL;
    
ALTER TABLE contact_request
    ADD COLUMN created_at TIMESTAMP NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN revoked_at TIMESTAMP NULL        DEFAULT NULL;

ALTER TABLE booking
    ADD COLUMN created_at TIMESTAMP NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN revoked_at TIMESTAMP NULL        DEFAULT NULL;

ALTER TABLE newsletter_subscription
    ADD COLUMN created_at TIMESTAMP NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN revoked_at TIMESTAMP NULL        DEFAULT NULL;

ALTER TABLE operator_session
    ADD COLUMN created_at TIMESTAMP NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN revoked_at TIMESTAMP NULL        DEFAULT NULL;

-- ===============================================
-- 3. DATA CONSTRAINTS
-- ===============================================

ALTER TABLE article
    ADD CONSTRAINT article_uk_slug_unique
        UNIQUE (slug);

ALTER TABLE event
    ADD CONSTRAINT event_uk_slug_unique
        UNIQUE (slug),
    ADD CONSTRAINT event_ck_price_not_negative
        CHECK (price_ht IS NULL OR price_ht >= 0);

ALTER TABLE operator
    ADD CONSTRAINT operator_uk_username
        UNIQUE (username);

ALTER TABLE taxonomy
    ADD CONSTRAINT taxonomy_uk_slug_per_parent
        UNIQUE (parent_id, slug);

ALTER TABLE trainer
    ADD CONSTRAINT trainer_uk_email_unique
        UNIQUE (email);

ALTER TABLE training
    ADD CONSTRAINT training_uk_slug_unique
        UNIQUE (slug),
    ADD CONSTRAINT training_ck_price_not_negative
        CHECK (price_ht >= 0);

ALTER TABLE training_program
    ADD CONSTRAINT training_program_uk_time_slot_unique
        UNIQUE (training_id, day_number, time_start),
    ADD CONSTRAINT training_program_ck_end_after_start
        CHECK (time_end > time_start),
    ADD CONSTRAINT training_program_ck_day_positive
        CHECK (day_number > 0);

ALTER TABLE newsletter_subscription
    ADD CONSTRAINT newsletter_subscription_uk_email_unique
        UNIQUE (email);

ALTER TABLE booking
  ADD CONSTRAINT booking_fk_event
      FOREIGN KEY (event_id) REFERENCES event (id),
  ADD CONSTRAINT booking_fk_training
      FOREIGN KEY (training_id) REFERENCES training (id),
  ADD CONSTRAINT chk_booking_one_target -- Enforce exactly one of event_id or training_id is non‐NULL
      CHECK ((event_id IS NULL AND training_id IS NOT NULL) OR (event_id IS NOT NULL AND training_id IS NULL));

-- ===============================================
-- 4. INDEXES
-- ===============================================

ALTER TABLE article
  ADD INDEX idx_article_published (enabled_at),
  ADD INDEX idx_article_featured (featured),
  ADD INDEX idx_article_category (category_id),
  ADD FULLTEXT KEY idx_article_search (label, summary, content);

ALTER TABLE event
  ADD INDEX idx_event_revoked_at_date (revoked_at, event_date),
  ADD INDEX idx_event_category_id (category_id);

ALTER TABLE operator
  ADD INDEX idx_operator_status (status);

ALTER TABLE taxonomy
  ADD INDEX idx_taxonomy_revoked_at (revoked_at);

ALTER TABLE trainer
  ADD INDEX idx_trainer_revoked_at (revoked_at);
  
ALTER TABLE training
  ADD INDEX idx_training_level_id (level_id),
  ADD INDEX idx_training_date (start_date),
  ADD INDEX idx_training_revoked_at (revoked_at);

ALTER TABLE contact_request
  ADD INDEX idx_contact_status_id (status_id),
  ADD INDEX idx_contact_created_at (created_at),
  ADD INDEX idx_contact_subject_id (subject_id);

ALTER TABLE newsletter_subscription
  ADD INDEX idx_newsletter_revoked_at (revoked_at);

ALTER TABLE operator_session
  ADD INDEX idx_operator_session_activity (last_activity);

ALTER TABLE booking
  ADD INDEX idx_booking_status_id (status_id);