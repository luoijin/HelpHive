USE helphive;

-- -------------------------------------------------------------
-- Seed Users (Default password for all seeded accounts: password123)
-- -------------------------------------------------------------
INSERT INTO users (id, full_name, email, password_hash, role, is_active) VALUES
(1, 'Admin User', 'admin@helphive.org', '$2y$10$yf0Es3W.SeGhk2uSMhgIxeGoQTNOnpCLzAZL/WFOFwSpJySHycdQi', 'admin', 1),
(2, 'Blue Shores Rep', 'contact@blueshores.org', '$2y$10$yf0Es3W.SeGhk2uSMhgIxeGoQTNOnpCLzAZL/WFOFwSpJySHycdQi', 'organization', 1),
(3, 'Green Shift Rep', 'hello@greenshift.org', '$2y$10$yf0Es3W.SeGhk2uSMhgIxeGoQTNOnpCLzAZL/WFOFwSpJySHycdQi', 'organization', 1),
(4, 'Paws & Care Rep', 'info@pawscare.org', '$2y$10$yf0Es3W.SeGhk2uSMhgIxeGoQTNOnpCLzAZL/WFOFwSpJySHycdQi', 'organization', 1),
(5, 'John Smith', 'johnsmith@example.com', '$2y$10$yf0Es3W.SeGhk2uSMhgIxeGoQTNOnpCLzAZL/WFOFwSpJySHycdQi', 'volunteer', 1),
(6, 'Maria Santos', 'maria@example.com', '$2y$10$yf0Es3W.SeGhk2uSMhgIxeGoQTNOnpCLzAZL/WFOFwSpJySHycdQi', 'volunteer', 1);

-- -------------------------------------------------------------
-- Seed Organizations
-- -------------------------------------------------------------
INSERT INTO organizations (id, user_id, name, description, contact_email, location, is_approved) VALUES
(1, 2, 'Blue Shores Initiative', 'Dedicated to shoreline conservation, marine ecosystem restoration, and coastal cleanups.', 'contact@blueshores.org', 'Lapu-Lapu City, Cebu', 1),
(2, 3, 'Green Shift Media', 'Youth-led digital environmental awareness and community sustainability advocacy.', 'hello@greenshift.org', 'Lapu-Lapu City, Cebu', 1),
(3, 4, 'Paws & Care Foundation', 'Animal shelter caring for rescued animals and finding loving adoptive homes.', 'info@pawscare.org', 'Cebu City, Cebu', 1);

-- -------------------------------------------------------------
-- Seed Opportunities (Figma Nodes 391:10748 & 389:17049)
-- -------------------------------------------------------------
INSERT INTO opportunities (id, organization_id, title, description, cause_area, location, start_at, end_at, registration_deadline, capacity, requirements, status) VALUES
(1, 1, 'Coastal Cleanup Drive', 'Collect litter along the coast to protect marine life and keep local beaches clean. Help keep our shorelines pristine and ensure aquatic biodiversity thrives.', 'Environment', 'Lapu-Lapu City, Cebu', '2026-11-20 07:00:00', '2026-11-20 10:00:00', '2026-11-19 18:00:00', 50, 'Wear comfortable clothes, bring drinking water and hat. Trash bags and gloves provided.', 'published'),
(2, 2, 'Environmental Awareness Creator', 'Create digital infographics or awareness posts about recycling, plastic pollution, and environmental conservation.', 'Environment', 'Lapu-Lapu City, Cebu', '2026-11-21 09:00:00', '2026-11-21 12:00:00', '2026-11-20 18:00:00', 15, 'Proficiency in Canva or Adobe Express, stable internet connection.', 'published'),
(3, 3, 'Animal Shelter Assistant', 'Help care for rescued animals by preparing food, cleaning their spaces, and giving them attention and love.', 'Animal Welfare', 'Cebu City, Cebu', '2026-11-22 08:00:00', '2026-11-22 12:00:00', '2026-11-21 18:00:00', 20, 'Comfortable around cats and dogs, wear closed shoes.', 'published'),
(4, 1, 'Mangrove Tree Planting', 'Plant mangrove seedlings along the intertidal zone to restore natural coastal protection barriers.', 'Environment', 'Olango Island, Cebu', '2026-11-28 07:30:00', '2026-11-28 11:30:00', '2026-11-27 12:00:00', 40, 'Ready to walk in mud and shallow water, sunscreen recommended.', 'published'),
(5, 3, 'Weekend Reading Buddies', 'Help children build reading comprehension and confidence through engaging storytelling sessions.', 'Education', 'Cebu City, Cebu', '2026-11-29 08:30:00', '2026-11-29 11:30:00', '2026-11-28 17:00:00', 12, 'Patience and enthusiasm for teaching elementary kids.', 'published');

-- -------------------------------------------------------------
-- Seed Registrations
-- -------------------------------------------------------------
INSERT INTO registrations (id, opportunity_id, volunteer_id, status, registered_at) VALUES
(1, 1, 5, 'registered', '2026-11-01 09:00:00'),
(2, 3, 5, 'registered', '2026-11-02 10:30:00'),
(3, 1, 6, 'registered', '2026-11-01 11:00:00');

-- -------------------------------------------------------------
-- Seed Attendance & Hours (Business invariant: verified only with verified_by and verified_at)
-- -------------------------------------------------------------
INSERT INTO attendance_and_hours (id, registration_id, attendance_status, hours_claimed, hours_verified, verification_status, verified_by, verified_at) VALUES
(1, 1, 'present', 3.00, 3.00, 'verified', 2, '2026-11-02 14:00:00'),
(2, 2, 'present', 4.00, NULL, 'pending', NULL, NULL),
(3, 3, 'present', 3.00, 3.00, 'verified', 2, '2026-11-02 14:00:00');

-- -------------------------------------------------------------
-- Seed Notifications
-- -------------------------------------------------------------
INSERT INTO notifications (id, user_id, type, message, link, is_read) VALUES
(1, 5, 'hours_verified', 'Blue Shores Initiative verified 3.00 service hours for Coastal Cleanup Drive.', '/volunteer/hours', 0),
(2, 5, 'registration_approved', 'Your registration for Coastal Cleanup Drive was confirmed!', '/volunteer/registrations', 1),
(3, 2, 'new_registration', 'John Smith registered for Coastal Cleanup Drive.', '/org/opportunities/1/attendance', 0);
