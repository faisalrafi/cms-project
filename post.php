<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
<?php include "admin/functions.php"; ?>

<!-- Navigation -->

<?php include "includes/navigation.php"; ?>

<?php



?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php
            global $conn;

            if (isset($_GET['p_id']))
            {
                $post_id = $_GET['p_id'];

                $view_query = "UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id =$post_id ";
                $send_query = mysqli_query($conn,$view_query);

                if(!$send_query){
                    die("Query Failed");
                }

                if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'){

                    $query = "SELECT * FROM posts WHERE  post_id = $post_id";

                }
                else{
                    $query = "SELECT * FROM posts WHERE  post_id = $post_id AND post_status = 'published' ";
                }

                $select_all_posts_query = mysqli_query($conn, $query);

                if (mysqli_num_rows($select_all_posts_query) < 1){

                    echo "<h1 class='text-center'>No Posts Available</h1>";
                }
                else{

            while ($row = mysqli_fetch_assoc($select_all_posts_query)){
                $post_title = $row['post_title'];
                $post_author = $row['post_user'];
                $post_date = $row['post_date'];
                $post_image = $row['post_image'];
                $post_content = $row['post_content'];

                $base_url = 'http://127.0.0.1';
                $current_url = $base_url . $_SERVER['REQUEST_URI'];

                ?>
                <h1 class="text-center page-header">
                    Posts
                </h1>
                <h3 class="text-left"><strong>Share The Post On</strong></h3>
                <div class="col-md-12">
                    <a class="btn fa-icons" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $current_url; ?>"><i class="bi bi-facebook">Facebook</i></a>

                    <a class="btn fa-icons" target="_blank" href="https://twitter.com/intent/tweet?url=<?php echo $current_url; ?>"><i class="bi bi-twitter">Twitter</i></a>

                    <a class="btn fa-icons" target="_blank" href="https://wa.me/?text=<?php echo $current_url; ?>"><i class="bi bi-whatsapp">WhatsApp</i></a>

                    <a class="btn fa-icons" target="_blank" href="mailto:?subject=Check out this post&amp;body=<?php echo $current_url; ?>"><i class="fa-lg bi bi-envelope-at-fill">Email</i></a>
                </div>
                <hr/>

                <!-- First Blog Post -->
                <div class="col-md-12">
                    <h2 class="text-center">
                        <a href="#"><?php echo $post_title; ?></a>
                    </h2>
                </div>
                <p class="lead">
                    by <a href="/cms/index.php"><?php echo $post_author; ?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span><?php echo $post_date; ?></p>
                <hr>
                <img class="img-responsive" src="/cms/images/<?php echo $post_image; ?>" alt="">
                <hr>
                <p><?php echo $post_content; ?></p>
<!--                <a class="btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>-->

                <hr>

                <?php

                }

                ?>
                    <!-- Blog Comments -->
                    <?php

            if (isset($_POST['create_comment']))
            {

                $the_post_id = $_GET['p_id'];

                $comment_author = $_POST['commnet_author'];
                $comment_email = $_POST['comment_email'];
                $comment_content = $_POST['comment_content'];

                if (!empty($comment_author) && !empty($comment_email) && !empty($comment_content))
                {
                    $query = "INSERT INTO comments (comment_post_id, commnet_author, 
                          comment_email, comment_content, comment_status, comment_date)";
                    $query .= "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}', '{$comment_content}', 
                           'unapproved', now())";

                    $create_comment_query = mysqli_query($conn, $query);
                    if(!$create_comment_query)
                    {
                        die("QUERY FAILED" . mysqli_error($create_comment_query));
                    }

//                    $query = "UPDATE posts SET post_comment_count = post_comment_count +1 ";
//                    $query .= "WHERE post_id =$the_post_id ";
//                    $update_comment_count = mysqli_query($conn,$query);

                } else{

                    echo "<script>alert('Field cannot be empty!')</script>";

                }
                }
            ?>

            <!-- Comments Form -->
            <div class="well">
                <h4>Leave a Comment:</h4>
                <form action="" method="post" role="form">
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input class="form-control" type="text" name="commnet_author" />
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" type="email" name="comment_email" />
                    </div>
                    <div class="form-group">
                        <label for="comment">Your Comment</label>
                        <textarea name="comment_content" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
                </form>
            </div>

            <hr>

            <!-- Posted Comments -->

            <?php

            $query = "SELECT * FROM comments WHERE comment_post_id = {$post_id} ";
            $query .= "AND comment_status = 'approved' ";
            $query .= "ORDER BY comment_id DESC ";
            $select_comment_query = mysqli_query($conn, $query);
            if(!$select_comment_query)
            {
                die("Query Failed" . mysqli_error($conn));
            }
            while($row = mysqli_fetch_assoc($select_comment_query)){
                $comment_date = $row['comment_date'];
                $comment_content = $row['comment_content'];
                $comment_author = $row['commnet_author'];
                ?>

                <!-- Comment -->
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="http://placehold.it/64x64" alt="">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading"><?php echo $comment_author; ?>
                            <small><?php echo $comment_date; ?></small>
                        </h4>
                        <?php echo $comment_content; ?>
                    </div>
                </div>

            <?php
            }  } } else{
                header("Location: index.php");
            }
            ?>


        </div>

        <?php include "includes/sidebar.php"; ?>

    </div>
    <!-- /.row -->

    <hr>
    <?php include "includes/footer.php"; ?>
