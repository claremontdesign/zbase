<?php

/**
 * Zbase
 *
 * Zbase Dynamic DB Table Creation
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @project Zbase
 */

/**
 * http://laravel.com/docs/5.1/migrations
 *
 * Schema::hasTable('tableName')
 * Schema::hasColumn('tableName', 'columnName')
 *
 * Schema::drop('users');
 * Schema::dropIfExists('users');
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTable extends Migration
{

	/**
	 * Run the migrations dynamically
	 *
	 * @return void
	 */
	public function up()
	{
		$dbTblPrefix = zbase_db_prefix();
		$entities = zbase_config_get('entity', []);
		if(!empty($entities))
		{
			foreach ($entities as $entity)
			{
				$enable = zbase_data_get($entity, 'enable', false);
				$tableName = zbase_data_get($entity, 'table.name', null);
				$columns = zbase_data_get($entity, 'table.columns', []);
				// published_status 1=published|2=not published|3=draft
				if(!empty($enable) && !empty($tableName))
				{
					Schema::create($tableName, function(Blueprint $table) use($columns, $entity)
						{
						$primaryKey = zbase_data_get($entity, 'table.primaryKey', null);
						if(!is_null($primaryKey))
						{
							$table->increments($primaryKey);
						}
						foreach ($columns as $columnName => $column)
						{
							$columnName = zbase_data_get($column, 'name', $columnName);
							if($columnName == $primaryKey)
							{
								continue;
							}
							$type = zbase_data_get($column, 'type', 'string');
							if($type == 'string')
							{
								$defaultLength = 255;
							}
							elseif($type == 'integer')
							{
								$defaultLength = 16;
							}
							$index = zbase_data_get($column, 'index', false);
							$unique = zbase_data_get($column, 'unique', false);
							$length = zbase_data_get($column, 'length', $defaultLength);
							$comment = zbase_data_get($column, 'comment', false);
							$default = zbase_data_get($column, 'default', null);
							$unsigned = zbase_data_get($column, 'unsigned', false);
							$nullable = zbase_data_get($column, 'nullable', false);
							if(!is_null($type))
							{
								if($type == 'decimal')
								{
									$decimal = zbase_data_get($column, 'decimal', 2);
									$col = $table->decimal($columnName, $length, $decimal);
								}
								elseif($type == 'double')
								{
									$decimal = zbase_data_get($column, 'decimal', 2);
									$col = $table->double($columnName, $length, $decimal);
								}
								elseif($type == 'integer')
								{
									$col = $table->integer($columnName);
								}
								elseif($type == 'bigint')
								{
									$col = $table->bigInteger($columnName);
								}
								elseif($type == 'tinyint')
								{
									$col = $table->tinyInteger($columnName);
								}
								elseif($type == 'enum')
								{
									$enum = zbase_data_get($column, 'enum', []);
									if(!empty($enum))
									{
										$enums = [];
										foreach ($enum as $eV => $eK)
										{
											$enums[] = $eV;
										}
										$col = $table->enum($columnName, $enums);
									}
								}
								else
								{
									if(empty($length))
									{
										$col = $table->{$type}($columnName);
									}
									else
									{
										$col = $table->{$type}($columnName, $length);
									}
								}
								if(!is_null($default))
								{
									$col->default($default);
								}
								else
								{
									$col->default(null);
								}
								if(!empty($nullable))
								{
									$col->nullable();
								}
								if(!empty($index))
								{
									$table->index($columnName);
								}
								if(!empty($unique))
								{
									$table->unique($columnName);
								}
								if(!empty($unsigned))
								{
									$col->unsigned();
								}
								if(!empty($comment))
								{
									$col->comment($comment);
								}
								$foreignTable = zbase_data_get($column, 'foreign.table', null);
								$foreignColumn = zbase_data_get($column, 'foreign.column', null);
								$foreignOnDelete = zbase_data_get($column, 'foreign.onDelete', null);
								$foreignOnUpdate = zbase_data_get($column, 'foreign.onUpdate', null);
								if(!is_null($foreignTable) && !is_null($foreignColumn))
								{
									$col = $table->foreign($columnName)->references($foreignColumn)->on($foreignTable);
									if(!is_null($foreignOnDelete))
									{
										$col->onDelete($foreignOnDelete);
									}
									if(!is_null($foreignOnUpdate))
									{
										$col->onUpdate($foreignOnUpdate);
									}
								}
							}
						}
						$timestamp = zbase_data_get($entity, 'table.timestamp', false);
						if($timestamp)
						{
							$table->timestamps();
						}
						$softDelete = zbase_data_get($entity, 'table.softDelete', false);
						if(!empty($softDelete))
						{
							$table->softDeletes();
						}
						$rememberToken = zbase_data_get($entity, 'table.rememberToken', false);
						if(!empty($rememberToken))
						{
							$table->rememberToken();
						}
						$alphaId = zbase_data_get($entity, 'table.alphaId', false);
						if(!empty($alphaId))
						{
							$table->string('alpha_id', 64)->nullable();
							$table->unique('alpha_id');
						}
						$polymorphic = zbase_data_get($entity, 'table.polymorphic', []);
						if(!empty($polymorphic))
						{
							$polymorphicPrefix = zbase_data_get($entity, 'table.polymorphic.prefix', 'taggable');
							$table->integer($polymorphicPrefix . '_id')->nullable();
							$table->string($polymorphicPrefix . '_type', 64)->nullable();
						}
					});
					$description = zbase_data_get($entity, 'table.description', null);
					if(!is_null($description))
					{
						$tableName = $dbTblPrefix . $tableName;
						// DB::select(DB::raw("ALTER TABLE `{$tableName}` COMMENT='{$description}'"));
					}
				}
			}
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$entities = zbase_config_get('entity', []);
		if(!empty($entities))
		{
			DB::select(DB::raw("SET FOREIGN_KEY_CHECKS=0"));
			foreach ($entities as $entity)
			{
				$enable = zbase_data_get($entity, 'enable', false);
				$tableName = zbase_data_get($entity, 'table.name', null);
				if(!empty($enable) && !empty($tableName))
				{
					if(Schema::hasTable($tableName))
					{
						Schema::drop($tableName);
					}
				}
			}
			DB::select(DB::raw("SET FOREIGN_KEY_CHECKS=1"));
		}
	}

}
