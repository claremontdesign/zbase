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
<?php if(zbase()->telegram()->isEnabled()):?>
	<?php echo zbase_view_render(zbase_view_file_module('system.views.telegram-user', 'system', 'zbase'), ['user' => zbase_auth_user()])->render();?>
<?php endif;?>