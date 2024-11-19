<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_post'])) {
    $user_id = 1; 
    $content = $_POST['content'];

    $query = "INSERT INTO posts (userID, content) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $user_id, $content);

    if ($stmt->execute()) {
        echo "<script>
                alert('Post added successfully.');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Error adding post.');
                window.location.href = 'index.php';
              </script>";
    }
    $stmt->close();
}

if (isset($_GET['delete'])) {
    $post_id = $_GET['delete'];

    $query = "DELETE FROM posts WHERE postsID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $post_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Post deleted successfully.');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting post.');
                window.location.href = 'index.php';
              </script>";
    }
    $stmt->close();
}

$posts = executeQuery("SELECT postsID, userID, content FROM posts");

?>

<!DOCTYPE html>
<html>

<head>
    <title>Social Feed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: #f8f9fa;">

    <div class="container mt-5">
        <h1 class="mb-4 text-center">Social Feed</h1>

        <form action="" method="POST" class="mb-4">
            <div class="mb-3">
                <textarea name="content" class="form-control" placeholder="What's on your mind?" required></textarea>
            </div>
            <button type="submit" name="new_post" class="btn btn-primary">Add Post</button>
        </form>

        <?php if (mysqli_num_rows($posts) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($posts)): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">User <?php echo $row['userID']; ?></h5>
                        <p class="card-text"><?php echo $row['content']; ?></p>
                        <a href="?delete=<?php echo $row['postsID']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No posts yet. Be the first to post!</p>
        <?php endif; ?>
    </div>

</body>

</html>
