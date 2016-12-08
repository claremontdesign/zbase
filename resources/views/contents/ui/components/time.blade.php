<?php
$date = !empty($date) ? $date : null;
if(is_string($date))
{
	$date = zbase_date_from_db($date);
}
?>
<time datetime="<?php echo $date->format('Y-m-d H:i:s')?>" title="<?php echo $date->format('F d, Y h:i A')?>"><?php echo $date->format('F d, Y h:i A')?><?php // echo zbase_date_human($date) ?></time>