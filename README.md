# PHP MVC "Guess Words" Web Application

A custom lightweight MVC application that lets users select letters and find all valid dictionary words that can be created from those letters.

## Features

- Interactive A-Z letter selection interface
- Find all valid dictionary words from selected letters
- Lightweight custom MVC architecture (no frameworks)
- MySQL database with optimized word storage and retrieval

## Requirements

- PHP 7.4+
- MySQL 5.7+
- Web server (Apache/Nginx) or PHP built-in server

## Project Structure

```
/guess-words-app
    /app
        /controllers
            GuessController.php
        /models
            WordModel.php
        /views
            guess.php
        BaseController.php
        BaseModel.php
        Router.php
    /public
        index.php
        /assets
            /js
                app.js
            /css
                style.css
    /config
        database.php
        .env.example
    /migrations
        20250501_create_words_table.sql
    /seeders
        WordsSeeder.php
    README.md
```

## Installation & Setup

### 1. Clone the repository

```bash
git clone https://github.com/yahiaAlg/mvc_word_generator.git
cd mvc_word_generator
```

### 2. Set up environment variables

```bash
cp config/.env.example config/.env
```

Edit the `config/.env` file with your database credentials:

```
DB_HOST=localhost
DB_DATABASE=guess_words
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Create and seed the database

Run the migration:

```bash
mysql -u root -p < migrations/20250501_create_words_table.sql
```

username: `root`, password: ` ` ( empty password )
Import a dictionary file using the seeder:

```bash
php seeders/WordsSeeder.php path/to/dictionary.txt
```

> **Note:** You need to provide a dictionary file - a plain text file with one word per line.

### 4. Start the web server

Using PHP's built-in web server:

```bash
cd public
php -S localhost:8000
```

Or configure Apache/Nginx to point to the `/public` directory.

### 5. Access the application

Open your browser and navigate to:

```
http://localhost:8000
```

## How It Works

1. The application uses a MySQL database to store words and their "signatures" (alphabetically sorted letters).
2. When users select letters and click "Guess Words", the app computes the signature of the selected letters.
3. The database is queried to find all words that match the signature.
4. Results are returned to the frontend via AJAX and displayed to the user.

## Database Schema

The application uses a single table:

```sql
CREATE TABLE words (
  id INT AUTO_INCREMENT PRIMARY KEY,
  word VARCHAR(100) NOT NULL,
  signature VARCHAR(26) NOT NULL,
  INDEX(signature)
);
```

Where:

- `word` is the actual dictionary word
- `signature` is the sorted letters of the word (e.g., "cat" has signature "act")

## Technical Details

### MVC Architecture

- **Model**: Handles database operations and business logic
- **View**: Renders the user interface
- **Controller**: Processes user input and coordinates between the model and view

### Word Signatures

The application uses a "signature" system to efficiently find words:

1. A word's signature is created by sorting its letters alphabetically (e.g., "cat" → "act")
2. When a user selects letters, the same signature calculation is performed
3. The database is queried for all words with a matching signature
4. This approach is much more efficient than trying to generate all possible permutations

## Acknowledgements

- This project was developed as a custom PHP MVC application without using any frameworks
- The letter-button interface is inspired by word games like Scrabble and Boggle

## License

This project is licensed under the MIT License - see the LICENSE file for details.
