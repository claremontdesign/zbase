<?php
/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Aug 23, 2016 4:12:15 PM
 * @file accountinfo.blade.php
 * @project ZbaseAgse
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */
$addressCoverage = null;
$roleTitle = $user->roleTitle();
$isAdmin = zbase_auth_user()->isAdmin();
$contents = [];
$contents[] = [
	'position' => 10,
	'content' => function() use ($user){
		return zbase_view_render(zbase_view_file_module('account.views.accountInformation', 'account', 'zbase'), ['user' => $user]);
	}
];
$contents = zbase_module_widget_contents('account', 'account', zbase_section(), $isAdmin, 'informationInner', $contents);
?>
<div class="row">
	<div class="col-md-2">
		<img class="img-responsive thumbnail" src="<?php echo $user->avatarUrl()?>" alt="<?php echo $user->displayName()?>">
	</div>
	<div class="col-md-10">
		<?php echo zbase_module_widget_render_contents($contents);?>
	</div>
</div>