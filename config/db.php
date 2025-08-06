<?php
require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;
use Dotenv\Dotenv;

class Database {
    private $client;
    private $db;

    public function __construct() {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $uri = $_ENV['MONGODB_URI'];
        $dbName = $_ENV['MONGODB_DB'];

        $this->client = new Client($uri);
        $this->db = $this->client->$dbName;
    }

    public function getDb() {
        return $this->db;
    }

    public function getCollection($collectionName) {
        return $this->db->$collectionName;
    }
}
