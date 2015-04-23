<?php
require '../../includes/config/config.php';
require '../../includes/config/database.php';
require '../user.php';

$verificationCode = $_POST['verificationCode'];
$userId = $_POST['id'];
$newPassword = $_POST['password'];

$user = new User();
$answer = $user->resetPassword($userId, $verificationCode, $newPassword);
?>
<script>
	alert('<?php echo $answer ?>');
</script>

