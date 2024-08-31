<?php
session_start();
require 'config.php'; 

if (!isset($_POST['questionId']) || !isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

$questionId = intval($_POST['questionId']);
$action = $_POST['action'];

// Validate action
if (!in_array($action, ['like', 'unlike', 'dislike', 'undislike'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit();
}

// Initialize variables for the update
$likeChange = 0;
$dislikeChange = 0;

// Determine the update based on action
if ($action == 'like') {
    $likeChange = 1;
} elseif ($action == 'unlike') {
    $likeChange = -1;
} elseif ($action == 'dislike') {
    $dislikeChange = 1;
} elseif ($action == 'undislike') {
    $dislikeChange = -1;
}

// Update question like/dislike counts
$sql = "UPDATE question SET `like` = `like` + ?, `dislike` = `dislike` + ? WHERE questionid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $likeChange, $dislikeChange, $questionId);
$stmt->execute();

echo json_encode(['success' => true]);
