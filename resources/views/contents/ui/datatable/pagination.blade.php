<?php

$perPages = $ui->getRowsPerPages();
if($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator)
{
	$perPageRequest = zbase_request_query_input('pp', false);
	if(!empty($perPageRequest) && !zbase_is_angular())
	{
		$paginator->appends(['pp' => $perPageRequest]);
	}
	$presenter = new \Illuminate\Pagination\BootstrapThreePresenter($paginator);
	if(zbase_is_angular())
	{
		 echo str_replace(array('?page=', 'class="pagination', route('index')), array('/page/', 'class="pagination pagination-sm','#'), $presenter->render());
		//echo $paginator;
	}
	else
	{
		echo str_replace(array('class="pagination'), array('class="pagination pagination-sm'), $presenter->render());
	}
	if(!zbase_is_angular())
	{
		echo '<ul class="pagination pagination-perpage pagination-sm">';
		if($paginator->lastPage() > 1 && !empty($perPages))
		{
			echo '<li><a class="btn disabled" href="#">Rows</a></li>';
		}
		if(!empty($perPages))
		{
			foreach ($perPages as $perPage)
			{
				if($paginator->total() > $perPage)
				{
					echo '<li><a data-perpage="' . $perPage . '" '
					. 'href="' . zbase_url_from_current(['pp' => $perPage], false) . '" ' . ($paginator->perPage() == $perPage ? 'class="active"' : '') . '>' .
					$perPage . '</a>'
					. '</li>';
				}
			}
		}
		if($paginator->total() > $paginator->perPage())
		{
			echo '<li><a data-perpage="all" href="' . zbase_url_from_current(['pp' => $paginator->total()], false) . '" ' . ($paginator->perPage() > $perPageRequest ? 'class="active"' : '') . ' title="View all rows">'
			. 'View all'
			. '</a></li>';
		}
		echo '</ul>';
	}
}
