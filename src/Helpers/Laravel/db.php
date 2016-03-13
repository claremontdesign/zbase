<?php

/**
 * Zbase-Laravel Helpers-Db
 *
 * Functions and Helpers for db
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file db.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 *
 */

/**
 * Start transaction
 * @return void
 */
function zbase_db_transaction_start()
{
	\DB::beginTransaction();
}

/**
 * Rollback transaction
 * @return void
 */
function zbase_db_transaction_rollback()
{
	\DB::rollBack();
}

/**
 * Commit transaction
 * @return void
 */
function zbase_db_transaction_commit()
{
	\DB::commit();
}
