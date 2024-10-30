<?php
session_start();
require '../config/config.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
if ($_POST) {
  // print_r($_POST);
  // exit();
  $id = $_POST['id'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  if ($_POST['role'] == 'admin') {
    $role = 1;
  } else {
    $role = 0;
  }
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND id!= :id");
  $stmt->execute(array(':email' => $email,':id' => $id));
  $user = $stmt->fetch(PDO::FETCH_ASSOC);



  if ($user) {
    echo "<script>alert('Already Email')</script>";
  } else {
    $stmt = $conn->prepare('INSERT INTO users(name, email,role,password) VALUES (:name, :email, :role, :password)');
    $result = $stmt->execute([
      ':name' => $name,
      ':email' => $email,
      ':password' => $password,
      ':role' => $role
    ]);

    if ($result) {
      echo "<script>alert('Success');window.location.href='user.php';</script>";
    }
  }

  
}
?>
<?php include('header.php') ?>
<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <form action="" method="post">
              <div class="form-group">
                <label for="">Name</label>
                <input type="hidden" name="id">
                <input type="text" name="name" id="" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="">Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <div class="form-check">
                <input type="hidden" name="role" value="user">
                <input class="form-check-input" type="checkbox" value="admin" id="flexCheckIndeterminate" name="role">
                <label class="form-check-label" for="flexCheckIndeterminate">
                  Admin
                </label>
              </div><br>
              <div class="form-group">
                <input type="submit" value="Submit" class="btn btn-danger">
                <a href="user.php" class="btn btn-danger">Back</a>
              </div>
            </form>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<?php include('footer.html') ?>