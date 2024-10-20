<?php
header("Access-Control-Allow-Origin: *"); // Allows requests from any origin
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit();
}

$host = "localhost";
$dbname = "dbpost";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

$post_id = $data['post_id'];
$user_id = $data['user_id'];
$comment = $data['comment'];
$comment_date = $data['comment_date'];

if (isset($post_id, $user_id, $comment, $comment_date)) {
    try {
        $stmt = $pdo->prepare('
            INSERT INTO tblcomments (post_id, user_id, comment, comment_date) 
            VALUES (?, ?, ?, ?)
        ');
        $stmt->execute([$post_id, $user_id, $comment, $comment_date]);

        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save comment: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
}

$pdo = null;
?>
