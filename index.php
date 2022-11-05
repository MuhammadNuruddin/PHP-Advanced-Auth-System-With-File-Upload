<?php 

require_once 'core/init.php';




// echo Config::get('mysql.host');
// $users = DB::connect();
// $user = DB::connect()->select('users', array('username','=','zeeya'));
// if ($users->count()) {
// 	foreach ($users as $user) {
// 		echo $user->username;
// 	}
// }
// $user = DB::connect()->select('users', array('username','=','zeeya'));
// var_dump($user);

// $user_check = DB::connect()->query("SELECT * FROM users WHERE username = ?", 
// 	array('zeeya');
// );
// var_dump($user_check);
$test = DB::connect()->pdo_delete('users','=',array('username','groups'),array('nura',1));
// var_dump($test->pdo_count());

// $user = DB::connect()->update('users', 3 ,array(
// 'password' => 'new_passcode',
// 'name' => 'Nura Zia'
// ));


// if (!$user->count()) {
// 	echo "No user";
// }else {
// 	echo $user->first()->username;
// }

// DB::connect()->query("SELECT * FROM users");

if (Session::exists('home')) {
	echo '<p><strong>'.Session::flash('home').'</strong></p>';
}


if (Session::exists('success')) {
	echo Session::flash('success');
}

// echo Session::get(Config::get('session.session_name'));
$user = new User();
if ($user->isLoggedIn()) {
?>
	<h4>Welcome, <a href="profile.php?user=<?php echo escape($user->data()->username) ;?>"><?php echo escape($user->data()->username) ;?></a>!</h4>
	<ul>
		<li><a href="upload.php">Upload Product</a></li>
		<li><a href="logout.php">Log out</a></li>
		<li><a href="update.php">Update Details</a></li>
		<li><a href="change_password.php">Change Password</a></li>
	</ul>
<?php

if ($user->hasPermission('Admin')) {
	echo "<p>You are an administrator!</p>";
}
}else {

	echo '<p>You need to <a href="login.php">Login</a> or <a href="register.php">Register</a>';
}