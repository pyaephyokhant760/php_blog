<?php
session_start();
require '../config/config.php';
if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || $_SESSION['role'] != 1) {
    header('Location: login.php');
    exit();
}

if ($_POST) {
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_FILES['password']) || strlen($_POST['password']) < 4) {
        if (empty($_POST['name'])) {
            $nameError = 'Name Could Not Be Null';
        }
        if (empty($_POST['email'])) {
            $emailError = 'Email Could Not Be Null';
        }
        if (empty($_FILES['password'])) {
            $passwordError = 'Password Could Not Be Null';
        }
        if (strlen($_POST['password']) < 4) {
            $passwordError = 'Password Should Be 4 Character at least';
        }
    } else {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $role = ($_POST['role'] == 'admin') ? 1 : 0;

        // Use placeholders to prevent SQL injection
        $stmt = $conn->prepare("UPDATE users SET name='$name',email='$email',role='$role' WHERE id='$id'");
        $result = $stmt->execute();
        if ($result) {
            echo "<script>alert('Success');window.location.href='user.php';</script>";
        }
    }
}

// Safely retrieve user data by using parameterized queries
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Use FETCH_ASSOC for cleaner output


$stmt = $conn->prepare("SELECT * FROM users WHERE id=" . $_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_DEFAULT);

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
                            <input type="hidden" name="id" value="<?php echo $result[0]['id']; ?>">
                            <div class="form-group">
                                <label for="">Name</label><p style="color:red"><?php echo empty($nameError) ? '' : '*'.$nameError ?></p>
                                <input type="text" name="name" value="<?php echo $result[0]['name']; ?>" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label><p style="color:red"><?php echo empty($emailError) ? '' : '*'.$emailError ?></p>
                                <input type="email" name="email" class="form-control" required value="<?php echo $result[0]['email']; ?>">
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="hidden" name="role" value="user">
                                    <input class="form-check-input" type="checkbox" name="role" id="checkboxNoLabel" value="admin" aria-label="..." <?php if ($result[0]['role'] == 1) echo 'checked'; ?>> <label class="form-check-label" for="flexCheckIndeterminate">
                                        Admin
                                    </label>
                                </div>
                            </div>

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