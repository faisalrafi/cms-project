<?php
global $conn;
if (isset($_GET['edit_user'])){

   $the_user_id = $_GET['edit_user'];

$query = "SELECT * FROM users WHERE user_id=$the_user_id ";
$select_users_query = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($select_users_query)) {
    $user_id = $row['user_id'];
    $username = $row['username'];
    $user_password = $row['user_password'];
    $user_firstname = $row['user_firstname'];
    $user_lastname = $row['user_lastname'];
    $user_email = $row['user_email'];
    $user_image = $row['user_image'];
    $user_role = $row['user_role'];
}

?>
<?php



if (isset($_POST['edit_user']))
{
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_role = $_POST['user_role'];
//    $post_image = $_FILES['image']['name'];
//    $post_image_tmp = $_FILES['image']['tmp_name'];
    $username = $_POST['username'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
//    $post_date = date('d-m-y');

//    move_uploaded_file($post_image_tmp, "../images/$post_image");
    if(!empty($user_password))
    {
        $query_password = "SELECT user_password FROM users WHERE user_id =$the_user_id";
        $get_user = mysqli_query($conn,$query_password);
        confirmQuery($get_user);

        $row = mysqli_fetch_assoc($get_user);
        $db_user_password = $row['user_password'];

        if ($db_user_password != $user_password)
        {
            $hashed_password = password_hash($user_password, PASSWORD_DEFAULT, array('cost'=>12));
        }

        $query = "UPDATE users SET ";
        $query .="user_firstname  = '{$user_firstname}', ";
        $query .="user_lastname = '{$user_lastname}', ";
        $query .="user_role   =  '{$user_role}', ";
        $query .="username = '{$username}', ";
        $query .="user_email = '{$user_email}', ";
        $query .="user_password   = '{$hashed_password}' ";
        $query .= "WHERE user_id = {$the_user_id} ";

        $edit_user_query = mysqli_query($conn,$query);
        confirmQuery($edit_user_query);

        echo "User Updated: " . " " . "<a href='users.php'>View Users</a>";
    }

}

}else{
    header("Location: index.php");
}

?>


<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="author">Firstname</label>
        <input value="<?php echo $user_firstname ?>" type="text" class="form-control" name="user_firstname" />
    </div>
    <div class="form-group">
        <label for="post_status">Lastname</label>
        <input value="<?php echo $user_lastname ?>" type="text" class="form-control" name="user_lastname" />
    </div>
    <div class="form-group">
        <label for="post_status">User role</label>
        <select name="user_role" id="" class="form-control">

            <option value="<?php echo $user_role; ?>"><?php echo $user_role; ?></option>
            <?php

            if ($user_role == 'admin'){
               echo "<option value='subscriber'>subscriber</option>";
            }
            else{
                echo "<option value='admin'>admin</option>";
            }

            ?>


        </select>
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
        <input class="btn btn-success" type="submit" name="edit_user" value="Update User"/>
    </div>

</form>