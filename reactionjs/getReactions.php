<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
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

$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

if ($post_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid post ID']);
    exit();
}

try {
    $stmt = $pdo->prepare('
        SELECT reaction_type, COUNT(*) as count 
        FROM tblreactions 
        WHERE post_id = ? 
        GROUP BY reaction_type
    ');
    $stmt->execute([$post_id]);
    $reactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $reactionCounts = [
        'like' => 0,
        'heart' => 0,
        'care' => 0
    ];

    foreach ($reactions as $reaction) {
        if (array_key_exists($reaction['reaction_type'], $reactionCounts)) {
            $reactionCounts[$reaction['reaction_type']] = (int)$reaction['count'];
        }
    }

    echo json_encode(['status' => 'success', 'reactions' => $reactionCounts]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $e->getMessage()]);
}
?>
