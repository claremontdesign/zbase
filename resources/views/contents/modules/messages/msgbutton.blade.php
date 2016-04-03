<?php
/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Apr 1, 2016 10:21:39 PM
 * @file msgbutton.blade.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */
?>
<!--<div class="alert alert-danger">Are you sure you want to delete?</div>-->
<div class="btn-group" role="group" aria-label="Message buttons">
	<button type="button" class="btn btn-danger btn-sm zbase-btn-action-confirm"
			data-config="{url: '<?php echo zbase_url_from_route('messages', array('action' => 'trash', 'id' => $msg->alphaId())) ?>',
			mode: 'yesno',
			message: 'Are you sure you want to delete this message?',
			}">Delete</button>
</div>