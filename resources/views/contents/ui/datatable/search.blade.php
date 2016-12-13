<?php
zbase_view_plugin_load('bootstrap-datetime');
$dataPrefix = $ui->getWidgetPrefix();
$prefix = $ui->getWidgetPrefix('search');
$exportPrefix = $ui->getWidgetPrefix('export');
$isSearchable = $ui->isSearchable();
$searchUrl = $ui->getSearchUrl();
$queryJson = $ui->isSearchResultJson();
$queryOnLoad = $ui->isQueryOnLoad();
$searchOnLoad = $ui->isSearchOnLoad();
$tableTemplate = !empty($dataTableTemplate) ? $dataTableTemplate : $ui->searchResultsTemplate();
$paginationLoadMore = $ui->hasPaginationLoadMore();
$hasFilters = false;
$filterPrefix = $ui->getWidgetPrefix('filter');
$columns = $ui->getProcessedColumns();
foreach ($columns as $column)
{
	if($column->filterable())
	{
		$hasFilters = true;
		break;
	}
}
if(!empty($isSearchable))
{
	$searchableInputs = true;
}
if($paginationLoadMore)
{
	$searchableInputs = false;
	$isSearchable = true;
}
if(empty($isSearchable) && empty($hasFilters))
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
			if(value == null)
			{
				value = '';
			}
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
			// jQuery('#<?php echo $prefix?>Wrapper .btn-toolbar').remove();
		}
		if(jQuery('#<?php echo $prefix?>Table tbody').length > 0)
		{
			jQuery('#<?php echo $prefix?>Table tbody').empty();
		} else {
			if(jQuery('#<?php echo $prefix?>Table').length > 0)
			{
				jQuery('#<?php echo $prefix?>Table').remove();
			}
		}
		jQuery('#<?php echo $prefix?>SearchWrapper').siblings('.datatable-empty-message').eq(0).remove();
		<?php if(empty($hasFilters)):?>
			jQuery('#<?php echo $prefix?>SearchWrapper').siblings('table').eq(0).remove();
		<?php endif;?>
		var <?php echo $prefix?>Toolbar = jQuery('#<?php echo $prefix?>SearchWrapper').siblings('.btn-toolbar').eq(0);
		var <?php echo $prefix?>Pagination = <?php echo $prefix?>Toolbar.find('.pagination-pages');
		if(r.<?php echo $dataPrefix?> !== undefined && r.<?php echo $dataPrefix?>.totalRows > 0)
		{
			<?php echo $prefix?>TemplateTable = str_replace('__totalRows__',r.<?php echo $dataPrefix?>.totalRows,<?php echo $prefix?>TemplateTable);
			<?php echo $prefix?>Toolbar.show();
			if(<?php echo $prefix?>Toolbar.length > 0)
			{
				<?php if(empty($hasFilters)):?>
					<?php echo $prefix?>Toolbar.after(<?php echo $prefix?>TemplateTable);
				<?php endif;?>
			} else {
				jQuery(<?php echo $prefix?>TemplateTable).insertAfter('#<?php echo $prefix?>SearchWrapper')
			}
			if(r.<?php echo $dataPrefix?>.rows !== undefined)
			{
				jQuery.each(r.<?php echo $dataPrefix?>.rows, function(i, row){
					<?php echo $prefix?>DatatableRow(i, row);
				});
				zbase_call_function('<?php echo $prefix?>Callback', r.<?php echo $dataPrefix?>.rows);
			} else {
				zbase_call_function('<?php echo $prefix?>Callback', r.<?php echo $dataPrefix?>.rows);
			}
			/**
			 * pagination
			 */
			<?php echo $prefix?>Toolbar.find('.pagination-view-all').parent('li').hide();
			<?php echo $prefix?>Toolbar.find('.pagination-pages').hide();
			<?php echo $prefix?>Toolbar.find('.pagination-perpage').hide();
			if(r.<?php echo $dataPrefix?>.maxPage !== undefined && r.<?php echo $dataPrefix?>.maxPage > 1)
			{
				<?php echo $prefix?>Toolbar.find('.pagination-pages').show();
				<?php echo $prefix?>Toolbar.find('.pagination-perpage').show();
				var <?php echo $prefix?>CurPage = r.<?php echo $dataPrefix?>.currentPage;
				<?php echo $prefix?>Pagination.find('li').remove();
				var maxPageToDisplay = 8;
				var pageStarts = 1;
				var pageEnds = r.<?php echo $dataPrefix?>.maxPage;
				pageStarts = <?php echo $prefix?>CurPage - 4;
				pageEnds = <?php echo $prefix?>CurPage + 4;
				if(pageStarts < 1)
				{
					pageStarts = 1;
				}
				if(pageEnds > r.<?php echo $dataPrefix?>.maxPage)
				{
					pageEnds = r.<?php echo $dataPrefix?>.maxPage;
				}
				if(<?php echo $prefix?>CurPage > 5)
				{
					<?php echo $prefix?>Pagination.append('<li><a data-page="1" href="?page=1" class="<?php echo $dataPrefix?>page">1</a></li>');
				}
				if(<?php echo $prefix?>CurPage > 1)
				{
					<?php echo $prefix?>Pagination.append('<li><a data-page="'+ (<?php echo $prefix?>CurPage-1) +' "href="?page=' + (<?php echo $prefix?>CurPage-1) + '" class="<?php echo $dataPrefix?>page">&laquo;</a></li>');
				}
				for(var page = pageStarts; page <= pageEnds; page++)
				{
					if(page == r.<?php echo $dataPrefix?>.currentPage)
					{
						<?php echo $prefix?>Pagination.append('<li class="active"><span>' + page + '</span></li>');
					} else {
						<?php echo $prefix?>Pagination.append('<li><a data-page="'+page+'" href="?page=' + page + '" class="<?php echo $dataPrefix?>page">' + page + '</a></li>');
					}
				}
				if(<?php echo $prefix?>CurPage < r.<?php echo $dataPrefix?>.maxPage)
				{
					<?php echo $prefix?>Pagination.append('<li><a data-page="'+ (<?php echo $prefix?>CurPage+1) +'" href="?page=' + (<?php echo $prefix?>CurPage+1) + '" class="<?php echo $dataPrefix?>page">&raquo;</a></li>');
				}
				if(r.<?php echo $dataPrefix?>.maxPage > pageEnds)
				{
					<?php echo $prefix?>Pagination.append('<li><a data-page="'+ (r.<?php echo $dataPrefix?>.maxPage) +'" href="?page=' + (r.<?php echo $dataPrefix?>.maxPage) + '" class="<?php echo $dataPrefix?>page">'+ r.<?php echo $dataPrefix?>.maxPage +'</a></li>');
				}
				jQuery('.<?php echo $dataPrefix?>page').unbind('click').click(function(e){
					e.preventDefault();
					 <?php echo $prefix?>GoSearch(jQuery(this).attr('href'));
					<?php echo $prefix?>CurPage = jQuery(this).attr('data-page');
				});
				<?php echo $prefix?>Toolbar.find('.pagination-view-all').parent('li').show();
				<?php echo $prefix?>Toolbar.find('.pagination-view-all').unbind('click').click(function(e){
					e.preventDefault();
					 <?php echo $prefix?>GoSearch('?pp=' + r.<?php echo $dataPrefix?>.totalRows);
				});
				jQuery('#<?php echo $prefix ?>totalRows').text(r.<?php echo $dataPrefix?>.totalRows);
			}
			jQuery('.<?php echo $exportPrefix ?>Filters').remove();
			if(jQuery('#<?php echo $exportPrefix ?>SearchQuery').length == 0)
			{
				<?php foreach ($columns as $column): ?>
					<?php if($column->filterable()):?>
						<?php foreach($column->filterIds() as $filterId):?>
							jQuery('#<?php echo $exportPrefix ?>Wrapper').find('form').append('<input id="<?php echo $exportPrefix . $filterId ?>" type="hidden" value="' + jQuery('#<?php echo $filterId?>').val() + '" name="<?php echo $filterId?>" />');
						<?php endforeach;?>
					<?php endif;?>
				<?php endforeach;?>
				jQuery('#<?php echo $exportPrefix ?>Wrapper').find('form').append('<input id="<?php echo $exportPrefix ?>SearchQuery" type="hidden" value="' + jQuery('#<?php echo $prefix?>query').val() + '" name="<?php echo $prefix?>Query" />');
			}
			<?php foreach ($columns as $column): ?>
				<?php if($column->filterable()):?>
					<?php foreach($column->filterIds() as $filterId):?>
						jQuery('#<?php echo $exportPrefix . $filterId?>').val(jQuery('#<?php echo $filterId?>').val());
					<?php endforeach;?>
				<?php endif;?>
			<?php endforeach;?>
			jQuery('#<?php echo $exportPrefix ?>SearchQuery').val(jQuery('#<?php echo $prefix?>query').val());
		} else {
			jQuery(<?php echo $prefix?>EmptyMessage).insertAfter('#<?php echo $prefix?>SearchWrapper');
			<?php echo $prefix?>Toolbar.hide();
		}
		// Toolbar

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
	function <?php echo $prefix?>GoSearch(url)
	{
		var c = {url: url !== undefined ? url : '<?php echo $searchUrl?>',
					form: true,
					method: 'post',
					beforeSendCheck: <?php echo $prefix?>beforeSendCheck,
					callback: <?php echo $prefix?>,
					elements: [
							'#<?php echo $prefix?>query',
							<?php if(!empty($hasFilters)):?>
								<?php foreach ($columns as $column): ?>
									<?php if($column->filterable()):?>
										<?php foreach($column->filterIds() as $filterId):?>
											'<?php echo '#' . $filterId?>',
										<?php endforeach;?>
									<?php endif;?>
									<?php endforeach;?>
							<?php endif;?>
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
		jQuery('.element-data-filter-date').parent().datepicker().on('changeDate', function(e) {
			<?php echo $prefix?>GoSearch();
		});
		jQuery('select.element-data-filter').not('.element-data-filter-date').change(function(e){
			e.preventDefault();
			<?php echo $prefix?>GoSearch();
		});
		jQuery('input.element-data-filter').not('.element-data-filter-date').keypress(function(e){
			if (e.which == 13) {
				e.preventDefault();
				<?php echo $prefix?>GoSearch();
			}
		});
		jQuery('#<?php echo $prefix?>submitbutton').click(function(){<?php echo $prefix?>GoSearch();});
		<?php if(!empty($hasFilters)):?>
			var hasFilters = false;
			jQuery('#<?php echo $filterPrefix?>FilterBtn').click(function(){
				<?php foreach ($columns as $column): ?>
					<?php if($column->filterable()):?>
						<?php foreach($column->filterIds() as $filterId):?>
							saveToLocalStorage('<?php echo $filterId?>', jQuery('#<?php echo $filterId?>').val());
						<?php endforeach;?>
					<?php endif;?>
				<?php endforeach;?>
				<?php echo $prefix?>GoSearch();
			});
			jQuery('#<?php echo $filterPrefix?>FilterClearBtn').click(function(){
				<?php foreach ($columns as $column): ?>
					<?php if($column->filterable()):?>
						<?php foreach($column->filterIds() as $filterId):?>
							jQuery('#<?php echo $filterId?>').val('');
							removeFromLocalStorage('<?php echo $filterId?>');
						<?php endforeach;?>
					<?php endif;?>
				<?php endforeach;?>
				location.reload();
			});
			<?php foreach ($columns as $column): ?>
				<?php if($column->filterable()):?>
					<?php foreach($column->filterIds() as $filterId):?>
						var fV = getFromLocalStorage('<?php echo $filterId?>');
						if(!empty(fV))
						{
							hasFilters = true;
							jQuery('#<?php echo $filterId?>').val(fV);
						}
					<?php endforeach;?>
				<?php endif;?>
			<?php endforeach;?>
			if(hasFilters)
			{
				<?php echo $prefix?>GoSearch();
			}
		<?php endif;?>
	}
</script>
<?php
$script = ob_get_contents();
ob_end_clean();
zbase_view_script_add($prefix . 'search', $script, false);
zbase_view_script_add($prefix . 'searchinit', $prefix . 'init();', true);
?>
<?php if((!empty($isSearchable) && !empty($searchableInputs))): ?>
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
<?php else: ?>
	<?php if(!empty($hasFilters)):?>
	<div id="<?php echo $prefix?>SearchWrapper"></div>
	<?php endif; ?>
<?php endif; ?>