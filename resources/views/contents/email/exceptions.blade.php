<?php echo zbase_view_render(zbase_view_file_contents('email.header')); ?>

<?php if(!empty($error)):?>
ERROR: -----
<br />
<?php echo $error ?>
<?php endif;?>

<?php if(!empty($message)):?>
<br />
<br />
<br />
MESSAGE: -------------------
<br />
<?php echo $message ?>
<?php endif;?>

<br />
<br />
<br />
--- DETAILS:
<?php
$error = 'Date: ' . zbase_date_now()->format('Y-m-d h:i:s A') . "<br />";
$error .= 'URL: ' . zbase_url_uri() . "<br />";
$error .= 'Is Posting: ' . (zbase_request_is_post() ? 'Yes' : 'No') . "<br />";
$error .= 'Is AJAX: ' . (zbase_request_is_ajax() ? 'Yes' : 'No') . "<br />";
$error .= 'Data: ' . json_encode(zbase_request_inputs()) . "<br />";
$error .= 'Routes: ' . json_encode(zbase_route_inputs()) . "<br />";
$error .= 'IP Address: ' . zbase_ip() . "<br /><br /";
if(zbase_auth_has())
{
	$user = zbase_auth_user();
	$error .= 'User: ' . $user->email() . ' ' . $user->username() . '[' . $user->id() . ']' . "<br />";
}
echo $error;
?>

<?php echo zbase_view_render(zbase_view_file_contents('email.footer')); ?>