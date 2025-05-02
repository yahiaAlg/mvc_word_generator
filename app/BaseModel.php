<?php
/**
 * BaseModel - Abstract base model class
 * 
 * Provides database connection and common methods for all models
 */

abstract class BaseModel
{
    protected $db;
    
    /**
     * Constructor - creates database connection
     */
    public function __construct()
    {
        $this->connect();
    }
    
    /**
     * Connect to the database using config/database.php settings
     * 
     * @return void
     */
    protected function connect()
    {
        $config = require __DIR__ . '/../config/database.php';
        
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4";
            $this->db = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
}