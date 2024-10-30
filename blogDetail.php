<?php
session_start();
require 'config/config.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
if($_SESSION['role'] != 0) {
  header('Location: login.php');
}

if (isset($_GET['id'])) {
  $blog_id = $_GET['id'];

  // Fetch post
  $stmt = $conn->prepare("SELECT * FROM posts WHERE id = :id");
  $stmt->execute([':id' => $blog_id]);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Fetch comments
  $stmtcmt = $conn->prepare("SELECT * FROM comment WHERE post_id = :post_id");
  $stmtcmt->execute([':post_id' => $blog_id]);
  $resultcmt = $stmtcmt->fetchAll(PDO::FETCH_ASSOC);


  // if (!empty($resultcmt)) {
  //   $autherId = $resultcmt[0]['auther_id'];

  //   // Fetch author
  //   $stmtau = $conn->prepare("SELECT * FROM users WHERE id = :id");
  //   $stmtau->execute([':id' => $autherId]);
  //   $resultau = $stmtau->fetchAll(PDO::FETCH_ASSOC);
  // }
}

if ($_POST) {
  $comment = $_POST['comment'];

  // Insert new comment
  $stmt = $conn->prepare('INSERT INTO comment (content, auther_id, post_id) VALUES (:content, :auther_id, :post_id)');
  $result = $stmt->execute([
    ':content' => $comment,
    ':auther_id' => $_SESSION['user_id'],
    ':post_id' => $blog_id,
  ]);

  if ($result) {
    // Redirect to the blog detail page
    header('Location: blogDetail.php?id=' . $blog_id);
    exit();
  }
}
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Widgets</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
  <div class="">


    <!-- Content Wrapper. Contains page content -->
    <div class="">
      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div class="container-fluid">
                <a href="index.php" class="btn-default">Back</a>
                  <h1 style="text-align: center;"><?php echo $result[0]['title']; ?></h1>
                </div>
              </div>
             
              <!-- /.card-header -->
              <div class="card-body">
                <img class="img-fluid" src="admin/image/<?php echo $result[0]['image']; ?>" alt="Photo"><br>

                <p><?php echo $result[0]['content']; ?></p>
                <h3>Comment</h3><br>
              </div>

              <!-- /.card-body -->
              <div class="card-footer card-comments">
                <?php foreach ($resultcmt as $comment): ?>
                  <div class="card-comment">
                    <!-- User image -->
                    <div class="comment-text" style="margin-left:0px !important">
                      <span class="username">
                        <?php
                        // Fetch author name for each comment
                        $stmtau = $conn->prepare("SELECT name FROM users WHERE id = :id");
                        $stmtau->execute([':id' => $comment['auther_id']]);
                        $author = $stmtau->fetch(PDO::FETCH_ASSOC);
                        echo $author['name'];
                        ?>
                        <span class="text-muted float-right"><?php echo $comment['created_at']; ?></span>
                      </span>
                      <?php echo $comment['content']; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>

              <!-- /.card-footer -->
              <div class="card-footer">
                <form action="" method="post">
                  <!-- .img-push is used to add margin to elements next to floating images -->
                  <div class="img-push">
                    <input type="text" class="form-control form-control-sm" placeholder="Press enter to post comment" name="comment">
                  </div>
                </form>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->

        </div>
      </section>
      <!-- /.content -->

      <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
        <i class="fas fa-chevron-up"></i>
      </a>
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer" style="margin-left: 0px !important;">
      <div class="float-right d-none d-sm-block">
        <b>Version</b> 3.0.5
      </div>
      <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
      reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
</body>

</html>