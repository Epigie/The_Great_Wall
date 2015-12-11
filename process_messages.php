<?php session_start();
	  require_once('new-connection.php');
	/* ==========================  Posts form validation  ====================================================== */
 	if($_POST['submitted_form'] == 'post') {
 		$posts = $_POST['post_message'];
 		$query_1 = "INSERT INTO posts (post, created_at, updated_at, user_id) 
 					VALUES ( '$posts', NOW(), NOW(), {$_SESSION['this_user']['id']} )";
 		$query_2 = "SELECT posts.id as post_id, post, posts.created_at as post_created_at, posts.user_id, 
					users.id as user_id, users.first_name, users.last_name, users.created_at as users_created_at 
					FROM posts LEFT JOIN users ON posts.user_id = users.id";
 		$query_3 = "SELECT comments.id AS comment_id, comments.comment, comments.created_at AS comment_created_at, 
					comments.user_id AS comment_user_id, comments.post_id AS comment_post_id, 
					users.first_name AS comment_first_name, users.id AS comment_user_id FROM comments
					LEFT JOIN users ON comments.user_id = users.id;;";
 		// var_dump($post_message);
 		// var_dump(run_mysql_query($get_messages));
 		// var_dump(fetch_all($get_messages));
 		// var_dump(fetch_all($get_posts));

 		run_mysql_query($query_1);
 		if(fetch_all($query_2)){
 			$_SESSION['all_posts'] = fetch_all($query_2);
 		}
		if(! empty(fetch_all($query_3))){
			$_SESSION['all_comments'] = fetch_all($query_3);
		}		
		header('location: wall.php');
 	}
 	/* ==========================  Comment form validation  ==================================================== */
 	if($_POST['submitted_form'] == 'comments') {
		$comment = escape_this_string($_POST['post_comments']);
		$query_1 = "INSERT INTO comments (comment, created_at, updated_at, user_id, post_id )
		            VALUES ('$comment', NOW(), NOW(), '{$_SESSION['this_user']['id']}', '{$_POST['post_id']}')";
		$query_2 = "SELECT comments.id AS comment_id, comments.comment, comments.created_at AS comment_created_at, 
					comments.user_id AS comment_user_id, comments.post_id AS comment_post_id, 
					users.first_name AS comment_first_name, users.id AS comment_user_id FROM comments
					LEFT JOIN users ON comments.user_id = users.id;";
		run_mysql_query($query_1);
	// // 	// var_dump($_SESSION['post_id']);
	// // 	// var_dump($query_1);
	// // 	// var_dump(run_mysql_query($post_comments));
		$_SESSION['all_comments'] = fetch_all($query_2);
		header('location: wall.php');
	}
 ?>