<?php
//This file should only be used to register a user.. Should receive Username, Email and password.

require '../includes/config/database.php';
require '../includes/config/config.php';
require '../user/user.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$user = new User();
$answer = $user->createUser($username, $email, $password);
if($answer)
{
	?>
<script>
	alert('Welcome to Omi');
	window.location = '<?php echo $path ?>';
</script>

	<?php
}
else
{
	?>
<script>
	alert('SQL Error:<?php echo $answer ?>');
	window.location = '<?php echo $path ?>';
</script>
	<?php
}