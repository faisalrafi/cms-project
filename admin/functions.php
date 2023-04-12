<?php

function redirect($location){
    header("Location:" . $location);
    exit;

}

function ifItIsMethod($method = null){
    if ($_SERVER['REQUEST_METHOD'] == strtoupper($method)){
        return true;
    }
    return false;
}

function isLoggedIn(){
    if(isset($_SESSION['user_role'])){
        return true;
    }

    return false;
}

function checkIfUserIsLoggedInAndRedirect($redirectLocation=null){
    if (isLoggedIn()){
        redirect($redirectLocation);
    }
}


function escape($string){
    global $conn;
    return mysqli_real_escape_string($conn, trim($string));
}

function users_online()
{
    if(isset($_GET['onlineusers'])) {

        global $conn;

        if(!$conn) {

            session_start();

            include("../includes/db.php");

            $session = session_id();
            $time = time();
            $time_out_in_seconds = 05;
            $time_out = $time - $time_out_in_seconds;

            $query = "SELECT * FROM users_online WHERE session = '$session'";
            $send_query = mysqli_query($conn, $query);
            $count = mysqli_num_rows($send_query);

            if($count == NULL) {

                mysqli_query($conn, "INSERT INTO users_online(session, time) VALUES('$session','$time')");
            } else {
                mysqli_query($conn, "UPDATE users_online SET time = '$time' WHERE session = '$session'");
            }
            $users_online_query =  mysqli_query($conn, "SELECT * FROM users_online WHERE time > '$time_out'");
            $count_user = mysqli_num_rows($users_online_query);
            echo $count_user;
        }
    } // get request isset()
}

users_online();

function confirmQuery($result)
{
    global $conn;
    if (!$result){
        die("QUERY FAILED" . mysqli_error($conn));
    }
}

function insert_categories()
{
    global $conn;
    if (isset($_POST['submit']))
    {
        $cat_title = $_POST['cat_title'];
        if ($cat_title == "" || empty($cat_title)){
            echo "This field should not be empty";
        }else{
            $stmt = mysqli_prepare($conn,"INSERT INTO categories(cat_title) VALUES(?) ");
            mysqli_stmt_bind_param($stmt, 's', $cat_title);
            mysqli_stmt_execute($stmt);

            if(!$stmt){
                die('QUERY FAILED'. mysqli_error($conn));
            }
        }
    }
}

function findAllCategories(){

    global $conn;
    $query = "SELECT * FROM categories ";
    $select_categories = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($select_categories)){
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

        echo "<tr>";
        echo "<td>{$cat_id}</td>";
        echo "<td>{$cat_title}</td>";
        echo "<td><a href='categories.php?delete={$cat_id}'>DELETE</a></td>";
        echo "<td><a href='categories.php?edit={$cat_id}'>EDIT</a></td>";
        echo "</tr>";
    }

}

function deleteCategory(){
    global $conn;
    if (isset($_GET['delete'])){
        $the_cat_id = $_GET['delete'];
        $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id} ";
        $delete_query = mysqli_query($conn, $query);
        header("Location: categories.php");
    }

}

function recordCount($table){
    global $conn;
    $query = "SELECT * FROM posts " . $table;
    $select_all_post = mysqli_query($conn,$query);
    $result = mysqli_num_rows($select_all_post);
    confirmQuery($result);
    return $result;
}

function checkStatus($table ,$column, $status){
    global $conn;
    $query = "SELECT * FROM $table WHERE $column = '$status' ";
    $result = mysqli_query($conn,$query);
    return mysqli_num_rows($result );
}

function checkUserRole($table, $column, $role){
    global $conn;
    $query = "SELECT * FROM $table WHERE $column = '$role' ";
    $select_all_subscribers = mysqli_query($conn,$query);
    return mysqli_num_rows($select_all_subscribers);
}

function is_admin($username){
    global $conn;
    $query = "SELECT user_role FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    confirmQuery($result);
    $row = mysqli_fetch_array($result);
    if($row['user_role'] == 'admin'){
        return true;
    }
    else{
        return false;
    }
}

function username_exists($username){
    global $conn;
    $query = "SELECT username FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    confirmQuery($result);

    if(mysqli_num_rows($result) > 0){
        return true;
    }
    else{
        return false;
    }
}

function email_exists($email){
    global $conn;
    $query = "SELECT user_email FROM users WHERE user_email = '$email'";
    $result = mysqli_query($conn, $query);
    confirmQuery($result);

    if(mysqli_num_rows($result) > 0){
        return true;
    }
    else{
        return false;
    }
}

function register_user($username, $email, $password){

    global $conn;
    $username = mysqli_real_escape_string($conn,$username);
    $email    = mysqli_real_escape_string($conn,$email);
    $password = mysqli_real_escape_string($conn,$password);

    $password = password_hash($password, PASSWORD_DEFAULT, array('cost' => 12));

    $query = "INSERT INTO users (username, user_email, user_password, user_role) ";
    $query .= "VALUES ('{$username}','{$email}','{$password}','subscriber') ";
    $register_user_query = mysqli_query($conn,$query);
    confirmQuery($register_user_query);

//        $message = "Your Registration has been submitted";

}

function login_user($username, $password){
    global $conn;
    $username = trim($username);
    $password = trim($password);

    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    $query = "SELECT * FROM users WHERE username = '{$username}' ";
    $select_user_query = mysqli_query($conn, $query);
    if (!$select_user_query){
        die('QUERY FAILED' . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($select_user_query)){
        $db_user_id = $row['user_id'];
        $db_username = $row['username'];
        $db_user_password = $row['user_password'];
        $db_user_firstname = $row['user_firstname'];
        $db_user_lastname = $row['user_lastname'];
        $db_user_role = $row['user_role'];

        if (password_verify($password, $db_user_password))

        {
            $_SESSION['username'] = $db_username;
            $_SESSION['firstname'] = $db_user_firstname;
            $_SESSION['lastname'] = $db_user_lastname;
            $_SESSION['user_role'] = $db_user_role;
            redirect("/cms/admin");
        }
        else
        {
            return false;
        }
    }
    return true;

//   $password = password_verify($password, $db_user_password);
}


?>
