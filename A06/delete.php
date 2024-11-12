<?php
include('connect.php');

if (isset($_GET['id'])) {
  $postId = $_GET['id'];

  $query = "DELETE FROM posts WHERE postsID = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $postId);

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
$conn->close();
?>
