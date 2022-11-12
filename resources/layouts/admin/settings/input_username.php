<?php
function generate_username() {
	$settings = fullculqi_get_settings();
	$username_bd = $settings['username'];
	if($username_bd == '' || $username_bd == null){
		return bin2hex(random_bytes(5));
	}
	else
	{
		return $username_bd;
	}
}
?>
<label for="fullculqi_username">
	<b>Usuario:</b> <?php echo $username = generate_username(); ?>
	<b>Password:</b> <?php echo $password; ?>
    <input id="fullculqi_username" name="fullculqi_options[username]" type="hidden" value="<?php echo $username; ?>">
</label>
