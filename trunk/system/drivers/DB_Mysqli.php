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
 * Class DB_Mysqlii
 *
 * Driver class for MySQLi databases.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Driver
 */
class DB_Mysqli extends Database
{

	/**
	 * List tables query
	 * @var string
	 */
	protected $strListTables = "SHOW TABLES FROM `%s`";

	/**
	 * List fields query
	 * @var string
	 */
	protected $strListFields = "SHOW COLUMNS FROM `%s`";


	/**
	 * Connect to database server and select database
	 */
	protected function connect()
	{
		$this->resConnection = new mysqli($GLOBALS['TL_CONFIG']['dbHost'], $GLOBALS['TL_CONFIG']['dbUser'], $GLOBALS['TL_CONFIG']['dbPass'], $GLOBALS['TL_CONFIG']['dbDatabase'], $GLOBALS['TL_CONFIG']['dbPort']);
		$this->resConnection->set_charset($GLOBALS['TL_CONFIG']['dbCharset']);
	}


	/**
	 * Disconnect from database
	 */
	protected function disconnect()
	{
		$this->resConnection->close();
	}


	/**
	 * Return the last error message
	 * @return string
	 */
	protected function get_error()
	{
		return $this->resConnection->error;
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
	 * - index:      PRIMARY, UNIQUE or INDEX
	 * - extra:      extra information (e.g. auto_increment)
	 * @param string
	 * @return string
	 * @todo Support all kind of keys (e.g. FULLTEXT or FOREIGN).
	 */
	protected function list_fields($strTable)
	{
		$arrReturn = array();
		$arrFields = $this->execute(sprintf($this->strListFields, $strTable))->fetchAllAssoc();

		foreach ($arrFields as $k=>$v)
		{
			$arrChunks = preg_split('/(\([^\)]+\))/', $v['Type'], -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);

			$arrReturn[$k]['name'] = $v['Field'];
			$arrReturn[$k]['type'] = $arrChunks[0];

			if (strlen($arrChunks[1]))
			{
				$arrChunks[1] = str_replace(array('(', ')'), array('', ''), $arrChunks[1]);
				$arrSubChunks = explode(',', $arrChunks[1]);

				$arrReturn[$k]['length'] = trim($arrSubChunks[0]);

				if (strlen($arrSubChunks[1]))
				{
					$arrReturn[$k]['precision'] = trim($arrSubChunks[1]);
				}
			}

			if (strlen($arrChunks[2]))
			{
				$arrReturn[$k]['attributes'] = trim($arrChunks[2]);
			}

			if (strlen($v['Key']))
			{
				switch ($v['Key'])
				{
					case 'PRI':
						$arrReturn[$k]['index'] = 'PRIMARY';
						break;

					case 'UNI':
						$arrReturn[$k]['index'] = 'UNIQUE';
						break;

					default:
						$arrReturn[$k]['index'] = 'KEY';
						break;
				}
			}

			$arrReturn[$k]['null'] = ($v['Null'] == 'YES') ? 'NULL' : 'NOT NULL';
			$arrReturn[$k]['default'] = $v['Default'];
			$arrReturn[$k]['extra'] = $v['Extra'];
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
		$this->resConnection = new mysqli($GLOBALS['TL_CONFIG']['dbHost'], $GLOBALS['TL_CONFIG']['dbUser'], $GLOBALS['TL_CONFIG']['dbPass'], $strDatabase, $GLOBALS['TL_CONFIG']['dbPort']);
	}


	/**
	 * Begin a transaction
	 */
	protected function begin_transaction()
	{
		$this->resConnection->query("SET AUTOCOMMIT=0");
		$this->resConnection->query("BEGIN");
	}


	/**
	 * Commit a transaction
	 */
	protected function commit_transaction()
	{
		$this->resConnection->query("COMMIT");
		$this->resConnection->query("SET AUTOCOMMIT=1");
	}


	/**
	 * Rollback a transaction
	 */
	protected function rollback_transaction()
	{
		$this->resConnection->query("ROLLBACK");
		$this->resConnection->query("SET AUTOCOMMIT=1");
	}
}


/**
 * Class DB_Mysqli_Statement
 *
 * Driver class for MySQLi databases.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Driver
 */
class DB_Mysqli_Statement extends Database_Statement
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
		return "'" . $this->resConnection->real_escape_string($strString) . "'";

		/**
		 * The TYPOlight framework automatically strips slashes so do not
		 * do it again here. Uncomment if used as stand-alone library.
		 * 
		if (!get_magic_quotes_gpc())
		{
			return "'" . $this->resConnection->real_escape_string($strString) . "'";
		}

		return "'" . $this->resConnection->real_escape_string(stripslashes($strString)) . "'";
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

		switch ($strType)
		{
			case 'SELECT':
				$this->strQuery .= sprintf(' LIMIT %s,%s', $intOffset, $intRows);
				break;

			default:
				$this->strQuery .= sprintf(' LIMIT %s', $intRows);
				break;
		}
	}


	/**
	 * Execute the current query
	 * @return resource
	 */
	protected function execute_query()
	{
		return $this->resConnection->query($this->strQuery);
	}


	/**
	 * Return the last error message
	 * @return string
	 */
	protected function get_error()
	{
		return $this->resConnection->error;
	}


	/**
	 * Return the number of affected rows
	 * @return int
	 */
	protected function affected_rows()
	{
		return $this->resConnection->affected_rows;
	}


	/**
	 * Return the last insert ID
	 * @return int
	 */
	protected function insert_id()
	{
		return $this->resConnection->insert_id;
	}


	/**
	 * Explain the current query
	 * @return array
	 */
	protected function explain_query()
	{
		return $this->resConnection->query('EXPLAIN ' . $this->strQuery)->fetch_assoc();
	}
}


/**
 * Class DB_Mysqli_Result
 *
 * Driver class for MySQLi databases.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Driver
 */
class DB_Mysqli_Result extends Database_Result
{

	/**
	 * Fetch the current row as enumerated array
	 * @return array
	 */
	protected function fetch_row()
	{
		return $this->resResult->fetch_row();
	}


	/**
	 * Fetch the current row as associative array
	 * @return array
	 */
	protected function fetch_assoc()
	{
		return $this->resResult->fetch_assoc();
	}


	/**
	 * Return the number of rows of the current result
	 * @return int
	 */
	protected function num_rows()
	{
		return $this->resResult->num_rows;
	}


	/**
	 * Return the number of fields of the current result
	 * @return int
	 */
	protected function num_fields()
	{
		return $this->resResult->field_countmysql;
	}


	/**
	 * Get column information
	 * @param  int
	 * @return object
	 */
	protected function fetch_field($intOffset)
	{
		return $this->resResult->fetch_field_direct($intOffset);
	}
}

?>