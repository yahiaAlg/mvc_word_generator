<?php
/**
 * BaseController - Abstract base controller class
 * 
 * Provides common functionality for all controllers
 */

abstract class BaseController
{
    /**
     * Render a view file with optional data
     * 
     * @param string $view Name of view file
     * @param array $data Data to pass to the view
     * @return void
     */
    protected function render($view, $data = [])
    {
        // Extract data to make it available in view scope
        extract($data);
        
        // Include the view file
        $viewPath = __DIR__ . '/views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new Exception("View file {$view}.php not found.");
        }
        
        // Start output buffering to capture view output
        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        
        echo $content;
    }
    
    /**
     * Send JSON response
     * 
     * @param mixed $data Data to convert to JSON
     * @param int $statusCode HTTP status code
     * @return void
     */
    protected function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
    }
}