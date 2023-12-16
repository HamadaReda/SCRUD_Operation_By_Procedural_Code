<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add User</title>
  <link rel="stylesheet" href="css/bootstrap.min.css.map">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <?php

  session_start();
  if (isset($_SESSION['id'])) {
    echo "<div class='alert alert-primary'>Welcome " . $_SESSION['name'] . "</div>";
    echo "<a class='btn btn-primary' href='logout.php'>Logout</a>";
  } else {
    header("Location: login.php");
    exit;
  }

  $error_fields = array();
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!(isset($_POST['name']) && !empty($_POST['name']))) {
      $error_fields[] = "name";
    }
    if (!(isset($_POST['email']) && filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL))) {
      $error_fields[] = "email";
    }
    if (!(isset($_POST['password']) && strlen($_POST['password']) > 5)) {
      $error_fields[] = "password";
    }
    if (!$error_fields) {
      $conn = mysqli_connect('localhost', 'root', '', 'blog');
      // $conn = new mysqli('localhost', 'root', '', 'blog');
      if (!$conn) {
        echo "<div class='alert alert-danger text-center container' role='alert'>";
        echo "Failed Connection: " . mysqli_connect_error();
        echo "</div>";
        exit;
      }
      $name = mysqli_escape_string($conn, $_POST['name']);
      $email = mysqli_escape_string($conn, $_POST['email']);
      $password = sha1($_POST['password']);
      $admin = (isset($_POST['admin'])) ? 1 : 0;
      $uploads_dir = $_SERVER['DOCUMENT_ROOT'] . "/admin/users/uploads";
      $avatar = "";
      if ($_FILES["avatar"]["error"] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["avatar"]["tmp_name"];
        $avatar = basename($_FILES["avatar"]["name"]);
        move_uploaded_file($tmp_name, "$uploads_dir/$name.$avatar");
      } else {
        if ($_FILES["avatar"]["name"] == "") {
          $avatar = "";
        } else {
          echo "<div class='alert alert-danger text-center container' role='alert'>";
          echo "File can't be uploaded";
          echo "</div>";
          exit;
        }
      }
      $query = "INSERT INTO `users` (`name`, `email`, `password`, `avatar`, `admin`) VALUES ('$name', '$email', '$password', '$avatar', '$admin')";
      if (mysqli_query($conn, $query)) {
        header("Location: list.php");
      } else {
        // echo "<div class='alert alert-danger text-center container' role='alert'>";
        // echo "Failed Query: " . mysqli_error($conn);
        // echo "</div>";
        if (mysqli_errno($conn) == 1062) {
          // echo "Error: Duplicate entry for email '$email'";
          $error_fields[] = "duplicate_email";
        } else {
          header("Location: list.php?query_field=" . mysqli_error($conn));
          exit;
        }
      }
    }
  }

  ?>


  <div class="container">
    <form method="POST" enctype="multipart/form-data">
      <div class="bg-light p-4 mt-3">
        <div class="mb-3">
          <label for="name" class="form-label fw-bold">Name</label>
          <input type="text" class="form-control" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name'] ?>"><?php if (in_array("name", $error_fields)) echo "<p class='text-danger'>* Please enter your name</p>" ?>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label fw-bold">Email</label>
          <input type="text" class="form-control" name="email" value="<?php if (isset($_POST['email'])) echo $_POST['email'] ?>"><?php if (in_array("email", $error_fields))
                                                                                                                                    echo "<p class='text-danger'>* Please enter a valid email</p>";
                                                                                                                                  if (in_array("duplicate_email", $error_fields))
                                                                                                                                    echo "<p class='text-danger'>* This email is exist, please enter another email</p>"; ?>
          <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label fw-bold">Password</label>
          <input type="password" class="form-control" aria-describedby="emailHelp" name="password" value="<?php if (isset($_POST['password'])) echo $_POST['password'] ?>"><?php if (in_array("password", $error_fields)) echo "<p class='text-danger'>* Please enter a password not than 6 characters</p>" ?>
        </div>
        <div class="mb-3">
          <label for="avatar" class="form-label fw-bold">Select Avatar</label>
          <input class="form-control" type="file" name="avatar" id="avatar">
        </div>
        <div class="mb-3">
          <div class="form-check">
            <input type="checkbox" class="form-check-input" name="admin">
            <label for="admin" class="form-check-label">Admin</label>
          </div>
        </div>
        <input type="submit" class="btn btn-primary" name="submit" value="Add User">
      </div>
    </form>
  </div>


  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/bootstrap.bundle.min.js.map"></script>
  <script src="js/all.min.js"></script>
</body>

</html>