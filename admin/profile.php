<?php include "includes/admin_header.php"; ?>

<?php
global $conn;
if (isset($_SESSION['username']))
{
    $username = $_SESSION['username'];
    $query = "SELECT * FROM users WHERE username = '{$username}' ";
    $select_user_profile = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($select_user_profile)) {
        $user_id = $row['user_id'];
        $username = $row['username'];
        $user_password = $row['user_password'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_image = $row['user_image'];
        $user_role = $row['user_role'];
    }
}

?>

<?php

if (isset($_POST['update_profile']))
{
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
//    $post_image = $_FILES['image']['name'];
//    $post_image_tmp = $_FILES['image']['tmp_name'];
    $username = $_POST['username'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
//    $post_date = date('d-m-y');

//    move_uploaded_file($post_image_tmp, "../images/$post_image");

    $query = "UPDATE users SET ";
    $query .="user_firstname  = '{$user_firstname}', ";
    $query .="user_lastname = '{$user_lastname}', ";
    $query .="username = '{$username}', ";
    $query .="user_email = '{$user_email}', ";
    $query .="user_password   = '{$user_password}' ";
    $query .= "WHERE username = '{$username}' ";

    $edit_user_query = mysqli_query($conn,$query);
    confirmQuery($edit_user_query);
}

?>

    <div id="wrapper">

    <!-- Navigation -->
    <?php include "includes/admin_navigation.php"; ?>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Welcome To Admin
                        <small>Author</small>
                    </h1>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="author">Firstname</label>
                            <input value="<?php echo $user_firstname ?>" type="text" class="form-control" name="user_firstname" />
                        </div>
                        <div class="form-group">
                            <label for="post_status">Lastname</label>
                            <input value="<?php echo $user_lastname ?>" type="text" class="form-control" name="user_lastname" />
                        </div>
                        <!--    <div class="form-group">-->
                        <!--        <label for="post_image">Post Image</label>-->
                        <!--        <input type="file" class="form-control" name="image" />-->
                        <!--    </div>-->
                        <div class="form-group">
                            <label for="post_tags">Username</label>
                            <input value="<?php echo $username ?>" type="text" class="form-control" name="username" />
                        </div>
                        <div class="form-group">
                            <label for="post_content">Email</label>
                            <input value="<?php echo $user_email ?>" type="email" class="form-control" name="user_email" />
                        </div>
                        <div class="form-group">
                            <label for="post_content">Password</label>
                            <input autocomplete="off" value="" type="password" class="form-control" name="user_password" />
                        </div>
                        <div class="form-group">
                            <input class="btn btn-success" type="submit" name="update_profile" value="Update Profile"/>
                        </div>

                    </form>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->

<?php include "includes/admin_footer.php"; ?>