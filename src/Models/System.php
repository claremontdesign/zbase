<?php

namespace Zbase\Models;

/**
 * Zbase-Model-Request
 *
 * Request Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Request.php
 * @project Zbase
 * @package Zbase/Model
 */
use Zbase\Models;

class System
{

	/**
	 * The current module
	 * @var Models\Module
	 */
	protected $vars = array();

	/**
	 * The Maintenance file
	 * @var string
	 */
	protected $maintenanceFile = null;

	/**
	 *
	 */
	public function __construct()
	{
		$this->maintenanceFile = zbase_storage_path() . '/maintenance';
	}

	/**
	 * Schedule a Downtime/Maintenannce
	 *
	 * @return void
	 */
	public function scheduleDowntime($data)
	{
		$file = $this->maintenanceFile . '_schedule';
		if(file_exists($file))
		{
			unlink($file);
		}
		if(!empty($data['status']))
		{
			// '2016-08-22T22:00'
			$format = 'Y-m-d\TH:i:s';
			if(strlen($data['start-datetime']) == 16)
			{
				$format = 'Y-m-d\TH:i';
			}
			$data['start-datetime'] = zbase_date_from_format($format, $data['start-datetime'])->format('Y-m-d H:i:s');
			$data['end-datetime'] = zbase_date_from_format($format, $data['end-datetime'])->format('Y-m-d H:i:s');
			file_put_contents($file, json_encode($data));
		}
		else
		{
			$this->unScheduleDowntime();
		}
	}

	/**
	 * UnSchedule Downtime
	 *
	 * @return void
	 */
	public function unScheduleDowntime()
	{
		$file = $this->maintenanceFile . '_schedule';
		if(file_exists($file))
		{
			unlink($file);
		}
	}

	/**
	 * Check if we have a scheduled downtime
	 * @return boolean
	 */
	public function hasScheduledDowntime()
	{
		$file = $this->maintenanceFile . '_schedule';
		return file_exists($file);
	}

	/**
	 * REturn the Scheduled downtime details
	 * @return array|boolean
	 */
	public function scheduledDowntimeDetails()
	{
		$file = $this->maintenanceFile . '_schedule';
		if(file_exists($file))
		{
			return json_decode(file_get_contents($file), true);
		}
		return false;
	}

	/**
	 * Public function
	 */
	public function startMaintenance()
	{
		$details = $this->scheduledDowntimeDetails();
		$file = $this->maintenanceFile . '_schedule';
		if(!empty($details['maintenance-ips']))
		{
			$details['maintenance-ips'] = $details['maintenance-ips'] . "\n" . zbase_ip();
		}
		else
		{
			$details['maintenance-ips'] = zbase_ip() . "\n";
		}
		file_put_contents($file, json_encode($details));
		file_put_contents($this->maintenanceFile, 'x');
	}

	/**
	 * Check if we are in Maintenance
	 *
	 * @return boolean
	 */
	public function inMaintenance()
	{
		$file = $this->maintenanceFile;
		if(file_exists($file))
		{
			return true;
		}
		return false;
	}

	/**
	 * Disable maintenance mode.
	 * Make site accessible
	 *
	 * @return void
	 */
	public function stopMaintenance()
	{
		$file = $this->maintenanceFile;
		$this->unScheduleDowntime();
		if(file_exists($file))
		{
			unlink($file);
		}
		return false;
	}

	/**
	 * Check if the Current IP is exempted from the Maintenance
	 *
	 * @return boolean
	 */
	public function checkIp()
	{
		$details = $this->scheduledDowntimeDetails();
		if(!empty($details))
		{
			$ips = explode("\n", $details['maintenance-ips']);
			return in_array(zbase_ip(), $ips);
		}
		return false;
	}

}
