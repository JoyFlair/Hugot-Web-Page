<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

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

$data = json_decode(file_get_contents('php://input'), true);
$post_id = $data['post_id'];
$reaction_type = $data['reaction_type'];
$user_id = $data['user_id'];

if (!isset($post_id, $reaction_type, $user_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
    exit();
}

try {
    // Insert or update the reaction
    $stmt = $pdo->prepare('
        INSERT INTO tblreactions (post_id, user_id, reaction_type, reaction_date) 
        VALUES (?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE reaction_type = VALUES(reaction_type), reaction_date = NOW()
    ');
    $stmt->execute([$post_id, $user_id, $reaction_type]);

    // Optional: Update the total reactions count in tblposts if needed
    $updateStmt = $pdo->prepare('
        UPDATE tblposts p
        SET p.total_reactions = (
            SELECT COUNT(*) FROM tblreactions r WHERE r.post_id = p.post_id
        )
        WHERE p.post_id = ?
    ');
    $updateStmt->execute([$post_id]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save reaction: ' . $e->getMessage()]);
}
?>
