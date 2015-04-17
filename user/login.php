<?php

session_start();

require '../includes/config/database.php';
require '../includes/config/config.php';
require './user.php';

$username = $_POST['username'];
$password = $_POST['password'];

$user = new User();
$answer = $user->login($username, $password);
if(is_string($answer))
{
	?>
<script>
	alert('<?php echo $answer ?>');
	window.location = '<?php echo $path ?>';
</script>
	<?php
}
else
{
	?>
<script>
	window.location = '<?php echo $path ?>';
</script>

	<?php
}
