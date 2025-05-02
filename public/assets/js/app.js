/**
 * Guess Words App JavaScript
 */
document.addEventListener("DOMContentLoaded", function () {
  // DOM elements
  const letterButtons = document.querySelectorAll(".letter-btn");
  const guessButton = document.getElementById("guess-btn");
  const clearButton = document.getElementById("clear-btn");
  const selectedLettersElement = document.getElementById("selected-letters");
  const resultsElement = document.getElementById("results");

  // Track letter counts (up to 4 per letter)
  const letterCounts = {};

  // Add event listeners to letter buttons
  letterButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const letter = this.dataset.letter;

      // Initialize count if not exists
      if (!letterCounts[letter]) {
        letterCounts[letter] = 0;
      }

      // Handle letter selection logic
      if (letterCounts[letter] >= 4) {
        // Reset if already at max count
        letterCounts[letter] = 0;
        this.classList.remove("selected");

        // Remove counter element
        const counter = this.querySelector(".letter-count");
        if (counter) {
          counter.remove();
        }
      } else {
        // Increment count
        letterCounts[letter]++;

        // Add selected class if first selection
        if (letterCounts[letter] === 1) {
          this.classList.add("selected");
        }

        // Update or create counter element
        if (letterCounts[letter] > 1) {
          let counter = this.querySelector(".letter-count");

          // Create counter if it doesn't exist
          if (!counter) {
            counter = document.createElement("span");
            counter.classList.add("letter-count");
            this.appendChild(counter);
          }

          // Set counter text
          counter.textContent = letterCounts[letter];
        }
      }

      updateSelectedLetters();
    });
  });

  // Add event listener to guess button
  guessButton.addEventListener("click", function () {
    const selectedLetters = getSelectedLetters();

    if (selectedLetters.length === 0) {
      showError("Please select at least one letter");
      return;
    }

    guessWords(selectedLetters);
  });

  // Add event listener to clear button
  clearButton.addEventListener("click", function () {
    clearSelection();
  });

  /**
   * Update the selected letters display
   */
  function updateSelectedLetters() {
    const letters = getSelectedLetters();

    if (letters.length === 0) {
      selectedLettersElement.innerHTML =
        '<span class="placeholder">No letters selected</span>';
    } else {
      selectedLettersElement.textContent = letters.join(" ");
    }
  }

  /**
   * Get the currently selected letters
   * @return {Array} Array of selected letters (with duplicates based on count)
   */
  function getSelectedLetters() {
    const selected = [];

    // Add letters based on their counts
    Object.keys(letterCounts).forEach((letter) => {
      // Add letter the number of times it's been selected
      for (let i = 0; i < letterCounts[letter]; i++) {
        selected.push(letter);
      }
    });

    return selected;
  }

  /**
   * Send AJAX request to guess words
   * @param {Array} letters Array of selected letters
   */
  function guessWords(letters) {
    // Show loading state
    resultsElement.innerHTML = "<p>Loading...</p>";

    // Create form data
    const formData = new FormData();
    formData.append("letters", letters.join(""));

    // Send AJAX request
    fetch("guess", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        displayResults(data);
      })
      .catch((error) => {
        showError("Error: " + error.message);
      });
  }

  /**
   * Display the results of the guess
   * @param {Object} data Response data from the server
   */
  function displayResults(data) {
    // Check if there's an error
    if (data.error) {
      showError(data.error);
      return;
    }

    // Check if there are any words
    if (data.words.length === 0) {
      resultsElement.innerHTML =
        '<p class="no-results">No words found for these letters.</p>';
      return;
    }

    // Display words
    let html = `<p class="result-count">Found ${data.count} word(s):</p>`;
    html += '<div class="words-list">';

    data.words.forEach((word) => {
      html += `<div class="word-item">${word}</div>`;
    });

    html += "</div>";

    resultsElement.innerHTML = html;
  }

  /**
   * Show an error message
   * @param {string} message Error message to display
   */
  function showError(message) {
    resultsElement.innerHTML = `<p class="no-results error">${message}</p>`;
  }

  /**
   * Clear all selected letters
   */
  function clearSelection() {
    letterButtons.forEach((button) => {
      button.classList.remove("selected");
      const counter = button.querySelector(".letter-count");
      if (counter) {
        counter.remove();
      }
    });

    // Reset letter counts
    Object.keys(letterCounts).forEach((key) => delete letterCounts[key]);

    updateSelectedLetters();
    resultsElement.innerHTML =
      '<p class="no-results">Select letters and click "Guess Words" to see results.</p>';
  }
});
