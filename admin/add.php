<?php
session_start();
require '../config/config.php';
if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || $_SESSION['role'] != 1) {
  header('Location: login.php');
  exit();
}

if ($_POST) {
  if (empty($_POST['title']) || empty($_POST['content']) || empty($_FILES['image'])) {
    if (empty($_POST['title'])) {
        $titleError = 'Title Could Not Be Null';
    }
    if (empty($_POST['content'])) {
        $contentError = 'Content Could Not Be Null';
    }
    if (empty($_FILES['image'])) {
        $imageError = 'Image Could Not Be Null';
    }
  } else {
    $file = 'image/' . ($_FILES['image']['name']);
    $imageType = pathinfo($file, PATHINFO_EXTENSION);

    if ($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
      echo "<script>alert('Image must br png,jpg,jpeg')</script>";
    } else {
      $title = $_POST['title'];
      $content = $_POST['content'];
      $image = $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'], $file);

      $stmt = $conn->prepare('INSERT INTO posts(title, content, image, auther_id) VALUES (:title, :content, :image, :auther_id)');
      $result = $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':image' => $image,
        ':auther_id' => $_SESSION['user_id']
      ]);


      if ($result) {
        echo "<script>alert('Success');window.location.href='index.php';</script>";
      }
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
            <form action="" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="title">Title</label>
                <p style="color:red"><?php echo empty($titleError) ? '' : '*'.$titleError ?></p>
                <input type="text" name="title" id="title" class="form-control">
              </div>

              <div class="form-group">
                <label for="">Content</label><p style="color:red"><?php echo empty($contentError) ? '' : '*'.$contentError ?></p>
                <textarea name="content" id="" class="form-control"></textarea>
              </div>
              <div class="form-group">
                <label for="">Image</label><br><p style="color:red"><?php echo empty($imageError) ? '' : '*'.$imageError ?></p>
                <input type="file" name="image" id="" >
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
<?php include('footer.html') ?>