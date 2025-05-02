# PHP MVC “Guess Words” Web App — AI Prompt

You are an expert full‑stack engineer. Build a PHP MVC web application (custom lightweight MVC, **no** Laravel) with a MySQL database that provides a “letter‑buttons” interface and generates all valid dictionary words buildable from the selected letters when the user clicks **Guess Words**.

---

## 🎯 Deliverables

1. **Directory structure**

   - `/app`
     - `/controllers`
     - `/models`
     - `/views`
     - `BaseController.php`, `BaseModel.php`, `Router.php`
   - `/public`
     - `index.php` (front controller)
     - `/assets/js/app.js`
     - `/assets/css/style.css`
   - `/config`
     - `database.php` (PDO, .env-driven)
   - `/migrations`
     - `20250501_create_words_table.sql`
   - `/seeders`
     - `WordsSeeder.php`
   - `README.md`

2. **Database schema**

   - `words` table:
     ```sql
     CREATE TABLE words (
       id INT AUTO_INCREMENT PRIMARY KEY,
       word VARCHAR(100) NOT NULL,
       signature VARCHAR(26) NOT NULL,
       INDEX(signature)
     );
     ```
   - Seeder script to import a plaintext dictionary (one word per line), computing each word’s “signature” (letters sorted).

3. **MVC Components**

   - **Model** (`app/models/WordModel.php`)
     - Connect via PDO (credentials from `config/database.php`)
     - Method `findBySignature(string $sig): array` to query matching words.
   - **Controller** (`app/controllers/GuessController.php`)
     - Action `guess()` reads POSTed letters, builds signature, calls `WordModel`, returns JSON.
   - **View** (`app/views/guess.php`)
     - Renders A–Z buttons, “Guess Words” button, and results container.

4. **Front‑end interactivity**

   - JS (`public/assets/js/app.js`):
     1. Toggle CSS class on letter buttons.
     2. On “Guess Words” click, collect selected letters, send AJAX POST to `/guess`.
     3. Render returned word list in DOM.

5. **Coding standards**

   - PSR‑12 for PHP
   - PDO with prepared statements
   - Semantic HTML5, BEM or utility‑class CSS

6. **Setup instructions** (in README)
   1. Copy `.env.example` → `.env`, set DB credentials.
   2. Run migration: `mysql < migrations/20250501_create_words_table.sql`
   3. Run seeder: `php seeders/WordsSeeder.php dictionary.txt`
   4. Serve via built‑in PHP server or Apache/Nginx.

---

## 🚀 Step‑by‑Step Prompt

```

You are an expert PHP MVC developer. Create a custom lightweight MVC project (no frameworks) with PHP 7.4+ and MySQL 5.7+.

1. **Scaffold** the directory structure and base classes (Router, BaseController, BaseModel).
2. **Write** the SQL migration to create the `words` table (id, word, signature) with an index on `signature`.
3. **Implement** a seeder script that reads a dictionary file (one word per line), computes each word’s sorted‐letters signature, and inserts into the table.
4. **Build** `WordModel` with a method `findBySignature($sig)` using PDO prepared statements.
5. **Build** `GuessController::guess()` to accept an AJAX POST of selected letters, compute signature, fetch matching words, and return JSON.
6. **Create** a view `guess.php` that:

   * Displays 26 letter‑buttons (A–Z).
   * Toggles `.selected` class on click.
   * Includes a “Guess Words” button and an empty `<div id="results">`.
7. **Write** `app.js` to collect selected letters, send AJAX to `/guess`, and render the returned words in `#results`.
8. **Style** with CSS so selected buttons highlight, results list is readable.
9. **Provide** a README with setup/deploy steps.

For each file, output full code with comments.


```
