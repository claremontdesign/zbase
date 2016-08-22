<?php
/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Aug 22, 2016 8:29:03 PM
 * @file maintenance-notice.blade.php
 * @project Zbase
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */
?>
<?php if(zbase()->system()->hasScheduledDowntime() && !zbase()->system()->inMaintenance()): ?>
	<?php
	$details = zbase()->system()->scheduledDowntimeDetails();
	?>
	<div class="alert alert-warning">
		<h3>Temporary Downtime Notice.</h3>
		<p>
			<?php
			$message = null;
			if(!empty($details['maintenance-message']))
			{
				$message = $details['maintenance-message'];
			}
			if(!empty($details['start-datetime']))
			{
				$startTime = zbase_date_from_format('Y-m-d H:i:s', $details['start-datetime']);
				$endTime = zbase_date_from_format('Y-m-d H:i:s', $details['end-datetime']);
				echo str_replace(
						array('{START_TIME}', '{END_TIME}'), array(
					'<strong>' . $startTime->format('F d, Y h:i A') . '</strong>',
					'<strong>' . $endTime->format('F d, Y h:i A') . '</strong>'
						), nl2br($message));
			}
			?>
		</p>
	</div>
<?php endif; ?>