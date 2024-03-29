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
 * Class autoloader
 *
 * Include classes automatically when they are instantiated.
 * @param string
 */
function __autoload($strClassName)
{
	// Library
	if (file_exists(TL_ROOT . '/system/libraries/' . $strClassName . '.php'))
	{
		include_once(TL_ROOT . '/system/libraries/' . $strClassName . '.php');
		return;
	}

	// Modules
	foreach (scan(TL_ROOT . '/system/modules/') as $strFolder)
	{
		if (substr($strFolder, 0, 1) == '.')
		{
			continue;
		}

		if (file_exists(TL_ROOT . '/system/modules/' . $strFolder . '/' . $strClassName . '.php'))
		{
			include_once(TL_ROOT . '/system/modules/' . $strFolder . '/' . $strClassName . '.php');
			return;
		}
	}

	// HOOK: include DOMPDF classes
	if (function_exists('DOMPDF_autoload'))
	{
		DOMPDF_autoload($strClassName);
		return;
	}

	trigger_error(sprintf('Could not load class %s', $strClassName), E_USER_ERROR);
}


/**
 * Error handler
 *
 * Handle errors like PHP does it natively but additionaly log them to the
 * application error log file.
 * @param int
 * @param string
 * @param string
 * @param int
 */
function __error($intType, $strMessage, $strFile, $intLine)
{
	$arrErrors = array (
		E_ERROR           => 'Fatal error',
		E_WARNING         => 'Warning',
		E_PARSE           => 'Parsing error',
		E_NOTICE          => 'Notice',
		E_CORE_ERROR      => 'Core error',
		E_CORE_WARNING    => 'Core warning',
		E_COMPILE_ERROR   => 'Compile error',
		E_COMPILE_WARNING => 'Compile warning',
		E_USER_ERROR      => 'Fatal error',
		E_USER_WARNING    => 'Warning',
		E_USER_NOTICE     => 'Notice',
		E_STRICT          => 'Runtime notice'
	);

	// Ignore functions with an error control operator (@function_name)
	if (ini_get('error_reporting') > 0)
	{
		if ($intType != E_NOTICE)
		{
			// Log error
			error_log(sprintf('PHP %s: %s in %s on line %s',
							$arrErrors[$intType],
							$strMessage,
							$strFile,
							$intLine));

			// Display error
			if (ini_get('display_errors'))
			{
				$strMessage = sprintf('<strong>%s</strong>: %s in <strong>%s</strong> on line <strong>%s</strong>',
									$arrErrors[$intType],
									$strMessage,
									$strFile,
									$intLine);

				echo '<br />' . $strMessage;
			}
		}

		// Exit on severe errors
		if (in_array($intType, array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR)))
		{
			show_help_message();
			exit;
		}
	}
}


/**
 * Exception handler
 *
 * Log exceptions in the application log file and print them to the screen
 * if "display_errors" is set. Callback to a custom exception handler defined
 * in the application file "config/error.php".
 * @param object
 */
function __exception($e)
{
	error_log(sprintf("PHP Fatal error: Uncaught exception '%s' with message '%s' thrown in %s on line %s",
					get_class($e),
					$e->getMessage(),
					$e->getFile(),
					$e->getLine()));

	// Display exception
	if (ini_get('display_errors'))
	{
		$strMessage = sprintf('<strong>Fatal error</strong>: Uncaught exception <strong>%s</strong> with message <strong>%s</strong> thrown in <strong>%s</strong> on line <strong>%s</strong>',
							get_class($e),
							$e->getMessage(),
							$e->getFile(),
							$e->getLine());

		$strMessage .= "\n" . '<pre style="margin: 11px 0px 0px 0px;">' . "\n" . $e->getTraceAsString() . "\n" . '</pre>';

		echo '<br />' . $strMessage;
	}

	show_help_message();
	exit;
}


/**
 * Show a special TYPOlight "what to do in case of an error" message
 */
function show_help_message()
{
	if (!ini_get('display_errors'))
	{
		if (file_exists(TL_ROOT . '/system/modules/backend/templates/be_error.tpl'))
		{
			include(TL_ROOT . '/system/modules/backend/templates/be_error.tpl');
			exit;
		}

		echo 'An error occurred while executing this script!';
	}
}


/**
 * Add a log entry
 * @param string
 * @param string
 */
function log_message($strMessage, $strLog='error.log')
{
	@error_log(sprintf("[%s] %s\n", date('Y-m-d H:i:s'), $strMessage), 3, TL_ROOT . '/system/logs/' . $strLog);
}


/**
 * Scan a directory and return its files and folders as array
 * @param string
 * @return array
 */
function scan($strFolder)
{
	$arrReturn = array();

	foreach (scandir($strFolder) as $strFile)
	{
		if ($strFile == '.' || $strFile == '..')
		{
			continue;
		}

		$arrReturn[] = $strFile;
	}

	return $arrReturn;
}


/**
 * Convert special characters except ampersands to HTML entities
 * @param string
 * @return string
 */
function specialchars($strString)
{
	$arrFind = array('"', "'", '<', '>');
	$arrReplace = array('&#34;', '&#39;', '&lt;', '&gt;');

	return str_replace($arrFind, $arrReplace, $strString);
}


/**
 * Standardize a parameter (strip special characters and convert spaces to underscores)
 * @param mixed
 * @return mixed
 */
function standardize($varValue)
{
	$varValue = utf8_romanize($varValue);

	$varValue = preg_replace('/[^a-zA-Z0-9 _-]+/i', '', $varValue);
	$varValue = preg_replace('/ +/i', '-', $varValue);

	if (preg_match('/^[^a-zA-Z]/i', $varValue))
	{
		$varValue = 'id-' . $varValue;
	}

	return strtolower($varValue);
}


/**
 * Return an unserialized array or the argument
 * @param mixed
 * @param boolean
 * @return mixed
 */
function deserialize($varValue, $blnForceArray=false)
{
	if (!is_string($varValue) || !strlen(trim($varValue)))
	{
		return ($blnForceArray && !is_array($varValue)) ? array($varValue) : $varValue;
	}

	$varUnserialized = unserialize($varValue);

	if (is_array($varUnserialized))
	{
		$varValue = $varUnserialized;
	}

	elseif ($blnForceArray)
	{
		$varValue = array($varValue);
	}

	return $varValue;
}


/**
 * Split a string into fragments, remove whitespace and return fragments as array
 * @param string
 * @param string
 * @return string
 */
function trimsplit($strPattern, $strString)
{
	$arrFragments = array_map('trim', preg_split('/'.$strPattern.'/ui', $strString));

	if (count($arrFragments) < 2 && !strlen($arrFragments[0]))
	{
		return array();
	}

	return $arrFragments;
}


/**
 * Convert all ampersands into their HTML entity (default) or unencoded value
 * @param string
 * @return string
 */
function ampersand($strString, $blnEncode=true)
{
	return preg_replace('/&(amp;)?/i', ($blnEncode ? '&amp;' : '&'), $strString);
}


/**
 * Insert HTML line breaks before all newlines preserving preformatted text
 * @param string
 * @return string
 */
function nl2br_pre($str)
{
	$str = nl2br($str);

	if (stripos($str, '<pre') === false)
		return $str;

	$chunks = array();
	preg_match_all('/<pre[^>]*>.*<\/pre>/is', $str, $chunks);

	foreach ($chunks as $chunk)
	{
		$str = str_replace($chunk, str_ireplace(array('<br>', '<br />'), '', $chunk), $str);
	}

	return $str;
}


/**
 * Sort an array by keys using a case insensitive "natural order" algorithm
 * @param array
 * @return array
 */
function natcaseksort($arrArray)
{
	$arrBuffer = array_flip($arrArray);
	natcasesort($arrBuffer);

	return array_flip($arrBuffer);
}


/**
 * Insert a parameter or array into an existing array at a particular index
 * @param array
 * @param int
 * @param mixed
 */
function array_insert(&$arrCurrent, $intIndex, $arrNew)
{
	if (!is_array($arrCurrent))
	{
		$arrCurrent = $arrNew;
		return;
	}

	if (is_array($arrNew))
	{
		$arrBuffer = array_splice($arrCurrent, 0, $intIndex);
		$arrCurrent = array_merge_recursive($arrBuffer, $arrNew, $arrCurrent);

		return;
	}

	array_splice($arrCurrent, $intIndex, 0, $arrNew);
}


/**
 * Duplicate a particular element of an array
 * @param array
 * @param integer
 * @return array
 */
function array_duplicate($arrStack, $intIndex)
{
	$arrBuffer = $arrStack;
	$arrStack = array();

	for ($i=0; $i<=$intIndex; $i++)
	{
		$arrStack[] = $arrBuffer[$i];
	}

	for ($i=$intIndex; $i<count($arrBuffer); $i++)
	{
		$arrStack[] = $arrBuffer[$i];
	}

	return $arrStack;
}


/**
 * Move an array element one position up
 * @param array
 * @param integer
 * @return array
 */
function array_move_up($arrStack, $intIndex)
{
	if ($intIndex > 0)
	{
		$arrBuffer = $arrStack[$intIndex];
		$arrStack[$intIndex] = $arrStack[($intIndex-1)];
		$arrStack[($intIndex-1)] = $arrBuffer;
	}

	else
	{
		array_push($arrStack, $arrStack[$intIndex]);
		array_shift($arrStack);
	}

	return $arrStack;
}


/**
 * Move an array element one position down
 * @param array
 * @param int
 * @return array
 */
function array_move_down($arrStack, $intIndex)
{
	if (($intIndex+1) < count($arrStack))
	{
		$arrBuffer = $arrStack[$intIndex];
		$arrStack[$intIndex] = $arrStack[($intIndex+1)];
		$arrStack[($intIndex+1)] = $arrBuffer;
	}

	else
	{
		array_unshift($arrStack, $arrStack[$intIndex]);
		array_pop($arrStack);
	}

	return $arrStack;
}


/**
 * Delete a particular element of an array
 * @param array
 * @param int
 * @return array
 */
function array_delete($arrStack, $intIndex)
{
	unset($arrStack[$intIndex]);
	return array_values($arrStack);
}


/**
 * Return true if an array is associative
 * @param  array
 * @return boolean
 */
function array_is_assoc($arrArray)
{
	return (is_array($arrArray) && array_keys($arrArray) !== range(0, (sizeof($arrArray) - 1)));
}

?>