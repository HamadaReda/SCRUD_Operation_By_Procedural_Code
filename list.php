<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Users</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css.map">
  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="container">

    <?php

    session_start();
    if (isset($_SESSION['id'])) {
      echo "<div class='alert alert-primary'>Welcome " . $_SESSION['name'] . "</div>";
      echo "<a class='btn btn-primary' href='logout.php'>Logout</a>";
    } else {
      header("Location: login.php");
      exit;
    }

    $conn = mysqli_connect('localhost', 'root', '', 'blog');
    if (!$conn) {
      echo "<div class='alert alert-danger text-center container' role='alert'>";
      echo "Connection Failed: " . mysqli_connect_error();
      echo "</div>";
      exit;
    }
    $query = "SELECT * FROM `users`";

    if (isset($_GET['search'])) {
      $search = mysqli_escape_string($conn, $_GET['search']);
      $query .= " WHERE `users`.`name` LIKE '%$search%' OR `users`.`email` LIKE '%$search%'";
    }
    $result = mysqli_query($conn, $query);
    if (!$result) {
      echo "<div class='alert alert-danger text-center container' role='alert'>";
      echo "Query Failed: " . mysqli_error($conn);
      echo "</div>";
      exit;
    }

    if (isset($_GET['query_field'])) {
      echo "<div class='alert alert-danger text-center container' role='alert'>";
      echo "Query Failed: " . $_GET['query_field'];
      echo "</div>";
    }

    ?>


    <h1 style="text-align: center;">Users List</h1><br>
    <form action="" method="GET">
      <div class="mb-3 row">
        <div class="col-4 d-flex justify-content-end">
          <label class="fw-bold form-label" for="">Enter Name or Email for Search :</label>
        </div>
        <div class="col-6">
          <input class="form-control" type="text" name="search">
        </div>
        <div class="col-2 d-flex justify-content-start">
          <input class="btn btn-primary" type="submit" value="search">
        </div>
      </div>
    </form>
    <!-- Display a table containing all users -->
    <table class="table table-hover table-dark table-bordered text-center">
      <thead>
        <tr class="fw-bold">
          <td class="p-3">Id</td>
          <td class="p-3">Name</td>
          <td class="p-3">Email</td>
          <td class="p-3">Admin</td>
          <td class="p-3">Avatar</td>
          <td class="p-3">Actions</td>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= ($row['admin']) ? 'Yes' : 'No'; ?></td>
            <td><?php if ($row['avatar']) { ?>
                <img class="avatar" src="uploads/<?= $row['name'] . "." . $row['avatar'] ?>" alt="">
              <?php } else { ?>
                <i class="fa-solid fa-user"></i>
              <?php }  ?>
            </td>
            <td><a class="btn btn-primary" href="edit.php?id=<?= $row['id'] ?>">Edit</a> <a class="btn btn-primary ms-3" href="delete.php?id=<?= $row['id'] ?>">Delete</a></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
      <tfoot>
        <tr>
          <td class="fw-bold" colspan="5"><?= mysqli_num_rows($result) ?> Users</td>
          <td colspan="1"><a class="btn btn-primary" href="add.php">Add User</a></td>
        </tr>
      </tfoot>
    </table>
  </div>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/bootstrap.bundle.min.js.map"></script>
  <script src="js/all.min.js"></script>
</body>

</html>
<?php
// Close the connection
mysqli_free_result($result);
mysqli_close($conn);
