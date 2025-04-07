-- This SQL script initializes the database for the game application.

-- 1. Create the database with name e.g. 'connect4' and be sure you added it to .env file
CREATE DATABASE connect4;

-- 2. Execute the SQL commands to create tables

-- Create the `players` table
CREATE TABLE players (
    player_id INT AUTO_INCREMENT PRIMARY KEY,
    game_id INT DEFAULT NULL,
    opponent_id INT DEFAULT NULL,
    status ENUM('NONE', 'WAITING', 'CONFIRMING', 'READY', 'PLAYER_MOVE', 'OPPONENT_MOVE', 'WIN', 'LOSE', 'DRAW', 'REVENGE') DEFAULT 'NONE',
    nickname VARCHAR(255) DEFAULT 'NONE',
    balls_location JSON NULL,
    wins INT DEFAULT 0,
    player_color VARCHAR(7) DEFAULT '#ffffff',
    opponent_color VARCHAR(7) DEFAULT '#000000',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create the `games` table
CREATE TABLE games (
    game_id INT AUTO_INCREMENT PRIMARY KEY,
    unique_game_id VARCHAR(6) NOT NULL UNIQUE,
    board_size VARCHAR(10) DEFAULT '8x8',
    winning_balls JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Add foreign key constraints
ALTER TABLE players
ADD CONSTRAINT fk_game_id FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE SET NULL;