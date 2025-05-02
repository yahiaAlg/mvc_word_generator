<?php
/**
 * GuessController - Controller for word guessing functionality
 */

require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../models/WordModel.php';

class GuessController extends BaseController
{
    private $wordModel;
    
    /**
     * Constructor - initialize models
     */
    public function __construct()
    {
        $this->wordModel = new WordModel();
    }
    
    /**
     * Index action - display the main guess page
     * 
     * @return void
     */
    public function index()
    {
        $this->render('guess');
    }
    
    /**
     * Guess action - handle AJAX request for guessing words
     * Expects POST data with 'letters' parameter
     * 
     * @return void
     */
    public function guess()
    {
        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }
        
        // Get letters from POST data
        $letters = isset($_POST['letters']) ? $_POST['letters'] : '';
        
        // Validate letters
        if (empty($letters)) {
            $this->json(['error' => 'No letters provided'], 400);
            return;
        }
        
        // Find words that can be formed using the available letters
        $words = $this->wordModel->findWordsByAvailableLetters($letters);
        
        // Return words as JSON
        $this->json([
            'letters' => $letters,
            'words' => $words,
            'count' => count($words)
        ]);
    }
}