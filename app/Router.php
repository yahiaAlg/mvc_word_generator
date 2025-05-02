<?php
/**
 * Router - Simple MVC Router
 * 
 * Handles URL routing to appropriate controllers and actions
 */

class Router
{
    private $routes = [];
    
    /**
     * Add a new route to the routing table
     * 
     * @param string $method HTTP method (GET, POST)
     * @param string $path URL path
     * @param string $controller Controller class name
     * @param string $action Controller action/method name
     * @return void
     */
    public function addRoute($method, $path, $controller, $action)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    /**
     * Resolve the current request to controller and action
     * 
     * @return array|bool Returns controller/action array or false if no route found
     */
    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove leading slash and base directory if in subdirectory
        $base = dirname($_SERVER['SCRIPT_NAME']);
        if ($base !== '/' && strpos($path, $base) === 0) {
            $path = substr($path, strlen($base));
        }
        
        // Remove leading slash if present
        $path = ltrim($path, '/');
        
        // Default to index if path is empty
        if ($path === '') {
            $path = 'index';
        }
        
        // Find matching route
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                return [
                    'controller' => $route['controller'],
                    'action' => $route['action']
                ];
            }
        }
        
        return false;
    }
    
    /**
     * Dispatch the request to appropriate controller/action
     * 
     * @return mixed Output from controller action
     */
    public function dispatch()
    {
        $route = $this->resolve();
        
        if ($route === false) {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
            return;
        }
        
        $controllerName = $route['controller'];
        $actionName = $route['action'];
        
        // Check if controller exists
        if (!class_exists($controllerName)) {
            throw new Exception("Controller {$controllerName} not found.");
        }
        
        $controller = new $controllerName();
        
        // Check if action exists
        if (!method_exists($controller, $actionName)) {
            throw new Exception("Action {$actionName} not found in controller {$controllerName}.");
        }
        
        // Call the controller action
        return $controller->$actionName();
    }
}