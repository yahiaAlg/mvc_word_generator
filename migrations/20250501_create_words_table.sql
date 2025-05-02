-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS guess_words;

-- Use the database
USE guess_words;

-- Drop table if it exists
DROP TABLE IF EXISTS words;

-- Create words table
CREATE TABLE words (
  id INT AUTO_INCREMENT PRIMARY KEY,
  word VARCHAR(100) NOT NULL,
  signature VARCHAR(26) NOT NULL,
  INDEX(signature)
);