<?php 
session_start(); 
require 'config.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php"); 
    exit(); 
}

$questionid = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
    $submitted_answer = trim($_POST['answer']);

    $stmt = $conn->prepare("INSERT INTO answer (questionid, answer_text, userid) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $questionid, $submitted_answer, $_SESSION['userid']); 
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like']) && isset($_POST['dislike'])) {
    $action = $_POST['action'];
    $stmt = $conn->prepare("UPDATE question SET `like` = `like` + (CASE WHEN ? = 'like' THEN 1 ELSE 0 END), dislike = dislike + (CASE WHEN ? = 'dislike' THEN 1 ELSE 0 END) WHERE questionid = ?");
    $stmt->bind_param("ssi", $action, $action, $questionid);
    $stmt->execute();
    $stmt->close();
    header("Location: ".$_SERVER['PHP_SELF']."?id=".$questionid); // Refresh to update like/dislike count
    exit();
}

if ($questionid > 0) {
    $sql = "SELECT q.heading, q.question_text, u.username, u.userImage, q.created_at, q.`like`, q.dislike
            FROM question q 
            JOIN users u ON q.userid = u.userid
            WHERE q.questionid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $questionid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $error = "Question not found.";
    }
    $stmt->close();
} else {
    $error = "Invalid question ID.";
}

$product_exists = false;
$productID = 0;
if ($questionid > 0) {
    $sql = "SELECT ProductID FROM product WHERE QuestionID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $questionid);
    $stmt->execute();
    $stmt->bind_result($productID);
    if ($stmt->fetch()) {
        $product_exists = true;
    }
    $stmt->close();
}

// Fetch all answers for the question
$sql = "SELECT a.answer_text, u.username, u.userImage, a.created_at 
        FROM answer a 
        JOIN users u ON a.userid = u.userid
        WHERE a.questionid = ? ORDER BY a.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $questionid);
$stmt->execute();
$answers_result = $stmt->get_result();

$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Question Detail</title>
    <link rel="icon" href="../assets/secondary_logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../css/main.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/318f00e1f3.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <?php include '../components/navbar.php'; ?>

    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1>Question Detail</h1>
        </div>

        <?php if (isset($error)): ?>
            <p class="text-danger text-center"><?php echo htmlspecialchars($error); ?></p>
        <?php else: ?>
            <div class="card col-8 mx-auto mb-4 post-background">
                <div class="card-body">
                    <div class="row">
                        <div class="col-1">
                            <img src="../uploads/userImages/<?php echo htmlspecialchars($row['userImage']); ?>" alt="User Image" class="rounded-circle" style="width: 50px; height: 50px;">
                        </div>
                        <div class="col-2">
                            <p><?php echo htmlspecialchars($row['username']); ?></p>
                        </div>

                        <?php if ($product_exists): ?>
                            <div class="col text-end">
                                <a href="singleproductpage.php?id=<?php echo htmlspecialchars($productID); ?>" class="btn btn-info">View Related Product</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h5 class="card-title"><?php echo htmlspecialchars($row['heading']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($row['question_text']); ?></p>
                    <p class="card-text"><small class="text-muted">Posted on <?php echo htmlspecialchars($row['created_at']); ?></small></p>
                    <div class="row">
                        <div class="col-1">
                            <i class="fa-regular fa-thumbs-up like-toggle" data-id="<?php echo htmlspecialchars($questionid); ?>"></i> 
                            <span class="like-count"><?php echo htmlspecialchars($row['like']); ?></span>
                        </div>
                        <div class="col-1">
                            <i class="fa-regular fa-thumbs-down dislike-toggle" data-id="<?php echo htmlspecialchars($questionid); ?>"></i> 
                            <span class="dislike-count"><?php echo htmlspecialchars($row['dislike']); ?></span>
                        </div>
                        <div class="col-1">
                            <i class="fa-solid fa-share-from-square"></i>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="text-center mb-4">
                <h1>Answer</h1>
            </div>

            <form action="" method="POST" class="col-8 mx-auto">
                <div class="mb-3">
                    <label for="answer" class="form-label">Your Answer</label>
                    <textarea class="form-control" id="answer" name="answer" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Answer</button>
            </form>

            <!-- Display Answers -->
            <?php if ($answers_result->num_rows > 0): ?>
                <?php while ($answer = $answers_result->fetch_assoc()): ?>
                    <div class="card mt-4 post-background">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-1">
                                    <img src="../uploads/userImages/<?php echo htmlspecialchars($answer['userImage']); ?>" alt="User Image" class="img-thumbnail rounded-circle" style="width: 50px; height: 50px;">
                                </div>
                                <div class="col-2">
                                    <p><?php echo htmlspecialchars($answer['username']); ?> answered:</p>
                                </div>
                            </div>
                            <p><?php echo htmlspecialchars($answer['answer_text']); ?></p>
                            <p><small class="text-muted">Answered on <?php echo htmlspecialchars($answer['created_at']); ?></small></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>

        <?php endif; ?>
    </div>
    
    <script>
        $(document).ready(function() {
            $('.like-toggle').click(function() {
                var questionId = $(this).data('id');
                var $likeCount = $(this).next('.like-count');
                var $dislikeIcon = $('.dislike-toggle[data-id="' + questionId + '"]');
                
                if ($(this).hasClass('fa-regular')) {
                    $(this).removeClass('fa-regular').addClass('fa-solid');
                    $likeCount.text(parseInt($likeCount.text()) + 1);
                    
                    $.post('update_reactions.php', { questionId: questionId, action: 'like' }, function(response) {
                        if (!response.success) {
                            alert(response.message);
                        }
                    }, 'json');
                    
                    if ($dislikeIcon.hasClass('fa-solid')) {
                        $dislikeIcon.removeClass('fa-solid').addClass('fa-regular');
                        $.post('update_reactions.php', { questionId: questionId, action: 'undislike' }, function(response) {
                            if (!response.success) {
                                alert(response.message);
                            }
                        }, 'json');
                        $('.dislike-count').text(parseInt($('.dislike-count').text()) - 1);
                    }
                } else {
                    $(this).removeClass('fa-solid').addClass('fa-regular');
                    $likeCount.text(parseInt($likeCount.text()) - 1);
                    
                    $.post('update_reactions.php', { questionId: questionId, action: 'unlike' }, function(response) {
                        if (!response.success) {
                            alert(response.message);
                        }
                    }, 'json');
                }
            });
            
            $('.dislike-toggle').click(function() {
                var questionId = $(this).data('id');
                var $dislikeCount = $(this).next('.dislike-count');
                var $likeIcon = $('.like-toggle[data-id="' + questionId + '"]');
                
                if ($(this).hasClass('fa-regular')) {
                    $(this).removeClass('fa-regular').addClass('fa-solid');
                    $dislikeCount.text(parseInt($dislikeCount.text()) + 1);
                    
                    $.post('update_reactions.php', { questionId: questionId, action: 'dislike' }, function(response) {
                        if (!response.success) {
                            alert(response.message);
                        }
                    }, 'json');
                    
                    if ($likeIcon.hasClass('fa-solid')) {
                        $likeIcon.removeClass('fa-solid').addClass('fa-regular');
                        $.post('update_reactions.php', { questionId: questionId, action: 'unlike' }, function(response) {
                            if (!response.success) {
                                alert(response.message);
                            }
                        }, 'json');
                        $('.like-count').text(parseInt($('.like-count').text()) - 1);
                    }
                } else {
                    $(this).removeClass('fa-solid').addClass('fa-regular');
                    $dislikeCount.text(parseInt($dislikeCount.text()) - 1);
                    
                    $.post('update_reactions.php', { questionId: questionId, action: 'undislike' }, function(response) {
                        if (!response.success) {
                            alert(response.message);
                        }
                    }, 'json');
                }
            });
        });
    </script>
</body>
</html>
