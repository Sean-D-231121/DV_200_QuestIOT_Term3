<?php
require 'config.php'; 

if (!isset($_GET['questionId'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

$questionId = intval($_GET['questionId']);

$sql = "SELECT `like`, `dislike` FROM question WHERE questionid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $questionId);
$stmt->execute();
$stmt->bind_result($likeCount, $dislikeCount);
$stmt->fetch();

echo json_encode(['success' => true, 'likeCount' => $likeCount, 'dislikeCount' => $dislikeCount]);
