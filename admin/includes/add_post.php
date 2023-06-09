<?php
global $conn;

if (isset($_POST['create_post']))
{
    $post_title = escape($_POST['title']);
    $post_user = $_POST['post_user'];
    $post_category_id = $_POST['post_category'];
    $post_status = $_POST['post_status'];
    $post_image = $_FILES['image']['name'];
    $post_image_tmp = $_FILES['image']['tmp_name'];
    $post_tags = $_POST['post_tags'];
    $post_content = $_POST['post_content'];
    $post_date = date('d-m-y');
//    $post_comment_count = 4;

    move_uploaded_file($post_image_tmp, "../images/$post_image");

    $query = "INSERT INTO posts(post_category_id,post_title,post_user,post_date,
    post_image,post_content,post_tags,post_status) ";
    $query .= "VALUES({$post_category_id},'{$post_title}','{$post_user}',now(),
    '{$post_image}','{$post_content}','{$post_tags}','{$post_status}' )";

    $create_post_query = mysqli_query($conn,$query);

    confirmQuery($create_post_query);

    $the_post_id = mysqli_insert_id($conn);

    echo "<h4 class='bg-success'>Post Created. <a href='/cms/post.php?p_id={$the_post_id}'>View Post </a>or<a href='posts.php'> Edit More Posts</a></h4>";

}

?>


<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Post Title</label>
        <input type="text" class="form-control" name="title" />
    </div>
    <div class="form-group">
        <label for="category">Post Category: </label>
        <select class="form-control" name="post_category" id="post_category">
            <?php

            $query = "SELECT * FROM categories ";
            $select_categories = mysqli_query($conn, $query);
            confirmQuery($select_categories);

            while ($row = mysqli_fetch_assoc($select_categories)) {
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];

                echo "<option value='$cat_id'>$cat_title</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="user">Users: </label>
        <select class="form-control" name="post_user" id="post_category">
            <?php

            $query = "SELECT * FROM users ";
            $select_users = mysqli_query($conn, $query);
            confirmQuery($select_users);

            while ($row = mysqli_fetch_assoc($select_users)) {
                $user_id = $row['user_id'];
                $username = $row['username'];

                echo "<option value='{$username}'>$username</option>";
            }
            ?>
        </select>
    </div>
<!--    <div class="form-group">-->
<!--        <label for="author">Post Author</label>-->
<!--        <input type="text" class="form-control" name="author" />-->
<!--    </div>-->
    <div class="form-group">

        <select class="form-control" name="post_status" id="">
            <option value="draft">Post Status</option>
            <option value="published">Publish</option>
            <option value="draft">Draft</option>
        </select>

    </div>
    <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file" class="form-control" name="image" />
    </div>
    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input type="text" class="form-control" name="post_tags" />
    </div>
    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea class="form-control" name="post_content" id="body" cols="30" rows="10"></textarea>
    </div>
    <div class="form-group">
        <label for="post_tags"></label>
        <input type="submit" class="btn btn-success" name="create_post" value="Publish Post" />
    </div>

</form>

