CREATE DATABASE IF NOT EXISTS helphive CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE helphive;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('volunteer','organization','admin') NOT NULL DEFAULT 'volunteer',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_users_role (role)
) ENGINE=InnoDB;

CREATE TABLE organizations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL UNIQUE,
  name VARCHAR(160) NOT NULL,
  description TEXT NULL,
  contact_email VARCHAR(190) NULL,
  location VARCHAR(160) NULL,
  is_approved TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_org_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE opportunities (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  organization_id INT UNSIGNED NOT NULL,
  title VARCHAR(180) NOT NULL,
  description TEXT NOT NULL,
  cause_area VARCHAR(80) NOT NULL,
  location VARCHAR(160) NOT NULL,
  start_at DATETIME NOT NULL,
  end_at DATETIME NOT NULL,
  registration_deadline DATETIME NOT NULL,
  capacity SMALLINT UNSIGNED NOT NULL,
  requirements TEXT NULL,
  status ENUM('draft','published','cancelled','completed') NOT NULL DEFAULT 'draft',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_opp_org FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE,
  CONSTRAINT chk_opp_capacity CHECK (capacity > 0),
  CONSTRAINT chk_opp_dates CHECK (end_at > start_at AND registration_deadline <= start_at),
  INDEX idx_opp_status_start (status, start_at),
  INDEX idx_opp_cause (cause_area),
  INDEX idx_opp_location (location),
  FULLTEXT INDEX ft_opp_search (title, description)
) ENGINE=InnoDB;

CREATE TABLE registrations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  opportunity_id INT UNSIGNED NOT NULL,
  volunteer_id INT UNSIGNED NOT NULL,
  status ENUM('registered','cancelled','waitlisted') NOT NULL DEFAULT 'registered',
  registered_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reg_opp FOREIGN KEY (opportunity_id) REFERENCES opportunities(id) ON DELETE CASCADE,
  CONSTRAINT fk_reg_vol FOREIGN KEY (volunteer_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT uq_reg_unique UNIQUE (opportunity_id, volunteer_id),
  INDEX idx_reg_volunteer (volunteer_id),
  INDEX idx_reg_opp_status (opportunity_id, status)
) ENGINE=InnoDB;

CREATE TABLE attendance_and_hours (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  registration_id INT UNSIGNED NOT NULL UNIQUE,
  attendance_status ENUM('pending','present','absent','excused') NOT NULL DEFAULT 'pending',
  hours_claimed DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  hours_verified DECIMAL(5,2) NULL,
  verification_status ENUM('unverified','pending','verified','rejected') NOT NULL DEFAULT 'unverified',
  verified_by INT UNSIGNED NULL,
  verified_at DATETIME NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_ah_reg FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE,
  CONSTRAINT fk_ah_verifier FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL,
  CONSTRAINT chk_ah_hours CHECK (hours_claimed >= 0 AND (hours_verified IS NULL OR hours_verified >= 0)),
  CONSTRAINT chk_ah_verified CHECK (
    (verification_status = 'verified' AND hours_verified IS NOT NULL AND verified_at IS NOT NULL)
    OR (verification_status <> 'verified' AND hours_verified IS NULL)
  ),
  INDEX idx_ah_status (verification_status)
) ENGINE=InnoDB;

CREATE TABLE notifications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  type VARCHAR(40) NOT NULL,
  message VARCHAR(255) NOT NULL,
  link VARCHAR(255) NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_notif_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_notif_user_read (user_id, is_read)
) ENGINE=InnoDB;