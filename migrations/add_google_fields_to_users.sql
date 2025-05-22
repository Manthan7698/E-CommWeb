-- Add Google-specific fields to users table
ALTER TABLE users
ADD COLUMN google_id VARCHAR(255) NULL UNIQUE,
ADD COLUMN is_google_user TINYINT(1) DEFAULT 0,
ADD COLUMN profile_picture VARCHAR(255) NULL;

-- Update existing columns to be nullable for Google users
ALTER TABLE users
MODIFY COLUMN password VARCHAR(255) NULL; 