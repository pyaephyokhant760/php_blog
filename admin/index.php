<?php
session_start();
require '../config/config.php';
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
?>
<?php include ('header.html') ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Bordered Table</h3>
              </div>
              <?php
              $stmt = $conn->prepare("SELECT * FROM posts ORDER BY id DESC");
              $stmt->execute();
              $result = $stmt->fetchAll(PDO::FETCH_DEFAULT);
              
              ?>
              <!-- /.card-header -->
              <div class="card-body">
                <div>
                  <a href="add.php" type="button" class="btn btn-success">New Blog Post</a>
                </div><br>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Title</th>
                      <th>Content</th>
                      <th style="width: 40px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                   
                   <?php
                   $i=0;
                   if($result) {
                    foreach($result as $data) {?>
                       <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $data['title']; ?></td>
                      <td><?php echo substr($data['content'],0,50); ?></td>
                      <td>
                       <div class="btn-group">
                        <div class="container">
                          <a href="edit.php?id=<?php echo $data['id']; ?>" type="button" class="btn btn-danger">Edit</a>
                         </div>
                          <div class="container">
                            <a href="delete.php?id=<?php echo $data['id']; ?>" type="button" class="btn btn-warning">Delete</a>
                          </div>
                       </div>
                      </td>
                    </tr>
                    <?php
                    $i++;
                    }
                   }
                   
                   ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <?php include ('footer.html') ?>
  
  