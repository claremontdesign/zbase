<?php
$url = URL::to('password/reset', array($token));
?>
<?php echo zbase_view_render(zbase_view_file_contents('email.header')); ?>

We received a request to update your password.
<br />
If this is not you, disregard this email, else click on the link below.
<br /><br />
<?php echo $url?>
<br />
<br />
<a href="<?php echo $url?>">Update your password</a>

<br />
<?php echo zbase_view_render(zbase_view_file_contents('email.footer')); ?>