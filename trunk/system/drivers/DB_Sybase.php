<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class DB_Sybase
 *
 * Driver class for Sybase databases.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Driver
 */
class DB_Sybase extends Database
{

	/**
	 * List tables query
	 * @var string
	 */
	protected $strListTables = "SELECT name FROM sysobjects WHERE type='U' ORDER BY name";

	/**
	 * List fields query
	 * @var string
	 */
	protected $strListFields = "SELECT c.column_name, c.column_type, c.width FROM syscolumn c, systable t WHERE t.table_name='%s' AND c.table_id=t.table_id AND t.table_type='BASE'";


	/**
	 * Connect to database server and select database
	 */
	protected function connect()
	{
		$strHost = $GLOBALS['TL_CONFIG']['dbHost'];

		if ($GLOBALS['TL_CONFIG']['dbPort'])
		{
			$strHost .= ':' . $GLOBALS['TL_CONFIG']['dbPort'];
		}

		if ($GLOBALS['TL_CONFIG']['dbPconnect'])
		{
			$this->resConnection = @sybase_pconnect($strHost, $GLOBALS['TL_CONFIG']['dbUser'], $GLOBALS['TL_CONFIG']['dbPass'], $GLOBALS['TL_CONFIG']['dbCharset']);
		}

		else
		{
			$this->resConnection = @sybase_connect($strHost, $GLOBALS['TL_CONFIG']['dbUser'], $GLOBALS['TL_CONFIG']['dbPass'], $GLOBALS['TL_CONFIG']['dbCharset']);
		}

		if (is_resource($this->resConnection))
		{
			@sybase_select_db($GLOBALS['TL_CONFIG']['dbDatabase']);
		}
	}


	/**
	 * Disconnect from database
	 */
	protected function disconnect()
	{
		@sybase_close();
	}


	/**
	 * Return the last error message
	 * @return string
	 */
	protected function get_error()
	{
		return sybase_get_last_message();
	}


	/**
	 * Return a standardized array with field information
	 * 
	 * Standardized format:
	 * - name:       field name (e.g. my_field)
	 * - type:       field type (e.g. "int" or "number")
	 * - length:     field lenght (e.g. 20)
	 * - precision:  precision of a float number (e.g. 5)
	 * - null:       NULL or NOT NULL
	 * - default:    default value (e.g. "default_value")
	 * - attributes: attributes (e.g. "unsigned")
	 * - extra:      extra information (e.g. auto_increment)
	 * @param string
	 * @return string
	 * @todo This function is not tested yet, nor is the list tables and list fields statement!
	 */
	protected function list_fields($strTable)
	{
		$arrReturn = array();
		$arrFields = $this->execute(sprintf($this->strListFields, $strTable))->fetchAllAssoc();

		foreach ($arrFields as $k=>$v)
		{
			$arrReturn[$k]['name'] = $v['column_name'];
			$arrReturn[$k]['type'] = $v['column_type'];
			$arrReturn[$k]['length'] = $v['width'];
		}

		return $arrReturn;
	}


	/**
	 * Change the current database
	 * @param  string
	 * @return boolean
	 */
	protected function set_database($strDatabase)
	{
		return @sybase_select_db($strDatabase);
	}


	/**
	 * Begin a transaction
	 */
	protected function begin_transaction()
	{
		@sybase_query("BEGIN TRAN");
	}


	/**
	 * Commit a transaction
	 */
	protected function commit_transaction()
	{
		@sybase_query("COMMIT TRAN");
	}


	/**
	 * Rollback a transaction
	 */
	protected function rollback_transaction()
	{
		@sybase_query("ROLLBACK TRAN");
	}
}


/**
 * Class DB_Sybase_Statement
 *
 * Driver class for Sybase databases.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Driver
 */
class DB_Sybase_Statement extends Database_Statement
{

	/**
	 * Prepare a query and return it
	 * @param string
	 */
	protected function prepare_query($strQuery)
	{
		return $strQuery;
	}


	/**
	 * Escape a string
	 * @param  string
	 * @return string
	 */
	protected function string_escape($strString)
	{
		return "'" . str_replace("'", "''", $strString) . "'";

		/**
		 * The TYPOlight framework automatically strips slashes so do not
		 * do it again here. Uncomment if used as stand-alone library.
		 * 
		if (!get_magic_quotes_gpc())
		{
			return "'" . str_replace("'", "''", $strString) . "'";
		}

		return "'" . str_replace("\\'", "''", str_replace('\\\\', '\\', str_replace('\\"', '"', $strString))) . "'";
		*/
	}


	/**
	 * Limit the current query
	 * @param int
	 * @param int
	 */
	protected function limit_query($intRows, $intOffset)
	{
		$this->strQuery .= sprintf('SET ROWCOUNT %s; %s; SET ROWCOUNT 0;', ($intOffset + $intRows), $this->strQuery);
	}


	/**
	 * Execute the current query
	 * @return resource
	 */
	protected function execute_query()
	{
		return @sybase_query($this->strQuery);
	}


	/**
	 * Return the last error message
	 * @return string
	 */
	protected function get_error()
	{
		return sybase_get_last_message();
	}


	/**
	 * Return the number of affected rows
	 * @return int
	 */
	protected function affected_rows()
	{
		return @sybase_affected_rows();
	}


	/**
	 * Return the last insert ID
	 * @return int
	 */
	protected function insert_id()
	{
		return false;
	}


	/**
	 * Explain the current query
	 * @return array
	 */
	protected function explain_query()
	{
		return false;
	}
}


/**
 * Class DB_Sybase_Result
 *
 * Driver class for Sybase databases.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Driver
 */
class DB_Sybase_Result extends Database_Result
{

	/**
	 * Fetch the current row as enumerated array
	 * @return array
	 */
	protected function fetch_row()
	{
		return @sybase_fetch_row($this->resResult);
	}


	/**
	 * Fetch the current row as associative array
	 * @return array
	 */
	protected function fetch_assoc()
	{
		return @sybase_fetch_assoc($this->resResult);
	}


	/**
	 * Return the number of rows of the current result
	 * @return int
	 */
	protected function num_rows()
	{
		return @sybase_num_rows($this->resResult);
	}


	/**
	 * Return the number of fields of the current result
	 * @return int
	 */
	protected function num_fields()
	{
		return @sybase_num_fields($this->resResult);
	}


	/**
	 * Get column information
	 * @param  int
	 * @return object
	 */
	protected function fetch_field($intOffset)
	{
		return @sybase_fetch_field($this->resResult, $intOffset);
	}
}

?>