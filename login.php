<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="css/bootstrap.min.css.map">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="container">



    <?php

    session_start();
    $error;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $conn = mysqli_connect('localhost', 'root', '', 'blog');
      if (!$conn) {
        echo "<div class='alert alert-danger text-center' >";
        echo "Connection Failed: " . mysqli_connect_error();
        echo "</div>";
        exit;
      }
      $email = mysqli_escape_string($conn, $_POST['email']);
      $password = sha1($_POST['password']);
      $query = "SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password' LIMIT 1";
      $result = mysqli_query($conn, $query);
      if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        header("Location: list.php");
        exit;
      } else {
        $error = "Invalid email or password";
      }
    }

    ?>



    <form action="" method="POST" class="">
      <div class="bg-light p-4 mt-3">
        <div class="mb-3">
          <label for="email" class="form-label fw-bold">Enter Email</label>
          <input type="text" name="email" class="form-control" value="<?php if (isset($_POST['email'])) echo $_POST['email'] ?>">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label fw-bold">Enter Password</label>
          <input type="password" name="password" class="form-control">
        </div>
        <?php if (isset($error)) echo "<p class='text-danger'>* $error</p>" ?>
        <input type="submit" value="Login" class="btn btn-primary">
      </div>
    </form>




  </div>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/bootstrap.bundle.min.js.map"></script>
  <script src="js/all.min.js"></script>
</body>

</html>