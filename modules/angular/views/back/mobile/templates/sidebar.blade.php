<?php
/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Jul 8, 2016 1:13:57 PM
 * @file sidebar.blade.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */
?>
<div class="scrollable">
  <h1 class="scrollable-header app-name"><?php echo zbase_config_get('page.site.name', 'Zbase') ?> <!--<small>1.2</small>--></h1>
	<div class="scrollable-content">
		<div class="list-group" ui-turn-off='uiSidebarLeft'>
			<a class="list-group-item" href="#/">
				<i class="fa fa-home"></i>
				Dashboard
			</a>
			<?php
			$modules = zbase()->modules();
			foreach ($modules as $module)
			{
				if($module->isEnable())
				{
					if($module->hasNavigation(zbase_section()))
					{
						$navigation = $module->getNavigation(zbase_section());
						$navigation->isAngular(true);
						$navigation->setAttribute('format', '<a class="list-group-item {CLASS_ISACTIVE}" href="{URL}">{LABEL}</a>');
						echo $navigation;
					}
				}
			}
			?>
			<?php if(zbase_auth_has()): ?>
				<a class="list-group-item" href="<?php echo zbase_angular_url('admin.account', []) ?>">
					<i class="fa fa-user"></i>
					Account
				</a>
				<a class="list-group-item" href="<?php echo zbase_angular_url('admin.logout', []) ?>" ng-click="logout()">
					<i class="fa fa-key"></i>
					Logout
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>