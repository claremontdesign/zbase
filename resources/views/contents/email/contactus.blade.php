<?php
/**
 * Contact Us
 */
?>
<?php echo zbase_view_render(zbase_view_file_contents('email.header')); ?>
<h1>Contact Us New Message</h1>
Name:<?php echo $name ?><br />
Email:<?php echo $email ?><br />
<?php echo!empty($telephone) ? 'Telephone:' . $telephone . '<br />' : null ?>
Message:
<br />
====<br /><br />
<?php echo $comment ?>
<br /><br />===<br />

<?php echo zbase_view_render(zbase_view_file_contents('email.footer')); ?>