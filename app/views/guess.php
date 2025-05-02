<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guess Words App</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Guess Words</h1>
        
        <p>Select letters and click "Guess Words" to find all valid dictionary words.</p>
        
        <div class="letter-buttons">
            <?php
            // Generate buttons for all letters A-Z
            for ($i = 0; $i < 26; $i++) {
                $letter = chr(65 + $i); // ASCII for A-Z
                echo "<button class=\"letter-btn\" data-letter=\"$letter\">$letter</button>";
            }
            ?>
        </div>
        
        <div class="controls">
            <div class="selected-letters" id="selected-letters">
                <span class="placeholder">No letters selected</span>
            </div>
            
            <button class="guess-btn" id="guess-btn">Guess Words</button>
            <button class="clear-btn" id="clear-btn">Clear Selection</button>
        </div>
        
        <div class="results-container">
            <h2>Results</h2>
            <div class="results" id="results">
                <p class="no-results">Select letters and click "Guess Words" to see results.</p>
            </div>
        </div>
    </div>
    
    <script src="assets/js/app.js"></script>
</body>
</html>