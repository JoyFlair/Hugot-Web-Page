<?php
header("Access-Control-Allow-Origin: *"); // Allows requests from any origin
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
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

$stmt = $pdo->prepare('
    SELECT p.*, u.full_name, (
        SELECT COUNT(*) 
        FROM tblcomments c 
        WHERE c.post_id = p.post_id
    ) AS comment_count
    FROM tblposts p
    LEFT JOIN tblusers u ON p.user_id = u.user_id
    ORDER BY p.post_date DESC
');

try {
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch comments for each post
    foreach ($posts as &$post) {
        $post_id = $post['post_id'];
        $commentStmt = $pdo->prepare('
            SELECT c.comment_id, c.comment, u.full_name
            FROM tblcomments c
            LEFT JOIN tblusers u ON c.user_id = u.user_id
            WHERE c.post_id = ?
            ORDER BY c.comment_date DESC
        ');
        $commentStmt->execute([$post_id]);
        $post['comments'] = $commentStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode(['status' => 'success', 'posts' => $posts]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch posts: ' . $e->getMessage()]);
}
?>
