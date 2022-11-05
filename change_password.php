<?php 
require_once 'core/init.php';

$user = new User();

if (!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if (Input::exists()) {
	if (Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'password_current' => array(
				'required' => true,
				'min' => 6,
				'field_name' => 'Current Password'
			),
			'password_new' => array(
				'required' => true,
				'min' => 6,
				'field_name' => 'New Password'
			),
			'password_new_again' => array(
				'required' => true,
				'min' => 6,
				'field_name' => 'Confirm Password',
				'matches' => 'password_new'
			)					
		));		
	}

	if ($validation->passed()) {
		if (Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password) {
			echo "Your current password is wrong!";
		}else {
			$salt = substr(Hash::salt(), 0, 32);
			$user->update(array(
				'password' => Hash::make(Input::get('password_new'), $salt),
				'salt' => $salt
			));

				Session::flash('home', 'Your password has been changed successfully!');
				Redirect::to('index.php');
		}

	}else {
		foreach ($validation->errors() as $error) {
			echo $error, '<br>';
		}
	}
}

 ?>


 <form action="" method="POST">
<div class="field">
	<label for="password_current">Current Password</label>
	<input type="password" name="password_current" id="password_current" value="" autocomplete="off">
</div>	

<div class="field">
	<label for="password_new">New Password</label>
	<input type="password" name="password_new" id="password_new" value="" autocomplete="off">
</div>


<div class="field">
	<label for="password_new_again">Retype New Password</label>
	<input type="password" name="password_new_again" id="password_new_again" value="" autocomplete="off">
</div>

<input type="hidden" name="token" value="<?php echo Token::generate() ;?>">

<input type="submit" value="Change">
</form>