-- Back up sip_n_tell_db in phpMyAdmin before running this migration.
-- The current admin feature uses username and password_hash only.

ALTER TABLE admin
    DROP COLUMN full_name,
    DROP COLUMN address,
    DROP COLUMN profile_picture;
