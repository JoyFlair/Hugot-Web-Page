<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbpost";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Get POST data
$post_id = $_POST['post_id'];
$user_id = $_POST['user_id']; // Include user_id in the POST data
$reaction_type = $_POST['reaction_type'];
$increment = (int)$_POST['increment']; // +1 or -1

// Validate input
if (!in_array($reaction_type, ['like', 'heart', 'care'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid reaction type']);
    exit();
}

// Start transaction
$conn->begin_transaction();

try {
    // Check if the user has already reacted to this post
    $sql = "SELECT reaction_id FROM tblreactions WHERE post_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $stmt->store_result();
    $already_reacted = $stmt->num_rows > 0;

    if ($already_reacted) {
        // Update the existing reaction
        $sql = "UPDATE tblreactions SET reaction_type = ?, reaction_date = NOW() WHERE post_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $reaction_type, $post_id, $user_id);
    } else {
        // Insert a new reaction
        $sql = "INSERT INTO tblreactions (post_id, user_id, reaction_type, reaction_date) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $post_id, $user_id, $reaction_type);
    }
    $stmt->execute();

    // Update the total number of reactions in tblposts
    $sql = "UPDATE tblposts SET total_reactions = total_reactions + ? WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $increment, $post_id);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    echo json_encode(['status' => 'success', 'message' => 'Reactions updated successfully']);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => 'Failed to update reactions: ' . $e->getMessage()]);
}

// Close connection
$conn->close();
?>
