<!DOCTYPE html>
<?php
session_start();
include("includes/header.php");

if(!isset($_SESSION['user_email'])){
	header("location: index.php");
}
?>
<html>
<head>
	<title><?php echo "$user_name"; ?></title>
	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style/home_style2.css">
</head>
<style>
    #own_posts{
        border: 5px solid #e6e6e6;
        padding: 40px 50px;
        width: 90%;
    }
    #posts_img{
        height: 300px;
        width: 100%;
    }
</style>
<body>
<div class="row">
    <?php 
        if(isset($_GET['u_id'])){
            $u_id = $_GET['u_id'];
        }
        if($u_id < 0 || $u_id == ""){
            echo "<script>window.open('home.php','_self')</script>";
        }else{
    ?>
    <div class="col-sm-12">
        <?php
            if(isset($_GET['u_id'])){
                global $con;
                $user_id = $_GET['u_id'];
                $select = "select * from users where user_id='$user_id'";
                $run = mysqli_query($con, $select);
                $row = mysqli_fetch_array($run);

                $id = $row['user_id'];
                $image = $row['user_image'];
                $name = $row['user_name'];
                $f_name = $row['f_name'];
                $l_name = $row['l_name'];
                $country = $row['user_country'];
                $gender = $row['user_gender'];
                $register_date = $row['user_reg_date'];
                $user_email = $row['user_email'];

                echo"
                    <div class='row'>
                        <div class='col-sm-1'>
                        </div>
                        <center>
                        <div style='background-color: #e6e6e6;' class='col-sm-3'>
                        <h2>Information about</h2>
                        <img class='img-circle' src='users/$image' width='150' height='150';
                        <br><br>
                        <ul class='list-group'>
                            <li class='list-group-item' title='Username'><strong>$f_name $l_name</strong></li>
                            <li class='list-group-item' title='Gender'><strong>$gender</strong></li>
                            <li class='list-group-item' title='Country'><strong>$country</strong></li>
                            <li class='list-group-item' title='User Registration Date'><strong>$register_date</strong></li>";


                            if ($_SESSION['user_email'] != $user_email){
                                $temp_id = $_SESSION['user_id'];
                                $get_friends = "select * from follows where user_id=$temp_id and follow_id=$user_id";
                                // echo "$temp_id,$user_id";
                                $run_friends = mysqli_query($con, $get_friends);
                                $results = mysqli_num_rows($run_friends);
                            
                                if($results == 1){
                                    echo "
                                    <li class='list-group-item' title='Follow'>
                                    <form action='' method='post'>
                                        <button type='submit' class='btn btn-primary' value='0' name='follow_btn'>Unfollow</button>
                                    </form>    
                                    </li>
                                    </ul>
                                    </div>
                                    </center>
                                    ";
                                }
                                else{
                                    echo "
                                    <li class='list-group-item' title='Follow'>
                                    <form action='' method='post'>
                                        <button type='submit' class='btn btn-primary' value='1' name='follow_btn'>Follow</button>
                                    </form>   
                                    </li>
                                    </ul>
                                    </div>
                                    </center>
                                    ";
                                }
                            }
                            else{
                                echo "
                                </ul>
                                </div>
                                </center>
                                ";
                            };
            }
        ?>
        <div class="col-sm-8">
            <center><h1><strong><?php echo "$f_name $l_name"; ?></strong> Posts</h1></center>
            <?php
                global $con;
                if(isset($_GET['u_id'])){
                    $u_id = $_GET['u_id'];
                }
                $get_posts = "select * from posts where user_id='$u_id' ORDER by 1 DESC LIMIT 5";
                $run_posts = mysqli_query($con, $get_posts);
                while($row_posts = mysqli_fetch_array($run_posts)){
                    $post_id = $row_posts['post_id'];
                    $user_id = $row_posts['user_id'];
                    $content = $row_posts['post_content'];
                    $upload_image = $row_posts['upload_image'];
                    $post_date = $row_posts['post_date'];
                    $user = "select * from users where user_id='$user_id' AND posts='yes'";

                    $run_user = mysqli_query($con, $user);
                    $row_user = mysqli_fetch_array($run_user);

                    $user_name = $row_user['user_name'];
                    $f_name = $row_user['f_name'];
                    $l_name = $row_user['l_name'];
                    $user_image = $row_user['user_image'];

                    echo"
                        <div id='own_posts'>
                            <div class='row'>
                                <div class='col-sm-2'>
                                    <p><img src='users/$user_image' class='img-circle' width='100px' height='100px'></p>
                                </div>
                                <div class='col-sm-6'>
                                    <h3><a style='text-decoration:none; cursor:pointer;color#3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
                                    <h4><small style='color:black;'>Updated a post on <strong>$post_date</strong></small></h4>
                                </div>
                                <div class='col-sm-4'>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-sm-12'>
                                    <h3><p>$content</p></h3>
                                </div>
                            </div><br>
                        </div><br><br>
                    ";
                    
                }
            ?>
        </div>
    </div>
</div>
<?php } ?>
</body>
</html>

<?php
follow($user_id);
?>