#!/bin/bash

# Script to create Guess Words App project structure

# Define project root
PROJECT_ROOT="mvc_word_generator"

# Create directories
mkdir -p "$PROJECT_ROOT/app/controllers" \
         "$PROJECT_ROOT/app/models" \
         "$PROJECT_ROOT/app/views" \
         "$PROJECT_ROOT/public/assets/js" \
         "$PROJECT_ROOT/public/assets/css" \
         "$PROJECT_ROOT/config" \
         "$PROJECT_ROOT/migrations" \
         "$PROJECT_ROOT/seeders"

# Create empty files
# app/controllers and core files
touch "$PROJECT_ROOT/app/controllers/GuessController.php" \
      "$PROJECT_ROOT/app/BaseController.php" \
      "$PROJECT_ROOT/app/BaseModel.php" \
      "$PROJECT_ROOT/app/Router.php"

# app/models
 touch "$PROJECT_ROOT/app/models/WordModel.php"

# app/views
touch "$PROJECT_ROOT/app/views/guess.php"

# public files
 touch "$PROJECT_ROOT/public/index.php" \
      "$PROJECT_ROOT/public/assets/js/app.js" \
      "$PROJECT_ROOT/public/assets/css/style.css"

# config files
 touch "$PROJECT_ROOT/config/database.php" \
      "$PROJECT_ROOT/config/.env.example"

# migrations and seeders
touch "$PROJECT_ROOT/migrations/20250501_create_words_table.sql" \
      "$PROJECT_ROOT/seeders/WordsSeeder.php"

# README
 touch "$PROJECT_ROOT/README.md"

 echo "Guess Words App project structure created successfully."
