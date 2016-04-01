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
        </div>
        <div class="col-sm-9 col-md-10">
            <!-- Split button -->
<!--            <div class="btn-group">
                <button type="button" class="btn btn-default">
                    <div class="checkbox" style="margin: 0;">
                        <label>
                            <input type="checkbox">
                        </label>
                    </div>
                </button>
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span><span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">All</a></li>
                    <li><a href="#">None</a></li>
                    <li><a href="#">Read</a></li>
                    <li><a href="#">Unread</a></li>
                    <li><a href="#">Starred</a></li>
                    <li><a href="#">Unstarred</a></li>
                </ul>
            </div>-->
<!--            <button type="button" class="btn btn-default" data-toggle="tooltip" title="Refresh">
                &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;&nbsp;</button>-->
            <!-- Single button -->
<!--            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    More <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Mark all as read</a></li>
                    <li class="divider"></li>
                    <li class="text-center"><small class="text-muted">Select messages to see more actions</small></li>
                </ul>
            </div>
            <div class="pull-right">
                <span class="text-muted"><b>1</b>–<b>50</b> of <b>160</b></span>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-default">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </button>
                    <button type="button" class="btn btn-default">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </button>
                </div>
            </div>-->
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-3 col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#"><span class="badge pull-right">32</span> Inbox </a></li>
<!--                <li><a href="#">Starred</a></li>
                <li><a href="#">Important</a></li>
                <li><a href="#">Sent Mail</a></li>
                <li><a href="#"><span class="badge pull-right">3</span>Drafts</a></li>-->
            </ul>
        </div>
        <div class="col-sm-9 col-md-10">
			<?php if(!empty($rows)): ?>
				<div class="list-group">
					<?php foreach ($rows as $msg): ?>
					<?php
					$classes = [];
					?>
						<a href="<?php echo $msg->readUrl(); ?>" class="list-group-item">
							<!--									<div class="checkbox">
																	<label>
																		<input type="checkbox">
																	</label>
																</div>-->
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
			<?php endif; ?>
        </div>
    </div>
</div>