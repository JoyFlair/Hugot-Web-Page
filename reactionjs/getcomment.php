<?php
// getcomment.php
header("Access-Control-Allow-Origin: *"); // Allows requests from any origin
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
$host = "localhost";
$dbname = "dbpost";
$user = "root";
$pass = "";

$post_id = $_GET['post_id'] ?? null;

if (!$post_id) {
    echo json_encode(['status' => 'error', 'message' => 'Post ID required']);
    exit;
}

// Connect to your database
$pdo = new PDO('mysql:host=localhost;dbname=your_db', 'username', 'password');
$sql = 'SELECT * FROM comments WHERE post_id = :post_id';
$stmt = $pdo->prepare($sql);
$stmt->execute(['post_id' => $post_id]);

$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($comments);
?>
