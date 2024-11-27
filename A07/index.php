<?php
include('connect.php');

// Handle Post Submission
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

// Handle Delete Request
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

// Handle Edit Request
if (isset($_GET['edit'])) {
    $post_id = $_GET['edit'];
    $query = "SELECT content FROM posts WHERE postsID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();
}

// Update Post Content
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_post'])) {
    $post_id = $_POST['post_id'];
    $content = $_POST['content'];

    $query = "UPDATE posts SET content = ? WHERE postsID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $content, $post_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Post updated successfully.');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Error updating post.');
                window.location.href = 'index.php';
              </script>";
    }
    $stmt->close();
}

// Fetch All Posts
$posts = executeQuery("SELECT postsID, userID, content FROM posts");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Feed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .card {
            border-radius: 8px;
        }
        .card-title {
            font-size: 1.2rem;
        }
        .card-body {
            padding: 20px;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="mb-4 text-center">Social Feed</h1>

    <!-- Post Form -->
    <form action="" method="POST" class="mb-4">
        <div class="mb-3">
            <textarea name="content" class="form-control" placeholder="What's on your mind?" required></textarea>
        </div>
        <button type="submit" name="new_post" class="btn btn-custom">Add Post</button>
    </form>

    <!-- Display Posts -->
    <?php if (mysqli_num_rows($posts) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($posts)): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">User <?php echo $row['userID']; ?></h5>
                    <p class="card-text"><?php echo $row['content']; ?></p>
                    <a href="?edit=<?php echo $row['postsID']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="?delete=<?php echo $row['postsID']; ?>" class="btn btn-danger btn-sm">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center">No posts yet. Be the first to post!</p>
    <?php endif; ?>

    <!-- Edit Post Form -->
    <?php if (isset($post)): ?>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Edit Post</h5>
                <form action="" method="POST">
                    <div class="mb-3">
                        <textarea name="content" class="form-control" required><?php echo $post['content']; ?></textarea>
                    </div>
                    <input type="hidden" name="post_id" value="<?php echo $_GET['edit']; ?>">
                    <button type="submit" name="edit_post" class="btn btn-custom">Update Post</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
