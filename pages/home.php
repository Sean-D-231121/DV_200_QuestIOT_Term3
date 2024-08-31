<?php 
session_start(); 
require 'config.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_SESSION['userid'])) {
    $username = $_SESSION['username'];
    
    $sql = "SELECT userid FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    
    if ($stmt->execute()) {
        $stmt->bind_result($userid);
        if ($stmt->fetch()) {
            $_SESSION['userid'] = $userid;
        } else {
            echo "User not found.";
            exit();
        }
    } else {
        echo "Error retrieving user ID: " . $conn->error;
        exit();
    }
    $stmt->close();
}

$sql = "SELECT questionid, heading, question_text, username, `like`, dislike, created_at, userImage
        FROM question 
        JOIN users ON question.userid = users.userid
        ORDER BY created_at DESC 
        LIMIT 5";

$result = $conn->query($sql);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Main Feed</title>
    <link rel="icon" href="../assets/secondary_logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/main.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/318f00e1f3.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include '../components/navbar.php'; ?>
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1>Main feed</h1>
        </div>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $questionId = htmlspecialchars($row['questionid']);
                $userImage = htmlspecialchars($row['userImage']);
                // Ensure the image path is correctly set for display
                $imagePath = '../uploads/userImages/' . basename($userImage);

                echo '<div class="card col-8 mx-auto mb-4 post-background">';
                echo '<div class="card-body">';
                echo '<div class="row">';
                echo '<div class="col-1">';
                echo '<img src="' . $imagePath . '" alt="User Image" class="rounded-circle" style="width: 50px; height: 50px;">';
                echo '</div>';
                echo '<div class="col-8">';
                echo '<p>' . htmlspecialchars($row['username']) . '</p>';
                echo '</div>';
                echo '<div class="col-3 text-end">';
                echo '<a href="questionpostpage.php?id=' . $questionId . '" class="btn btn-primary">View Question</a>';
                echo '</div>';
                echo '</div>';
                echo '<h5 class="card-title mt-3">' . htmlspecialchars($row['heading']) . '</h5>';
                echo '<p class="card-text">' . htmlspecialchars($row['question_text']) . '</p>';
                echo '<p class="card-text"><small class="text-muted">Posted on ' . htmlspecialchars($row['created_at']) . '</small></p>';
                echo '<div class="row">';
                echo '<div class="col-1">';
                echo '<i class="fa-regular fa-thumbs-up" id="thumbs-up-' . $questionId . '" onclick="toggleThumbsUp(' . $questionId . ')"></i>';
                echo '<span id="like-count-' . $questionId . '">' . htmlspecialchars($row['like']) . '</span>';
                echo '</div>';
                echo '<div class="col-1">';
                echo '<i class="fa-regular fa-thumbs-down" id="thumbs-down-' . $questionId . '" onclick="toggleThumbsDown(' . $questionId . ')"></i>';
                echo '<span id="dislike-count-' . $questionId . '">' . htmlspecialchars($row['dislike']) . '</span>';
                echo '</div>';
                echo '<div class="col-1">';
                echo '<i class="fa-solid fa-share-from-square"></i>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No questions available.</p>';
        }
        ?>
    </div>

    <script>
    function toggleThumbsUp(questionId) {
        var thumbsUpIcon = document.getElementById('thumbs-up-' + questionId);
        var thumbsDownIcon = document.getElementById('thumbs-down-' + questionId);
        
        var action = thumbsUpIcon.classList.contains('fa-regular') ? 'like' : 'unlike';

        $.post('update_reactions.php', { questionId: questionId, action: action }, function(response) {
            if (response.success) {
                thumbsUpIcon.classList.toggle('fa-regular');
                thumbsUpIcon.classList.toggle('fa-solid');
                thumbsDownIcon.classList.remove('fa-solid');
                thumbsDownIcon.classList.add('fa-regular');
                updateCounts(questionId); // Update counts display if needed
            }
        }, 'json');
    }

    function toggleThumbsDown(questionId) {
        var thumbsDownIcon = document.getElementById('thumbs-down-' + questionId);
        var thumbsUpIcon = document.getElementById('thumbs-up-' + questionId);
        
        var action = thumbsDownIcon.classList.contains('fa-regular') ? 'dislike' : 'undislike';

        $.post('update_reactions.php', { questionId: questionId, action: action }, function(response) {
            if (response.success) {
                thumbsDownIcon.classList.toggle('fa-regular');
                thumbsDownIcon.classList.toggle('fa-solid');
                thumbsUpIcon.classList.remove('fa-solid');
                thumbsUpIcon.classList.add('fa-regular');
                updateCounts(questionId); // Update counts display if needed
            }
        }, 'json');
    }

    function updateCounts(questionId) {
        $.get('get_counts.php', { questionId: questionId }, function(response) {
            if (response.success) {
                document.getElementById('like-count-' + questionId).textContent = response.likeCount;
                document.getElementById('dislike-count-' + questionId).textContent = response.dislikeCount;
            }
        }, 'json');
    }
    </script>
</body>
</html>
