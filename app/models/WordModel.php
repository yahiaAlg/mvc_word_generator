<?php
/**
 * WordModel - Model for word operations
 * 
 * Handles database operations for the words table
 */

require_once __DIR__ . '/../BaseModel.php';

class WordModel extends BaseModel
{
    /**
     * Find words by their letter signature
     * A signature is the sorted letters of a word (e.g., "act" and "cat" both have signature "act")
     * 
     * @param string $signature The letter signature to search for
     * @return array List of matching words
     */
    public function findBySignature($signature)
    {
        $sql = "SELECT word FROM words WHERE signature = :signature ORDER BY word";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['signature' => $signature]);
        
        // Get all words as a flat array
        $results = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        
        return $results;
    }
    
    /**
     * Create a letter signature from a string
     * Signature is all letters sorted alphabetically, preserving duplicates
     * 
     * @param string $letters The letters to create a signature from
     * @return string The letter signature
     */
    public function createSignature($letters)
    {
        // Convert to lowercase
        $letters = strtolower($letters);
        
        // Remove non-letter characters
        $letters = preg_replace('/[^a-z]/', '', $letters);
        
        // Split into array of characters
        $chars = str_split($letters);
        
        // Sort alphabetically
        sort($chars);
        
        // Join back into a string
        return implode('', $chars);
    }
    
    /**
     * Find words that can be formed using the given letters
     * This checks if the word's signature can be formed using the available letters
     * 
     * @param string $letters The available letters
     * @return array List of words that can be formed
     */
    public function findWordsByAvailableLetters($letters)
    {
        // Create signature from letters
        $signature = $this->createSignature($letters);
        
        // Get letter frequencies in the signature
        $letterCounts = array_count_values(str_split($signature));
        
        // Query all potential words from the database that use these letters
        // We'll filter them more precisely in PHP
        $letterPattern = '';
        foreach ($letterCounts as $letter => $count) {
            $letterPattern .= $letter;
        }
        
        // Find words where signature only contains letters from our pattern
        $sql = "SELECT word, signature FROM words WHERE signature REGEXP '^[{$letterPattern}]+$' ORDER BY word";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $potentialWords = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Filter words to ensure they use only the available letters (with correct frequencies)
        $validWords = [];
        foreach ($potentialWords as $word) {
            // Check if word can be formed with available letters
            $wordLetterCounts = array_count_values(str_split($word['signature']));
            $valid = true;
            
            // Check if each letter in the word appears no more than in our available letters
            foreach ($wordLetterCounts as $letter => $count) {
                if (!isset($letterCounts[$letter]) || $letterCounts[$letter] < $count) {
                    $valid = false;
                    break;
                }
            }
            
            if ($valid) {
                $validWords[] = $word['word'];
            }
        }
        
        return $validWords;
    }
}