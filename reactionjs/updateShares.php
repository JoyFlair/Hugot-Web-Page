<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
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

// Retrieve post_id from the request
$data = json_decode(file_get_contents('php://input'), true);
$post_id = $data['post_id'];

// Update the total_shares field
$stmt = $pdo->prepare('
    UPDATE tblposts
    SET total_shares = total_shares + 1
    WHERE post_id = ?
');

try {
    $stmt->execute([$post_id]);
    echo json_encode(['status' => 'success', 'message' => 'Share updated']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update share: ' . $e->getMessage()]);
}
?>
