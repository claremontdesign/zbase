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
$rows = $ui->getRows();
?>
<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#"><span class="badge pull-right zbase-content-url" data-config="{url: '<?php echo zbase_url_from_route('messages', array('action' => 'count-new')) ?>', htmlIndex: 'message-count-new'}"></span> Inbox </a></li>
            </ul>
        </div>
        <div class="col-sm-9 col-md-10">
			<?php if(!empty($rows->count())): ?>
				<style type="text/css">
					.msgs-group{}
					.msgs-group .list-group-item{
						background: #EBEBEB;
						font-weight: bold;
					}
					.msgs-group .list-group-item.msg-read{
						background: white;
						font-weight: normal;
					}
				</style>
				<div class="list-group msgs-group">
					<?php foreach ($rows as $msg): ?>
						<?php
						$classes = [];
						if(!empty($msg->readStatus()))
						{
							$classes[] = 'msg-read';
						}
						?>
						<a href="<?php echo $msg->readUrl(); ?>" class="list-group-item <?php echo implode(' ', $classes); ?>">
							<span class="glyphicon glyphicon-star-empty"></span>
							<span class="name" style="min-width: 120px; display: inline-block;"><?php echo $msg->senderName() ?></span>
							<span class=""><?php echo $msg->subject() ?></span>
							<span class="badge"><?php echo zbase_view_render(zbase_view_file_contents('ui.components.time'), ['date' => $msg->getTimeSent()]); ?></span>
							<span class="pull-right">
								<span class="glyphicon glyphicon-paperclip"></span>
							</span>
						</a>
					<?php endforeach; ?>
				</div>
			<?php else: ?>
				<div class="alert alert-warning">You don't have any messages.</div>
			<?php endif; ?>
        </div>
    </div>
	<hr />

	<?php if(!empty($rows->count())): ?>
		<div class="pull-right">
			<?php echo zbase_view_render(zbase_view_file_contents('ui.datatable.pagination'), ['paginator' => $rows, 'ui' => $ui]); ?>
		</div>
	<?php endif; ?>
</div>