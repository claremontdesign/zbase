<?php
/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Apr 1, 2016 3:31:09 PM
 * @file list.blade.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */
$msg = $ui->entity();
$page = [
	'title' => 'Read Message',
	'subTitle' => '',
	'headTitle' => 'Read Message'
];
zbase_view_page_details(['page' => $page]);
$js = zbase_view_render(zbase_view_file_contents('modules.messages.js'), ['msg' => $msg]);
zbase_view_script_add('messages', $js, false);
?>
<div class="col-md-2">
	<a href="#">
		<img class="media-object" style="width: 150px;" src="<?php echo $msg->senderAvatarUrl() ?>" alt="<?php echo $msg->senderName() ?>" />
	</a>
	<a href="#">
		<?php echo $msg->senderName() ?>
	</a>
</div>
<?php if(!empty($msg->node_prefix)): ?>
	<div class="col-md-6">
		<h4 class="media-heading"><?php echo $msg->subject() ?></h4>
		<span class="badge"><?php echo zbase_view_render(zbase_view_file_contents('ui.components.time'), ['date' => $msg->getTimeSent()]); ?></span>
		<hr />
		<p><?php echo nl2br($msg->message()); ?></p>
		<hr />
		<?php echo zbase_view_render(zbase_view_file_contents('modules.messages.msgbutton'), ['msg' => $msg]); ?>
		<hr />
		<?php echo zbase_view_placeholder_render('message-reply'); ?>
	</div>
	<div class="col-md-4">
		<?php echo zbase_view_render(zbase_view_file_contents($msg->node_prefix . '.messages.read'), ['node_id' => $msg->node_id, 'node_prefix' => $msg->node_prefix, 'msg' => $msg]); ?>
	</div>
<?php else: ?>
	<div class="col-md-10">
		<h4 class="media-heading"><?php echo $msg->subject() ?></h4>
		<span class="badge"><?php echo zbase_view_render(zbase_view_file_contents('ui.components.time'), ['date' => $msg->getTimeSent()]); ?></span>
		<hr />
		<p><?php echo nl2br($msg->message()); ?></p>
		<hr />
		<?php echo zbase_view_render(zbase_view_file_contents('modules.messages.msgbutton'), ['msg' => $msg]); ?>
		<hr />
		<?php echo zbase_view_placeholder_render('message-reply'); ?>
	</div>
<?php endif; ?>