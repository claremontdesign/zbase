<?php
$dataPrefix = $ui->getWidgetPrefix();
$prefix = $ui->getWidgetPrefix('search');
$isSearchable = $ui->isSearchable();
$searchUrl = $ui->getSearchUrl();
$queryJson = $ui->isSearchResultJson();
$queryOnLoad = $ui->isQueryOnLoad();
$searchOnLoad = $ui->isSearchOnLoad();
$tableTemplate = !empty($dataTableTemplate) ? $dataTableTemplate : zbase_view_file_contents('ui.datatable.table');
if(empty($isSearchable))
{
	return;
}
?>
<?php ob_start(); ?>
<script type="text/javascript">
	<?php echo zbase_view_compile(zbase_view_render($tableTemplate, ['ui' => $ui, 'template' => true, 'prefix' => $prefix])); ?>
	function <?php echo $prefix?>DatatableRow(i, row)
	{
		var rowString = <?php echo $prefix?>TemplateTableRow;
		var idIndex = 'alpha_id';
		jQuery.each(row, function(index, value){
			rowString = str_replace('__' + index + '__', <?php echo $prefix?>DatatableRowCast(index, value), rowString);
		});
		if(jQuery('#<?php echo $prefix?>Table tbody') > 0)
		{
			jQuery(rowString).appendTo(jQuery('#<?php echo $prefix?>Table tbody'));
		} else {
			jQuery(rowString).appendTo(jQuery('#<?php echo $prefix?>Table'));
		}
		zbase_call_function('<?php echo $prefix?>TableRowCallback', row, jQuery(rowString));
	}
	function <?php echo $prefix?>DatatableRowCast(index, value)
	{
		return value;
	}
	function <?php echo $prefix?>(r)
	{
		if(jQuery('#<?php echo $prefix?>Wrapper').length > 1)
		{
			jQuery('#<?php echo $prefix?>Wrapper .btn-toolbar').remove();
		}
		jQuery('#<?php echo $prefix?>Table').remove();
		jQuery('#<?php echo $prefix?>SearchWrapper').siblings('.btn-toolbar').eq(0).remove();
		jQuery('#<?php echo $prefix?>SearchWrapper').siblings('.datatable-empty-message').eq(0).remove();
		jQuery('#<?php echo $prefix?>SearchWrapper').siblings('table').eq(0).remove();
		if(r.<?php echo $dataPrefix?> !== undefined && r.<?php echo $dataPrefix?>.totalRows > 0)
		{
			jQuery(<?php echo $prefix?>TemplateTable).insertAfter('#<?php echo $prefix?>SearchWrapper');
			if(r.<?php echo $dataPrefix?>.rows !== undefined)
			{
				jQuery.each(r.<?php echo $dataPrefix?>.rows, function(i, row){
					<?php echo $prefix?>DatatableRow(i, row);
				});
				zbase_call_function('<?php echo $prefix?>Callback', r.<?php echo $dataPrefix?>.rows);
			} else {
				zbase_call_function('<?php echo $prefix?>Callback', r.<?php echo $dataPrefix?>.rows);
			}
		} else {
			jQuery(<?php echo $prefix?>EmptyMessage).insertAfter('#<?php echo $prefix?>SearchWrapper');
		}
	}
	function <?php echo $prefix?>beforeSendCheck(){
		var o = jQuery('#<?php echo $prefix?>query');
		var ok = o.val() != '';
		o.closest('.form-group').removeClass('has-error');
		if(!ok)
		{
			o.closest('.form-group').addClass('has-error');
		}
		return ok;
	}
	function <?php echo $prefix?>GoSearch()
	{
		var c = {url: '<?php echo $searchUrl?>',
					form: true,
					method: 'post',
					beforeSendCheck: <?php echo $prefix?>beforeSendCheck,
					callback: <?php echo $prefix?>,
					elements: [
							'#<?php echo $prefix?>query',
							'#<?php echo $prefix?>json',
						]
					};
					zbase_ajax(c);
					<?php if(!empty($searchOnLoad)):?>
						saveToLocalStorage('<?php echo $prefix?>query', jQuery('#<?php echo $prefix?>query').val());
					<?php endif;?>
	}
	function <?php echo $prefix?>init()
	{
		<?php if(!empty($searchOnLoad)):?>
			var sq = getFromLocalStorage('<?php echo $prefix?>query');
			if(!empty(sq))
			{
				jQuery('#<?php echo $prefix?>query').val(sq);
				<?php echo $prefix?>GoSearch();
			}
		<?php endif;?>
		jQuery('#<?php echo $prefix?>submitbutton').click(function(){<?php echo $prefix?>GoSearch();});
		jQuery('#<?php echo $prefix?>resetbutton').click(function(){jQuery('#<?php echo $prefix?>query').val('');<?php echo $prefix?>GoSearch();});
		jQuery('#<?php echo $prefix?>query').on('keypress', function (event) {
			 if(event.which === 13){
				<?php echo $prefix?>GoSearch();
				jQuery(this).closest('form').submit(function(e){e.preventDefault();});
			 }
	   });
	}
</script>
<?php
$script = ob_get_contents();
ob_end_clean();
zbase_view_script_add($prefix . 'search', $script, false);
zbase_view_script_add($prefix . 'searchinit', $prefix . 'init();', true);
?>
<?php if(!empty($isSearchable)): ?>
	<div id="<?php echo $prefix?>SearchWrapper">
		<div class="form-group">
			<input id="<?php echo $prefix?>query" type="text" class="form-control datatable-search-query" name="<?php echo $prefix?>Query" value="" placeholder="<?php echo $ui->searchTextPlaceholder()?>"/>
		</div>
		<div class="form-group">
			<button id="<?php echo $prefix?>submitbutton" type="button" class="btn btn-success">Search</button>
			<button id="<?php echo $prefix?>resetbutton" type="button" class="btn btn-default">Reset</button>
		</div>
	</div>
<hr />
<?php endif; ?>