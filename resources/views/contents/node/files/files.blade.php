<?php
zbase_view_plugin_load('zbase');
zbase_view_plugin_load('nodes');
//zbase_view_plugin_load('nodes-upload-krajee');
/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Mar 23, 2016 8:54:46 PM
 * @file images.blade.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */
if(empty($node) & !empty($ui))
{
	$node = $ui->form()->entity();
}
if(!empty($node))
{
	if($node instanceof Zbase\Entity\Laravel\Node\Node)
	{
		$isNode = true;
		$images = $node->files()->get();
	}
	if($node instanceof Zbase\Entity\Laravel\Node\Category)
	{
		$isCategory = true;
	}
	if($node instanceof Zbase\Entity\Laravel\User\User)
	{
		$isUser = true;
	}
}
?>
<?php if(!empty($isCategory)): ?>
	<div class="col-xs-12 col-md-12" style="margin-bottom: 20px;">
		<img class="img-thumbnail" src="<?php echo $node->avatarUrl(['thumbnail => true']) ?>" alt="<?php echo $node->title() ?>" />
	</div>
<?php endif; ?>
<?php if(!empty($isUser)): ?>
	<div class="col-xs-12 col-md-12" style="margin-bottom: 20px;">
		<img class="img-thumbnail" src="<?php echo $node->avatarUrl(['thumbnail => true']) ?>" alt="<?php echo $node->displayName() ?>" />
	</div>
<?php endif; ?>
<?php if(!empty($images) && !empty($isNode)): ?>
	<div class="row" id="node-files" style="margin: 20px;">
		<?php foreach ($images as $img): ?>
			<div class="col-xs-12 col-md-6" data-id="<?php echo $img->alphaId() ?>" id="node-files-<?php echo $img->alphaId() ?>">
				<div class="col-xs-4 col-md-2">
					<img class="img-thumbnail" src="<?php echo $img->alphaUrl(['thumbnail' => true]) ?>" alt="<?php echo $img->title() ?>" />
				</div>
				<div class="cols-xs-8 col-md-10">
					<div class="form-group">
						<input placeholder="Title" id="node-files-<?php echo $img->alphaId() ?>-title" type="text" name="nodefiletitle" class="form-control" value="<?php echo $img->title() ?>"/>
					</div>
					<div class="form-group">
						<textarea placeholder="Caption" id="node-files-<?php echo $img->alphaId() ?>-excerpt" name="nodefileexcerpt" rows="2" style="max-width:100%;width:100%;" class="form-control"><?php echo $img->caption() ?></textarea>
					</div>
					<div class="form-group">
						<div class="radio">
							<label><input value="2" type="radio" data-name="nodefilestatus" name="node-files-<?php echo $img->alphaId() ?>-status" <?php echo ($img->isDisplayed() ? ' checked' : '') ?>>Display</label>
							<label><input value="0" type="radio" data-name="nodefilestatus" name="node-files-<?php echo $img->alphaId() ?>-status" <?php echo (!$img->isDisplayed() ? ' checked' : '') ?>>Hide</label>
						</div>
					</div>
					<div class="form-group">
						<button type="button" class="btn btn-success btn-sm zbase-ajax-url"
								data-config="{
								url: '<?php echo $img->actionUrl('update') ?>',
								form: true,
								method: 'post',
								callback: nodeFileAfterUpdate,
								elements: [
								'#node-files-<?php echo $img->alphaId() ?>-excerpt',
								'#node-files-<?php echo $img->alphaId() ?>-title',
								'inputByName=node-files-<?php echo $img->alphaId() ?>-status']
								}">Update</button>
								<?php if(!empty($enablePrimary)): ?>
							<button type="button" class="btn btn-info btn-sm zbase-ajax-url"
									data-config="{
									url: '<?php echo $img->actionUrl('primary') ?>',
									form: true,
									method: 'post',
									callback: nodeFileAfterPrimary,
									}">Set As Primary</button>
								<?php endif; ?>
						<button type="button" class="btn btn-danger btn-sm zbase-btn-action-confirm"
								data-config="{url: '<?php echo $img->actionUrl('delete') ?>',
								mode: 'yesno',
								message: 'Are you sure you want to delete this image?',
								callback: nodeFileAfterDelete
								}">Delete</button>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>