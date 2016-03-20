<?php
/**
 * Contact Us
 */
?>
<?php echo zbase_view_render(zbase_view_file_contents('email.header')); ?>
<h1>Contact Us New Message</h1>
Name:<?php echo $name ?><br />
Email:<?php echo $email ?><br />
Telephone:<?php echo!empty($telephone) ? $telephone : null ?><br />
Message:
<br />
====<br /><br />
<?php echo $message ?>
<br /><br />===<br />

<?php echo zbase_view_render(zbase_view_file_contents('email.footer')); ?>