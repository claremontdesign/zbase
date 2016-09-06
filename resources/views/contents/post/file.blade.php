<?php
/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Sep 6, 2016 7:31:49 PM
 * @file files.blade.php
 * @project Zbase
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */
?>
<div class="thumbnail <?php echo $file->id ?> <?php echo $entity->postHtmlId() ?>FileWrapper">
	<a href="<?php echo $entity->postFileUrl($file, 'view') ?>" class="fancybox-button" data-rel="fancybox-button">
		<img class="img-thumbnail img-responsive" src="<?php echo $entity->postFileUrl($file, 'view', ['w' => 200]) ?>" alt="<?php echo $file->filename ?>">
	</a>
	<?php if($entity->postFileCanBeDeleted($file)): ?>
		<div class="caption">
			<p>
				<a data-id="<?php echo $file->id?>" href="<?php echo $entity->postFileUrl($file, 'delete') ?>" class="<?php echo $entity->postHtmlId() ?>FileDeleteBtn btn red delete btn-sm">
					<i class="fa fa-trash-o"></i>
					<span>Delete</span>
				</a>
			</p>
		</div>
	<?php endif; ?>
</div>
