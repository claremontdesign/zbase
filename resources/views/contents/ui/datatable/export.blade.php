<?php
/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Sep 5, 2016 3:04:27 PM
 * @file export.blade.php
 * @project Zbase
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */
$isExportable = true; // $ui->isExportable();
if(empty($isExportable))
{
	return;
}
$maxColumns = 12;
$formats = [
	'csv' => 'CSV',
	'excel' => 'Excel',
	'pdf' => 'PDF',
];
/**
 * Selectable filters
 * export.filters.select2
 * export.filters.select1
 */
$exportableFilters = []; //$ui->exportableFilters();
$prefix = $ui->getWidgetPrefix('export');
?>
<div class="datatable-toolbar-export col-md-<?php echo $maxColumns ?>" id="<?php echo $prefix ?>Wrapper" style="margin:10px 0px;">
	<form action="<?php echo zbase_url_from_current() ?>" class="form-inline zbase-ajax-form" role="form" method="POST">
		<?php echo zbase_csrf_token_field(); ?>
		<div class="col-md-4 form-group">
			<select name="<?php echo $prefix ?>Filter[status]" class="<?php echo $prefix ?>Filters form-control input-sm">
				<option value="all">All</option>
				<option value="completed">Completed</option>
				<option value="new">New</option>
				<option value="pendingupdatedpayment">Pending</option>
			</select>
		</div>
		<div class="col-md-4 form-group">
			<select name="<?php echo $prefix ?>Format" class="form-control input-sm">
				<option value="excel">Excel</option>
			</select>
		</div>
		<div class="col-md-4 form-group">
			<button type="submit" id="<?php echo $prefix ?>ExportBtn" class="btn btn-sm blue">Export</button>
		</div>
		<input type="hidden" name="<?php echo $prefix ?>" value="1">
		<input type="hidden" name="json" value="1">
	</form>
</div>