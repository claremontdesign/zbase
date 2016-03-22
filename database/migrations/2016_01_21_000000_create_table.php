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
		$this->down();
		$dbTblPrefix = zbase_db_prefix();
		$entities = zbase_config_get('entity', []);
		if(!empty($entities))
		{
			foreach ($entities as $entity)
			{
				$enable = zbase_data_get($entity, 'enable', false);
				if(empty($enable))
				{
					continue;
				}
				/**
				 * acceptable value is false|string|classname
				 * if null, will pass the entity
				 */
				$model = zbase_data_get($entity, 'model', null);
				if(is_null($model))
				{
					continue;
				}
				$modelName = zbase_class_name($model);
				if(method_exists($modelName, 'entityConfiguration'))
				{
					$entity = $modelName::entityConfiguration($entity);
				}
				$tableName = zbase_data_get($entity, 'table.name', null);
				if(empty($tableName))
				{
					continue;
				}
				$columns = zbase_data_get($entity, 'table.columns', []);
				$nodeable = zbase_data_get($entity, 'table.nodeable', false);
				if(!empty($nodeable))
				{
					$columns = array_merge_recursive($columns, \Zbase\Entity\Laravel\Node\Node::nodeDefaultColumns());
				}
				$nesteable = zbase_data_get($entity, 'table.nesteable', false);
				if(!empty($nesteable))
				{
					$columns = array_merge_recursive($columns, \Zbase\Entity\Laravel\Node\Nested::nestedNodeDefaultColumns());
				}
				if(method_exists($modelName, 'tableColumns'))
				{
					$columns = $modelName::tableColumns($columns);
				}
				if(empty($columns))
				{
					continue;
				}
				if(!empty($enable) && !empty($tableName))
				{
					Schema::create($tableName, function(Blueprint $table) use($columns, $entity)
						{
//						if(is_string($columns) && is_a($columns, true))
//						{
//							$className = zbase_class_name($columns);
//							$columns = $className::columns();
//						}
//						if($columns instanceof \Closure)
//						{
//							$columns = $columns();
//						}
						$tableTye = zbase_data_get($entity, 'table.type', 'InnoDB');
						$table->engine = $tableTye;
						$primaryKey = zbase_data_get($entity, 'table.primaryKey', null);
						if(!is_null($primaryKey))
						{
							$table->increments($primaryKey);
						}
						$pivotable = zbase_data_get($entity, 'table.pivotable', false);
						if(!empty($pivotable))
						{
							$pivotEntityTable = zbase_config_get('entity.' . $pivotable['entity'], null);
							$nestedEntityTable = zbase_config_get('entity.' . $pivotable['nested'], null);
							if(!is_null($pivotEntityTable) && !is_null($nestedEntityTable))
							{
								$entityPrimaryKey = zbase_data_get($pivotEntityTable, 'table.primaryKey', null);
								$nestedPrimaryKey = zbase_data_get($nestedEntityTable, 'table.primaryKey', null);
								$columns = [];
								$columns[$entityPrimaryKey] = [
									'length' => 16,
									'hidden' => false,
									'fillable' => true,
									'type' => 'integer',
									'unsigned' => true,
									'foreign' => [
										'table' => zbase_data_get($pivotEntityTable, 'table.name', null),
										'column' => $entityPrimaryKey,
										'onDelete' => 'cascade'
									],
									'comment' => zbase_data_get($pivotEntityTable, 'table.description', null) . ' ID'
								];
								$columns[$nestedPrimaryKey] = [
									'length' => 16,
									'hidden' => false,
									'fillable' => true,
									'type' => 'integer',
									'unsigned' => true,
									'foreign' => [
										'table' => zbase_data_get($nestedEntityTable, 'table.name', null),
										'column' => $nestedPrimaryKey,
										'onDelete' => 'cascade'
									],
									'comment' => zbase_data_get($nestedEntityTable, 'table.description', null) . ' ID'
								];
							}
						}
						// <editor-fold defaultstate="collapsed" desc="Columns">
						if(!empty($columns))
						{
							foreach ($columns as $columnName => $column)
							{
								$columnName = zbase_data_get($column, 'name', $columnName);
								if($columnName == $primaryKey)
								{
									continue;
								}
								$type = zbase_data_get($column, 'type', 'string');
								$defaultLength = 16;
								if($type == 'string')
								{
									$defaultLength = 255;
								}
								elseif($type == 'integer' || $type == 'decimal' || $type == 'double')
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
										$length = zbase_data_get($column, 'length', 16);
										$decimal = zbase_data_get($column, 'decimal', 2);
										$col = $table->decimal($columnName, $length, $decimal);
									}
									elseif($type == 'double')
									{
										$length = zbase_data_get($column, 'length', 16);
										$decimal = zbase_data_get($column, 'decimal', 2);
										$col = $table->double($columnName, $length, $decimal);
									}
									elseif($type == 'json')
									{
										$col = $table->text($columnName);
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
						}
						// </editor-fold>
						$orderable = zbase_data_get($entity, 'table.orderable', false);
						if(!empty($orderable))
						{
							$table->integer('position')->unsigned()->comment('Row Order/Position');
						}
						$optionable = zbase_data_get($entity, 'table.optionable', false);
						if(!empty($optionable))
						{
							$table->text('option')->nullable();
						}
						$sluggable = zbase_data_get($entity, 'table.sluggable', false);
						if(!empty($sluggable))
						{
							$table->string('slug', 255)->nullable()->comment('Slug/URL key');
							$table->index('slug');
							$table->unique('slug');
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
						\DB::statement("ALTER TABLE `{$tableName}` COMMENT='{$description}'");
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
		if(!zbase_is_dev())
		{
			echo 'You are in PRODUCTION Mode. Cannot drop tables.';
			return false;
		}
		$entities = zbase_config_get('entity', []);
		if(!empty($entities))
		{
			\DB::statement('SET FOREIGN_KEY_CHECKS = 0');
			foreach ($entities as $entity)
			{
				$enable = zbase_data_get($entity, 'enable', false);
				$model = zbase_data_get($entity, 'model', null);
				if(is_null($model))
				{
					continue;
				}
				$modelName = zbase_class_name($model);
				if(method_exists($modelName, 'entityConfiguration'))
				{
					$entity = $modelName::entityConfiguration($entity);
				}
				$tableName = zbase_data_get($entity, 'table.name', null);
				if(!empty($enable) && !empty($tableName))
				{
					if(Schema::hasTable($tableName))
					{
						Schema::drop($tableName);
					}
				}
			}
			\DB::statement('SET FOREIGN_KEY_CHECKS = 1');
		}
	}

}
