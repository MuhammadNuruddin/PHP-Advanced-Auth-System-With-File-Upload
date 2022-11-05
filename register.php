<?php 
require_once 'core/init.php';



if (Input::exists()) {
	if (Token::check(Input::get('token'))) {
	
	$validate = new Validate();
	$validation = $validate->check($_POST,array(
		'username' => array(
			'required' => true,
			'min' => 2,
			'max' => 30,
			'unique' => 'users',
			'field_name' => 'Username'
		),
		'password' => array(
			'required' => true,
			'min' => 6,
			'field_name' => 'Password'
		),
		'retype_password' => array(
			'required' => true,
			'matches' => 'password',
			'field_name' => 'Confirm Password'
		),
		'name' => array(
			'required' => true,
			'min' => 2,
			'max' => 50,
			'field_name' => 'Name'
		)

	));

	if ($validation->passed()) {
		// Session::flash('success','You registered successfully!');
		// header("Location: index.php");
		$salt = substr(Hash::salt(), 0, 32) ;
		$user = new User();
		try {
			
			$user->create(array(
				'username' => Input::get('username'),
				'password' => Hash::make(Input::get('password'), $salt),
				'salt' => $salt,
				'name' => Input::get('name'),
				'groups' => 1
				)
			);

			Session::flash('home', 'You have been registered successfully and can now log in');
			Redirect::to('index.php');

		} catch (Exception $e) {
			die($e->getMessage());
		}
	}else {
		$errors = $validation->errors();
		// $errors = [];
		// foreach ($validation->errors() as $error) {
		// 	// echo $error, '</br>';
		// 	array_push($errors, $error);
		// 	// var_dump($validation->errors());
		// }
		// print_r($errors);
		// echo $errors['username'];
	}
}
}
?>
<head>
	<style>
		.error {
			border:1px solid red;
		}
		</style>
</head>
<form action="" method="POST">
<div class="field">
	<label for="username">Username</label>
	<input type="text" name="username" class="<?php if(isset($errors) &&check_array('username',$errors)) echo 'error' ?>" id="username" value="<?= escape(Input::get('username')) ;?>" autocomplete="off">
	<br>
	<small style='color:red'><?php if(isset($errors)) check_error('username',$errors); ?></small>
	
</div>	

<div class="field">
	<label for="password">Password</label>
	<input type="password" name="password" id="password" class="<?php if(isset($errors) &&check_array('username',$errors)) echo 'error' ?>">
	<br>
	<small style='color:red'><?php if(isset($errors)) check_error('password',$errors); ?></small>
</div>	

<div class="field">
	<label for="retype_password">Retype Password</label>
	<input type="password" name="retype_password" id="retype_password" class="<?php if(isset($errors) &&check_array('username',$errors)) echo 'error' ?>">
	<br>
	<small style='color:red'><?php if(isset($errors)) check_error('retype_password',$errors); ?></small>	
</div>	

<div class="field">
	<label for="name">Your Name</label>
	<input type="text" name="name" id="name"
	class="<?php if(isset($errors) &&check_array('username',$errors)) echo 'error' ?>"
	 value="<?= escape(Input::get('name')) ;?>" autocomplete="off">
	<br>
	<small style='color:red'><?php if(isset($errors)) check_error('name',$errors); ?></small>
</div>
<input type="hidden" name="token" value="<?= Token::generate() ;?>">

<input type="submit" value="Register">
</form>

