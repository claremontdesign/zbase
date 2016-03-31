<?php
$sortables = $ui->getSortableColumns();
$currentSorting = $ui->getRequestSorting();
$hasSortables = false;
$sortingOptions = [];
foreach ($sortables as $column => $sortable)
{
	$sort = zbase_data_get($sortable, 'options.sort');
	if(!empty($sort) && is_array($sort))
	{
		foreach ($sort as $s)
		{
			if(!empty($s['label']))
			{
				$selected = '';
				if(!empty($currentSorting) && array_key_exists($column, $currentSorting))
				{
					$selected = ' selected="selected"';
					$currentDir = $currentSorting[$column];
					$params = [
						'sort' => [$column => (strtolower($currentDir) == 'asc' ? 'desc' : 'asc')]
					];
				}
				else
				{
					$params = [
						'sort' => [$column => 'asc']
					];
				}
				$url = zbase_url_from_current($params);
				$sortingOptions[] = '<option ' . $selected . ' value="' . $url . '">' . $s['label'] . '</option>';
			}
		}
	}
}
?>
<?php if(!empty($sortingOptions)): ?>
	<select onchange="window.location = jQuery(this).val();">
		<?php echo implode('', $sortingOptions); ?>
	</select>
<?php endif; ?>