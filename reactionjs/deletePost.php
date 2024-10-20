<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

include 'connection-pdo.php'; // Ensure this file contains the correct PDO connection setup

$data = json_decode(file_get_contents('php://input'), true);
$post_id = isset($data['post_id']) ? $data['post_id'] : null;

if ($post_id) {
    try {
        // Start a transaction
        $conn->beginTransaction();

        // Delete all comments related to the post
        $deleteCommentsSql = "DELETE FROM tblcomments WHERE post_id = :post_id";
        $stmt = $conn->prepare($deleteCommentsSql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->execute();

        // Delete the post
        $deletePostSql = "DELETE FROM tblposts WHERE post_id = :post_id";
        $stmt = $conn->prepare($deletePostSql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        // Rollback the transaction if there was an error
        $conn->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
?>
