<?php

/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Aug 23, 2016 6:00:01 PM
 * @file notifications.blade.php
 * @project Zbase
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */
?>
<?php if(zbase()->telegram()->isEnabled() && zbase_config_get('modules.account.widgets.account.tab.telegram', true)):?>
	<?php echo zbase_view_render(zbase_view_file_module('account.views.telegram-user', 'account', 'zbase'), ['user' => zbase_auth_user()]);?>
<?php endif;?>