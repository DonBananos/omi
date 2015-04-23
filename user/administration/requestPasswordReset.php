<?php
require '../../includes/config/config.php';
require '../../includes/config/database.php';
require '../user.php';


$email = $_POST['email'];

$user = new User();
$message = $user->RequestPasswordReset($email);
?>
<script>
	alert('<?php echo $message ?>');
	window.location = '<?php echo $path ?>';
</script>
