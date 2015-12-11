<?php session_start(); 	
	require("new-connection.php");
	if (! isset($_SESSION['this_user'])) {
		header('location: index.php');
	}
	if(empty($_SESSION['posts'])) {
		$_SESSION['posts'] = array();
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>THE LCSO Wall</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="styles_2.css">
	<!-- <meta http-equiv="refresh" content="1"> -->
</head>
<body>
	<div id="header">
		<h2>The Great Wall</h2>
		<p class='a'>Welcome <?php echo $_SESSION['this_user']['first_name']; ?> </p>
		<a href="index.php"><p class='b'>Log off</p></a>
	</div>
	<div id="wall">
		<h3>Post a message</h3>
		<?php echo $_SESSION['this_user']['first_name'] . " - <span class='blue'>" . $_SESSION['this_user']['id'] . "</span>"; ?>
		<!-- FORM TO SUBMIT POSTS -->
		<form action="process_messages.php" method="post">
			<input type='hidden' name='submitted_form' value='post'>
			<textarea type='text' name='post_message' placeholder="Post a message" class='posts'></textarea>
			<input type='submit' value='Post a message' class='button'>
		</form>
		<?php 
		if(! empty($_SESSION['all_posts'])){ 
			$posts = $_SESSION['all_posts'];
			for ($i = count($posts)-1; $i >= 0; $i-- ){?>  
				<div id="post"> <!-- =================== POSTS ========================== -->
					<h3><?php echo $posts[$i]['first_name'] ." <span class='time'> ( user-ID ".$posts[$i]['user_id']." )</span>" ?></h3>
					<p class='posted_messages'><?php echo '"'.$posts[$i]['post'].'"'."<span class='time'> - ". $posts[$i]['post_created_at']."</span>"; ?></p>
					<!-- ========================= COMMENTS ============================= -->
					<?php 
						if(! empty($_SESSION['all_comments'])){
							for($x = count($_SESSION['all_comments'])-1; $x >=0; $x--)
							{
								if($_SESSION['all_comments'][$x]['comment_post_id'] == $_SESSION['all_posts'][$i]['post_id'])
								{?>
									<p class='posted_comments'><?php echo $_SESSION['all_comments'][$x]['comment'].
									"<span class='time'> - ".$_SESSION['all_comments'][$x]['comment_first_name'].
									" ( ".$_SESSION['all_comments'][$x]['comment_created_at']." )</span>";
									?></p>

								<?}
							}		
						}
						// echo "=================================================================";
						// var_dump($_SESSION['all_posts']);
					?>
					<!-- =======================  COMMENT SUBMIT FORM  ============================== -->
					<form action='process_messages.php' method='post'>
						<input type='hidden' name='submitted_form' value='comments'>
						<input type='hidden' name='post_id' value=<? echo $posts[$i]['post_id'] ?>  > <!--'$posts[$i]['id']'-->
						<textarea name='post_comments' placeholder='Add a comment' class='post_comments'></textarea>
						<input type='submit' class='button' value='Add comment'>
					</form>
					<?php  } ?>
				</div> <!-- End of post div -->
			<?php } 
					// var_dump($_SESSION['all_comments']);
		?>

	

	</div> <!-- End Container -->
</body> 
</head>