<?php
session_start();
require '../config/config.php';
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}

if($_POST) {
    $id = $_POST["id"];
    $title = $_POST['title'];
    $content = $_POST['content'];

    if($_FILES['image']['name'] != null) {
      $file = 'image/'.($_FILES['image']['name']);
      $imageType = pathinfo($file,PATHINFO_EXTENSION);

      if($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
      echo "<script>alert('Image must br png,jpg,jpeg')</script>";
      }else {
          $image = $_FILES['image']['name'];
          move_uploaded_file($_FILES['image']['tmp_name'],$file);

          $stmt = $conn->prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id='$id'");
          $result = $stmt->execute();
          if($result) {
            echo "<script>alert('Success');window.location.href='index.php';</script>";
          }
      }
    }else{
      $stmt = $conn->prepare("UPDATE posts SET title='$title',content='$content' WHERE id='$id'");
      $result = $stmt->execute();
      if($result) {
        echo "<script>alert('Success');window.location.href='index.php';</script>";
      }
    }
}

$stmt = $conn->prepare("SELECT * FROM posts WHERE id=" . $_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_DEFAULT);


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
                <input type="hidden" name="id" value="<?php echo $result[0]['id']; ?>">
                <div class="form-group">
                    <label for="">Title</label>
                    <input type="text" name="title" value="<?php echo $result[0]['title']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="">Content</label>
                    <textarea name="content" id="" class="form-control" required><?php echo $result[0]['content']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="">Image</label><br>
                    <img src="image/<?php echo $result[0]['image']; ?>" width="150px" height="150px">
                    <input type="file" name="image" id="">
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
  
  