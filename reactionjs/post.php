<?php
header('Content-Type: application/json');

// Allow requests from http://localhost:3000
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// Database connection parameters
$host = "localhost";
$dbname = "dbpost";
$user = "root";
$pass = "";

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read and decode the JSON body
    $data = json_decode(file_get_contents('php://input'), true);

    $user_id = $data['user_id'] ?? null;
    $post_content = $data['post_content'] ?? null;
    $post_date = date('Y-m-d H:i:s'); // Set the current date and time

    if ($user_id && $post_content) {
        try {
            // Prepare and execute SQL statement
            $stmt = $pdo->prepare('INSERT INTO tblposts (user_id, post_content, post_date) VALUES (:user_id, :post_content, :post_date)');
            $stmt->execute([
                ':user_id' => $user_id,
                ':post_content' => $post_content,
                ':post_date' => $post_date
            ]);

            // Get the ID of the new post
            $post_id = $pdo->lastInsertId();

            echo json_encode(['status' => 'success', 'message' => 'Post added successfully', 'post_id' => $post_id]);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add post: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
