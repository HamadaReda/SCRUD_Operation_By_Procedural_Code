<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete User</title>
  <link rel="stylesheet" href="css/bootstrap.min.css.map">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>


  <?php

  $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
  $conn = mysqli_connect('localhost', 'root', '', 'blog');
  if (!$conn) {
    echo "<div class='alert alert-danger text-center container' role='alert'>";
    echo "Failed Connection: " . mysqli_connect_error();
    echo "</div>";
    exit;
  }

  $query = "DELETE FROM `users` WHERE `id` = " . $id;
  $result = mysqli_query($conn, $query);
  if ($result) {
    header("Location: list.php");
    exit;
  } else {
    // echo "<div class='alert alert-danger text-center container' role='alert'>";
    // echo "Query Failed: " . mysqli_error($conn);
    // echo "</div>";
    header("Location: list.php?query_field=" . mysqli_error($conn));
    exit;
  }

  mysqli_close($conn);
  ?>



  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/bootstrap.bundle.min.js.map"></script>
  <script src="js/all.min.js"></script>
</body>

</html>