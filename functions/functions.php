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
			if(strlen($upload_image) >= 1 && strlen($content) >= 1){
				move_uploaded_file($image_tmp, "imagepost/$upload_image.$random_number");
				$insert = "insert into posts (user_id, post_content, upload_image, post_date) values('$user_id', '$content', '$upload_image.$random_number', NOW())";

				$run = mysqli_query($con, $insert);

				if($run){
					echo "<script>alert('Your Post updated a moment ago!')</script>";
					echo "<script>window.open('home.php', '_self')</script>";

					$update = "update users set posts='yes' where user_id='$user_id'";
					$run_update = mysqli_query($con, $update);
				}

				exit();
			}else{
				if($upload_image=='' && $content == ''){
					echo "<script>alert('Error Occured while uploading!')</script>";
					echo "<script>window.open('home.php', '_self')</script>";
				}else{
					if($content==''){
						move_uploaded_file($image_tmp, "imagepost/$upload_image.$random_number");
						$insert = "insert into posts (user_id,post_content,upload_image,post_date) values ('$user_id','No','$upload_image.$random_number',NOW())";
						$run = mysqli_query($con, $insert);

						if($run){
							echo "<script>alert('Your Post updated a moment ago!')</script>";
							echo "<script>window.open('home.php', '_self')</script>";

							$update = "update users set posts='yes' where user_id='$user_id'";
							$run_update = mysqli_query($con, $update);
						}

						exit();
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
		}
	}
}

//for searching people
function search_user(){
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

?>