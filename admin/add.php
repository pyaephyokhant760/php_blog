<?php
session_start();
require '../config/config.php';
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
if($_POST) {
    $file = 'image/'.($_FILES['image']['name']);
    $imageType = pathinfo($file,PATHINFO_EXTENSION);

    if($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
      echo "<script>alert('Image must br png,jpg,jpeg')</script>";
    }else {
      $title = $_POST['title'];
      $content = $_POST['content'];
      $image = $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'],$file);

      $stmt = $conn->prepare('INSERT INTO posts(title, content, image, auther_id) VALUES (:title, :content, :image, :auther_id)');
    $result = $stmt->execute([
      ':title' => $title,
      ':content' => $content,
      ':image' => $image,
      ':auther_id' => $_SESSION['user_id']
  ]);


      if($result) {
        echo "<script>alert('Success');window.location.href='index.php';</script>";
      }
    }
}
?>
<?php include ('header.html') ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
              <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="">Title</label>
                    <input type="text" name="title" id="" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="">Content</label>
                    <textarea name="content" id="" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="">Image</label><br>
                    <input type="file" name="image" id="" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Submit" class="btn btn-danger">
                    <a href="index.php" class="btn btn-danger">Back</a>
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
  <?php include ('footer.html') ?>
  
  