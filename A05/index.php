<?php
include('connect.php');
?>

<!DOCTYPE html>
<html>
<head>
  <title>Posts</title>
</head>
<body>
  <h1>Posts</h1>
  <table border="1">
    <tr>
      <th>Post ID</th>
      <th>User ID</th>
      <th>Content</th>
    </tr>

    <?php
    $query = "SELECT postsID, userID, content FROM posts";
    $result = executeQuery($query);

    if(mysqli_num_rows($result) > 0){
      while($row = mysqli_fetch_assoc($result)){
        echo "<tr>";
        echo "<td>" . $row['postID'] . "</td>";
        echo "<td>" . $row['userID'] . "</td>";
        echo "<td>" . $row['content'] . "</td>";
        echo "</tr>";
      }
    } else {
      echo "<tr><td colspan='3'>No posts found.</td></tr>";
    }
    ?>
  </table>
</body>
</html>
