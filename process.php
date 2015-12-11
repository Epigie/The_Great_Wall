<?php session_start();
	  require('new-connection.php');
	  // Set global variables // 
	  $errors = array();
	  $salt = bin2hex(openssl_random_pseudo_bytes(5));

	/* ====================  Registration form validation  ================================================== */
	if($_POST['submitted_form'] == 'register') {
		if(empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || 
		   empty($_POST['password']) || empty($_POST['confirm_password'])){
			$errors[] = "Make sure no fields are empty";
		}  
		if (is_numeric($_POST['first_name']) || strlen($_POST['first_name']) < 3 ){
			$errors[] = "Enter a valid first name";
		}  
		if (is_numeric($_POST['last_name']) || strlen($_POST['last_name']) < 3){
			$errors[] = "Enter a valid last name";
		}  
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			$errors[] = "Enter a valid email";
		}  
		if (strlen($_POST['password']) < 6){
			$errors[] = "Password length must be more than 6 characters";
		}  
		if ($_POST['confirm_password'] != $_POST['password']){
			$errors[] = "Passwords do not match";
		}  
		$first_name = ucwords(escape_this_string($_POST['first_name']));
		$last_name = ucwords(escape_this_string($_POST['last_name']));
		$email = escape_this_string($_POST['email']);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    $_POST['email'];
		$encrypted_password = md5(escape_this_string($_POST['password']) . '' . $salt);

		# Queries 
		$query_1 = "INSERT INTO users (first_name, last_name, email, password, salt, created_at, updated_at) 
					VALUES ('{$first_name}', '{$last_name}', '{$email}', '{$encrypted_password}', '{$salt}', NOW(), NOW())";
		$query_2 = "SELECT * FROM users WHERE email = '{$_POST['email']}' ";
		$query_3 = "SELECT posts.id as post_id, post, posts.created_at as post_created_at, posts.user_id, 
					users.id as user_id, users.first_name, users.last_name, users.created_at as users_created_at 
					FROM posts LEFT JOIN users ON posts.user_id = users.id";
		$query_4 = "SELECT comments.id, comment, comments.created_at AS comment_created_at, 
					comments.user_id AS comment_user_id, comments.post_id AS comment_post_id, 
					users.first_name as comment_first_name, posts.id AS post_id FROM comments LEFT JOIN posts ON 
					comments.post_id = posts.id LEFT JOIN users ON posts.user_id = users.id";
		
		// if(fetch_record($query_5)){
		// 	$errors[] = "Sorry, the email chose already exists";
		// } 
		/* Check errors array for errors  */
		if(count($errors) > 0){
			$_SESSION['errors'] = $errors;
			$errors = array();
			header('location: index.php');
			die; 
		} 
		run_mysql_query($query_1);
		$_SESSION['this_user'] = fetch_record($query_2);
		if(! empty(fetch_all($query_3))){
			$_SESSION['all_posts'] = fetch_all($query_3);
		} 
		if(! empty(fetch_all($query_4))){
			$_SESSION['all_comments'] = fetch_all($query_4);
		}
		header('location: wall.php');
	}
	/* ========================  Login form validation  ======================================================== */
	if($_POST['submitted_form'] == 'login') {
		if(empty($_POST['email'])||empty($_POST['password'])){
			$errors[] = "Make sure no fields are empty";
		}
		if (strlen($_POST['password']) < 6 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			$errors[] = "Make sure password is more than 6 characters and email is valid";
		}
		// Set local variables // 
		$query_1 = "SELECT * FROM users WHERE email = '{$_POST['email']}' ";
		$query_2 = "SELECT posts.id as post_id, post, posts.created_at as post_created_at, posts.user_id, 
					users.id as user_id, users.first_name, users.last_name, users.created_at as users_created_at 
					FROM posts LEFT JOIN users ON posts.user_id = users.id";
		$query_3 = "SELECT comments.id AS comment_id, comments.comment, comments.created_at AS comment_created_at, 
					comments.user_id AS comment_user_id, comments.post_id AS comment_post_id, 
					users.first_name AS comment_first_name, users.id AS comment_user_id FROM comments
					LEFT JOIN users ON comments.user_id = users.id;";
		$this_user = fetch_record($query_1);

		// Check database for username & password // 
		$encrypted_password = md5( escape_this_string($_POST['password']).''.$this_user['salt'] );
		if(empty($this_user)) {
			$errors[] = "Email was not found";
		} else if ($this_user['password'] != $encrypted_password){
			$errors[] = "Password and email do not match";
		}

		/* ---------- use when troubleshooting password / email errors ------ */
		// echo "this_user: ";
		// var_dump($this_user);
		// echo "encrypted_password:"; 
		// var_dump($encrypted_password);
		// echo "stored password:";
		// var_dump($this_user['password']);
		// var_dump($this_user['salt']);
		// var_dump($salt);
		/* ------------------------------------------------------------------- */

		if(count($errors) > 0){
			$_SESSION['errors'] = $errors;
			$errors = array();
			header('location: index.php');
		} else { 
			$_SESSION['this_user'] = $this_user;
			  # check for posts
			if(! empty(fetch_all($query_2))) {
				$_SESSION['all_posts'] = fetch_all($query_2);
			} # check for comments 
			if(! empty(fetch_all($query_3))){
				$_SESSION['all_comments'] = fetch_all($query_3);
			}
			header('location: wall.php');
		}
	}  
 ?>