<?php
function generate_password() {
	$settings = fullculqi_get_settings();
	$password_bd = $settings['password'];
	if($password_bd == '' || $password_bd == null){
		return bin2hex(random_bytes(10));
	}
	else
	{
		return $password_bd;
	}
}
?>
<label for="fullculqi_password">
	<b>Password:</b> <?php echo $password = generate_password(); ?>
    <input id="fullculqi_password" name="fullculqi_options[password]" type="hidden" value="<?php echo $password; ?>">
</label>
