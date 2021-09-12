<?php

$con = mysqli_connect("localhost","root","","twitter") or die("Connection was not established");

//function for inserting post

function insertPost(){
	if(isset($_POST['sub'])){
		global $con;
		global $user_id;

		$content = htmlentities($_POST['content']);
		$upload_image = $_FILES['upload_image']['name'];
		$image_tmp = $_FILES['upload_image']['tmp_name'];
		$random_number = rand(1, 100);

		if(strlen($content) > 140){
			echo "<script>alert('Please Use 140 or less than 140 words!')</script>";
			echo "<script>window.open('home.php', '_self')</script>";
		}else{
			$insert = "insert into posts (user_id, post_content, post_date) values('$user_id', '$content', NOW())";
			$run = mysqli_query($con, $insert);

			if($run){
				echo "<script>alert('Your Post updated a moment ago!')</script>";
				echo "<script>window.open('home.php', '_self')</script>";

				$update = "update users set posts='yes' where user_id='$user_id'";
				$run_update = mysqli_query($con, $update);
				}
			}
	}
}


//for searching people
function search_user($temp){
	global $con;
	if (isset($_GET['search_user_btn'])) {
		$search_query = htmlentities($_GET['search_user']);
		$get_user = "select * from users where f_name like '%$search_query%' OR l_name like '%$search_query%' OR user_name like '%$search_query%'";
	}
	else{
		$get_user = "select * from users";
	}
	$run_user = mysqli_query($con, $get_user);
	while ($row_user=mysqli_fetch_array($run_user)){

		$user_id = $row_user['user_id'];
		$f_name = $row_user['f_name'];
		$l_name = $row_user['l_name'];
		$username = $row_user['user_name'];
		$user_image = $row_user['user_image'];
		$user_mail = $row_user['user_email'];

		if ($user_mail == $temp){
			continue;
		};

		echo"
		<div class='row'>
			<div class='col-sm-3'>
			</div>
				<div class='col-sm-6'>
					<div class='row' id='find_people'>
						<div class='col-sm-4'>
							<a href='user_profile.php?u_id=$user_id'>
							<img src='users/$user_image' width='150px' height='140px' title='$username' style='float:left; ,margin:lpx;'/>
							</a>
						</div><br><br>
						<div class='col-sm--6'>
							<a style='text-decoration:none;cursor:pointer;color:#3897f0;' href='user_profile.php?u_id=$user_id'>
							<strong><h2>$f_name $l_name</h2></strong>
							</a>
						</div>
						<div class='col-sm-3'>
						</div>
					</div>
				</div>
				<div class='col-sm-4'>
				</div>
			</div><br>
		";
	}
}

//for getting posts in the home page
function get_posts(){
	global $con;
	$per_page = 4;

	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page=1;
	}

	$start_from = ($page-1) * $per_page;

	$get_posts = "select * from posts ORDER by 1 DESC LIMIT $start_from, $per_page";

	$run_posts = mysqli_query($con, $get_posts);

	while($row_posts = mysqli_fetch_array($run_posts)){

		$post_id = $row_posts['post_id'];
		$user_id = $row_posts['user_id'];
		$content = substr($row_posts['post_content'], 0,40);
		$post_date = $row_posts['post_date'];

		$user = "select *from users where user_id='$user_id' AND posts='yes'";
		$run_user = mysqli_query($con,$user);
		$row_user = mysqli_fetch_array($run_user);

		$user_name = $row_user['user_name'];
		$user_image = $row_user['user_image'];

		//now displaying posts from database

		echo"
		<div class='row'>
			<div class='col-sm-3'>
			</div>
			<div id='posts' class='col-sm-6'>
				<div class='row'>
					<div class='col-sm-2'>
					<p><img src='users/$user_image' class='img-circle' width='100px' height='100px'></p>
					</div>
					<div class='col-sm-6'>
						<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
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
				<a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-info'>Comment</button></a><br>
			</div>
			<div class='col-sm-3'>
			</div>
		</div><br><br>
		";
	}

	include("pagination.php");
}

//for following people
function follow($user_id){
	global $con;
	if (isset($_POST['follow_btn'])) {
		if ($_POST['follow_btn'] == 1){
			$temp = $_SESSION['user_id'];
			$insert = "insert into follows(user_id,follow_id) values($temp, $user_id)";
			$query = mysqli_query($con, $insert);
		}
		else{
			$temp = $_SESSION['user_id'];
			$delete = "delete from follows where user_id=$temp and follow_id=$user_id";
			$query = mysqli_query($con, $delete);
		}
		echo "<meta http-equiv='refresh' content='0'>";
	};
}
?>