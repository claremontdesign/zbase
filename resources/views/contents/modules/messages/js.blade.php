<?php
/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Apr 1, 2016 10:16:12 PM
 * @file js.blade.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */
?>
<script type="text/javascript">
	var Messages = function () {
	_this = this;
	_this._url = '<?php echo zbase_url_from_route('messages', ['action' => 'action', 'id' => 'id']) ?>';
	return {
		init: function () {},
		trashMessage: function(id){
			zbase_ajax_post(_this._url.replace('action','trash').replace('id',id), {}, function(data){});
		},
		trashMessages: function(selector){

		}
	}
	}();
</script>