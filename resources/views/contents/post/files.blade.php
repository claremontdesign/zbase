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
$files = $entity->postFiles();
?>
<?php ob_start(); ?>
<script type="text/javascript">
	<?php echo $entity->postFileScript('delete'); ?>
</script>
<?php
zbase_view_script_add($entity->postHtmlId() . 'Filesscript', ob_get_clean());
?>
<div class="files_wrapper" id="<?php echo $entity->postHtmlId() ?>FilesWrapper">
	<?php if(!empty($files)): ?>
		<?php foreach ($files as $file): ?>
			<?php $file = (object) $file; ?>
			<?php echo zbase_view_render(zbase_view_file_contents('post.file'), ['file' => $file, 'entity' => $entity])?>
		<?php endforeach; ?>
	<?php else: ?>
	<p class="empty">No images found.</p>
	<?php endif; ?>
</div>
