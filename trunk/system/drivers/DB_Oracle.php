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
 * Class DB_Oracle
 *
 * Driver class for Oracle databases.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Driver
 */
class DB_Oracle extends Database
{

	/**
	 * List tables query
	 * @var string
	 */
	protected $strListTables = "SELECT table_name, table_type FROM cat WHERE table_type='TABLE'";

	/**
	 * List fields query
	 * @var string
	 */
	protected $strListFields = "SELECT cname, coltype, width, SCALE, PRECISION, NULLS, DEFAULTVAL FROM col WHERE tname='%s' ORDER BY colno";


	/**
	 * Connect to database server and select database
	 */
	protected function connect()
	{
		if ($GLOBALS['TL_CONFIG']['dbPconnect'])
		{
			$this->resConnection = @oci_connect($GLOBALS['TL_CONFIG']['dbUser'], $GLOBALS['TL_CONFIG']['dbPass'], '', $GLOBALS['TL_CONFIG']['dbCharset']);
		}

		else
		{
			$this->resConnection = @oci_connect($GLOBALS['TL_CONFIG']['dbUser'], $GLOBALS['TL_CONFIG']['dbPass'], '', $GLOBALS['TL_CONFIG']['dbCharset']);
		}
	}


	/**
	 * Disconnect from database
	 */
	protected function disconnect()
	{
		@oci_close($this->resConnection);
	}


	/**
	 * Return the last error message
	 * @return string
	 */
	protected function get_error()
	{
		$arrError = oci_error();
		return $arrError['message'];
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
	 */
	protected function list_fields($strTable)
	{
		$arrReturn = array();
		$arrFields = $this->execute(sprintf($this->strListFields, $strTable))->fetchAllAssoc();

		foreach ($arrFields as $k=>$v)
		{
			$arrReturn[$k]['name'] = $v['CNAME'];
			$arrReturn[$k]['type'] = $v['COLTYPE'];
			$arrReturn[$k]['length'] = $v['WIDTH'];
			$arrReturn[$k]['precision'] = $v['PRECISION'];
			$arrReturn[$k]['null'] = $v['NULLS'];
			$arrReturn[$k]['default'] = $v['DEFAULTVAL'];
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
		return false;
	}


	/**
	 * Begin a transaction
	 */
	protected function begin_transaction()
	{
		$this->blnDisableAutocommit = true;
	}


	/**
	 * Commit a transaction
	 */
	protected function commit_transaction()
	{
		@oci_commit($this->resConnection);
		$this->blnDisableAutocommit = false;
	}


	/**
	 * Rollback a transaction
	 */
	protected function rollback_transaction()
	{
		@oci_rollback($this->resConnection);
		$this->blnDisableAutocommit = false;
	}
}


/**
 * Class DB_Oracle_Statement
 *
 * Driver class for MySQL databases.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Driver
 */
class DB_Oracle_Statement extends Database_Statement
{

	/**
	 * Current statement resource
	 * @var resource
	 */
	protected $resStatement;


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
		$strType = strtoupper(preg_replace('/\s+.*$/is', '', trim($this->strQuery)));

		// Return if the current statement is not a SELECT statement
		if ($strType != 'SELECT')
		{
			return;
		}

		// Get field names of the current query
		$resStmt = @oci_parse($this->resConnection, sprintf('SELECT * FROM (%s) WHERE rownum < 2', $this->strQuery));
		@oci_execute($resStmt);

		$arrFields = array_keys(@oci_fetch_assoc($resStmt));
		$strFields = implode(', ', $arrFields);

		// Oracle starts row counting at 1
		++$intRows;

		$this->strQuery = sprintf('SELECT %s FROM (SELECT %s, ROWNUM AS ROWN FROM (%s) WHERE ROWNUM < %d) WHERE ROWN > %d',
								$strFields,
								$strFields,
								$this->strQuery,
								($intOffset + $intRows),
								$intOffset);
	}


	/**
	 * Execute the current query
	 * @return resource
	 */
	protected function execute_query()
	{
		if (($this->resStatement = @oci_parse($this->resConnection, $this->strQuery)) == false)
		{
			$this->resStatement = $this->resConnection;
			return false;
		}

		$strExecutionMode = $this->blnDisableAutocommit ? OCI_DEFAULT : OCI_COMMIT_ON_SUCCESS;

		if (!@oci_execute($this->resStatement, $strExecutionMode))
		{
			return false;
		}

		return $this->resStatement;
	}


	/**
	 * Return the last error message
	 * @return string
	 */
	protected function get_error()
	{
		$arrError = is_resource($this->resStatement) ? oci_error($this->resStatement) : oci_error();
		return $arrError['message'];
	}


	/**
	 * Return the number of affected rows
	 * @return int
	 */
	protected function affected_rows()
	{
		return @oci_num_rows($this->resResult);
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
 * Class DB_Oracle_Result
 *
 * Driver class for MySQL databases.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Driver
 */
class DB_Oracle_Result extends Database_Result
{

	/**
	 * Fetch the current row as enumerated array
	 * @return array
	 */
	protected function fetch_row()
	{
		return @oci_fetch_row($this->resResult);
	}


	/**
	 * Fetch the current row as associative array
	 * @return array
	 */
	protected function fetch_assoc()
	{
		return @oci_fetch_assoc($this->resResult);
	}


	/**
	 * Return the number of rows of the current result
	 * @return int
	 */
	protected function num_rows()
	{
		$this->fetchAllAssoc();
		$this->reset();

		return count($this->arrCache);
	}


	/**
	 * Return the number of fields of the current result
	 * @return int
	 */
	protected function num_fields()
	{
		return @oci_num_fields($this->resResult);
	}


	/**
	 * Get column information
	 * @param  int
	 * @return object
	 */
	protected function fetch_field($intOffset)
	{
		// Oracle starts row counting at 1
		++$intOffset;

		$arrData['name'] = @ocicolumnname($this->resResult, $intOffset);
		$arrData['max_length'] = @ocicolumnsize($this->resResult, $intOffset);
		$arrData['not_null'] = @ocicolumnisnull($this->resResult, $intOffset);
		$arrData['type'] = @ocicolumntype($this->resResult, $intOffset);

		return $arrData;
	}
}

?>