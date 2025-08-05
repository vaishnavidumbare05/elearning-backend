<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

require_once '../config/db.php';

// Initialize the database connection
$database = new Database();
$db = $database->getDb();

$assessmentId = $_GET['id'] ?? null;

if ($assessmentId) {
    try {
        // Fetch assessment status based on the assessmentId
        $collection = $db->assessment_results; // The collection for assessment results
        $assessment = $collection->findOne(['assessmentId' => $assessmentId]); // Find the assessment
        
        if ($assessment) {
            // Return the status of the assessment
            echo json_encode(['status' => $assessment['status']]);
        } else {
            echo json_encode(['status' => 'Not Found']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No assessment ID provided.']);
}
?>
