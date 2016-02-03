<?php

/**
 * Entities configuration
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file entity.php
 * @project Zbase
 * @package config
 *
 *
 *
 * entity.$entityName.enable = boolean
 * entity.$entityName.model
 * entity.$entityName.relations
 * entity.$entityName.relations.entityName
 * entity.$entityName.relations.entityName.type
 * entity.$entityName.relations.entityName.keys
 * entity.$entityName.relations.entityName.keys.local
 * entity.$entityName.relations.entityName.keys.foreign
 * entity.$entityName.relations.entityName.pivot
 * entity.$entityName.relations.entityName.keys.foreignOne
 * entity.$entityName.relations.entityName.keys.foreignTwo
 * entity.$entityName.data
 * entity.$entityName.data.defaults = array of data to be inserted as default value
 * entity.$entityName.data.factory
 * entity.$entityName.data.factory.enable
 * entity.$entityName.data.factory.rows
 * entity.$entityName.data.factory.dependent = true, data will not be inserted directly
 * entity.$entityName.table.name = The Table Name
 * entity.$entityName.table.primaryKey
 * entity.$entityName.table.sampleData = boolean
 * entity.$entityName.table.softDelete = boolean
 * entity.$entityName.table.timestamps = boolean
 * entity.$entityName.table.compositeKeys = boolean
 * entity.$entityName.table.columns
 * entity.$entityName.table.columns.$columnName
 * entity.$entityName.table.columns.$columnName.hidden = boolean
 * entity.$entityName.table.columns.$columnName.index = boolean
 * entity.$entityName.table.columns.$columnName.fillable = boolean
 * entity.$entityName.table.columns.$columnName.type = https://laravel.com/docs/5.1/migrations#creating-tables
 * entity.$entityName.table.columns.$columnName.unique = boolean
 * entity.$entityName.table.columns.$columnName.length
 * entity.$entityName.table.columns.$columnName.comment
 * entity.$entityName.table.columns.$columnName.default = Default value
 * entity.$entityName.table.columns.$columnName.unsigned = boolean
 * entity.$entityName.table.columns.$columnName.nullable = boolean
 * entity.$entityName.table.columns.$columnName.position = integer
 * entity.$entityName.table.columns.$columnName.foreign
 * entity.$entityName.table.columns.$columnName.foreign.table
 * entity.$entityName.table.columns.$columnName.foreign.column
 * entity.$entityName.table.columns.$columnName.foreign.onDelete
 * entity.$entityName.table.columns.$columnName.foreign.onChange
 *
 */
return array_merge(require_once __DIR__ . '/entities/user.php');
