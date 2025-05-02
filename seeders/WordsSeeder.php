<?php
/**
 * Words Seeder
 * 
 * Imports words from a dictionary file into the database
 * Usage: php WordsSeeder.php path/to/dictionary.txt
 */

// Check for dictionary file argument
if ($argc < 2) {
    echo "Usage: php WordsSeeder.php path/to/dictionary.txt\n";
    exit(1);
}

$dictionaryFile = $argv[1];

// Check if file exists
if (!file_exists($dictionaryFile)) {
    echo "Error: Dictionary file not found at {$dictionaryFile}\n";
    exit(1);
}

// Load database configuration
$config = require __DIR__ . '/../config/database.php';

try {
    // Connect to database
    $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    echo "Connected to database successfully.\n";
    
    // Prepare insert statement
    $stmt = $pdo->prepare("INSERT INTO words (word, signature) VALUES (:word, :signature)");
    
    // Read dictionary file
    $words = file($dictionaryFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $totalWords = count($words);
    
    echo "Found {$totalWords} words in dictionary file.\n";
    echo "Importing words into database...\n";
    
    // Counter variables
    $importedCount = 0;
    $batchSize = 1000;
    $startTime = microtime(true);
    
    // Begin transaction for better performance
    $pdo->beginTransaction();
    
    // Process each word
    foreach ($words as $index => $word) {
        // Clean the word (remove whitespace, lowercase)
        $word = trim(strtolower($word));
        
        // Skip empty words
        if (empty($word)) {
            continue;
        }
        
        // Generate signature (letters sorted alphabetically)
        $signature = createSignature($word);
        
        // Insert into database
        $stmt->execute([
            'word' => $word,
            'signature' => $signature
        ]);
        
        $importedCount++;
        
        // Commit every batch size
        if ($importedCount % $batchSize === 0) {
            $pdo->commit();
            $pdo->beginTransaction();
            $percent = round(($importedCount / $totalWords) * 100, 2);
            echo "Imported {$importedCount} words ({$percent}%)...\n";
        }
    }
    
    // Commit any remaining words
    $pdo->commit();
    
    $endTime = microtime(true);
    $duration = round($endTime - $startTime, 2);
    
    echo "Import completed. Total {$importedCount} words imported in {$duration} seconds.\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

/**
 * Create a letter signature from a string
 * Signature is all letters sorted alphabetically
 * 
 * @param string $word The word to create a signature from
 * @return string The letter signature
 */
function createSignature($word) {
    // Remove non-letter characters
    $word = preg_replace('/[^a-z]/', '', $word);
    
    // Split into array of characters
    $chars = str_split($word);
    
    // Sort alphabetically
    sort($chars);
    
    // Join back into a string
    return implode('', $chars);
}