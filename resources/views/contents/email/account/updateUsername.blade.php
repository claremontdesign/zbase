<?php

/**
 * Username was updated, email this to account to the owner
 * $entity = The account owner
 * $old = Old username
 * $new = New username
 */
?>
<?php echo zbase_view_render(zbase_view_file_contents('email.header')); ?>
<?php echo zbase_view_render(zbase_view_file_contents('email.footer')); ?>