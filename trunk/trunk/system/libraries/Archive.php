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
 * Class Archive
 *
 * This class provides methods to handle ZIP files. It is based on the
 * phpMyAdmin library which is based on tutorials and code by Eric Mueller
 * (eric@themepark.com), Denis125 (webmaster@atlant.ru), Peter Listiak 
 * (mlady@users.sourceforge.net) and Holger Boskugel (vbwebprofi@gmx.de).
 * @link	   http://www.zend.com/codex.php?id=535&single=1
 * @link	   http://www.zend.com/codex.php?id=470&single=1
 * @link	   http://www.phpmyadmin.net
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Library
 */
class Archive
{

	/**
	 * Archive name
	 * @var string
	 */
	protected $strName;

	/**
	 * Array to store compressed data
	 * @var array
	 */
	protected $arrData = array();

	/**
	 * Central directory
	 * @var array
	 */
	protected $arrCtrlDir = array();

	/**
	 * Last offset position
	 * @var integer
	 */
	protected $intLastOffset = 0;

	/**
	 * Comments
	 * @var	string
	 */
	protected $strComment = '';


	/**
	 * Set the archive name
	 * @param string
	 */
	public function __construct($strName='')
	{
		$this->strName = $strName;
	}


	/**
	 * Add a file to the archive
	 * @param string
	 * @param string
	 */
	public function addFile($strFile, $strName=false)
	{
		$this->addString(file_get_contents($strFile), ($strName ? $strName : $strFile));
	}


	/**
	 * Add a file from a string to the archive
	 * @param string
	 * @param string
	 */
	public function addFromString($strContent, $strName)
	{
		$this->addString($strContent, $strName);
	}


	/**
	 * Generate the archive and return it as string
	 * @return string
	 */
	public function generate()
	{
		$data = implode('', $this->arrData);
		$ctrldir = implode('', $this->arrCtrlDir);

		return
			$data .
			$ctrldir .
			"\x50\x4b\x05\x06\x00\x00\x00\x00" .   // end of central dir
			pack('v', sizeof($this->arrCtrlDir)) . // total # of entries "on this disk"
			pack('v', sizeof($this->arrCtrlDir)) . // total # of entries overall
			pack('V', strlen($ctrldir)) .          // size of central dir
			pack('V', strlen($data)) .             // offset to start of central dir
			"\x00\x00";                            // .zip file comment length
	}


	/**
	 * Return the content of the archive as array
	 * @return array
	 * @throws Exception
	 */
	public function extract()
	{
		if (!strlen($this->strName))
		{
			throw new Exception('Invalid archive name');
		}

		return $this->ReadFile($this->strName);
	}


	/**
	 * Converts an Unix timestamp to a four byte DOS date and time format (date
	 * in high two bytes, time in low two bytes allowing magnitude comparison).
	 * @param integer
	 * @return integer
	 */
	private function unix2DosTime($unixtime = 0)
	{
		$timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);

		if ($timearray['year'] < 1980)
		{
			$timearray['year']    = 1980;
			$timearray['mon']     = 1;
			$timearray['mday']    = 1;
			$timearray['hours']   = 0;
			$timearray['minutes'] = 0;
			$timearray['seconds'] = 0;
		}

		return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) | ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
	}


	/**
	 * Add a string as file to the archive
	 * @param string
	 * @param string
	 * @param integer
	 */
	private function addString($data, $name, $time=0)
	{
		$name = str_replace('\\', '/', $name);
		$dtime = dechex($this->unix2DosTime($time));
		$hexdtime = '\x' . $dtime[6] . $dtime[7] . '\x' . $dtime[4] . $dtime[5] . '\x' . $dtime[2] . $dtime[3] . '\x' . $dtime[0] . $dtime[1];

		eval('$hexdtime = "' . $hexdtime . '";');
		$fr = "\x50\x4b\x03\x04\x14\x00\x00\x00\x08\x00" . $hexdtime;

		// "Local file header" segment
		$unc_len = strlen($data);
		$crc	 = crc32($data);
		$zdata   = gzcompress($data);
		$zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2); // fix crc bug
		$c_len   = strlen($zdata);

		$fr .= pack('V', $crc);          // crc32
		$fr	.= pack('V', $c_len);        // compressed filesize
		$fr	.= pack('V', $unc_len);      // uncompressed filesize
		$fr	.= pack('v', strlen($name)); // length of filename
		$fr	.= pack('v', 0);             // extra field length
		$fr	.= $name;

		// "File data" segment
		$fr .= $zdata;
		$this->arrData[] = $fr;

		// Add to central directory record
		$cdrec = "\x50\x4b\x01\x02";
		$cdrec .= "\x00\x00";               // version made by
		$cdrec .= "\x14\x00";               // version needed to extract
		$cdrec .= "\x00\x00";               // gen purpose bit flag
		$cdrec .= "\x08\x00";               // compression method
		$cdrec .= $hexdtime;                // last mod time & date
		$cdrec .= pack('V', $crc);          // crc32
		$cdrec .= pack('V', $c_len);        // compressed filesize
		$cdrec .= pack('V', $unc_len);      // uncompressed filesize
		$cdrec .= pack('v', strlen($name)); // length of filename
		$cdrec .= pack('v', 0);             // extra field length
		$cdrec .= pack('v', 0);             // file comment length
		$cdrec .= pack('v', 0);             // disk number start
		$cdrec .= pack('v', 0);             // internal file attributes
		$cdrec .= pack('V', 32);            // external file attributes - 'archive' bit set

		$cdrec .= pack('V', $this->intLastOffset);
		$this->intLastOffset += strlen($fr);

		$cdrec .= $name;
		$this->arrCtrlDir[] = $cdrec;
	}


	/**
	 * Read ZIP file and extracts the entries
	 * @param string
	 * @return array
	 */
	private function ReadFile($in_FileName)
	{
		$arrEntries = array();

		// Read file
		$vZ = file_get_contents($in_FileName);

		// Cut end of central directory
		$aE = explode("\x50\x4b\x05\x06", $vZ);

		// Comments
		$aP = unpack('x16/v1CL', $aE[1]);
		$this->strComment = substr($aE[1], 18, $aP['CL']);

		// Translates end of line from other operating systems
		$this->strComment = strtr($this->strComment, array("\r\n"=>"\n", "\r"=>"\n"));

		// Cut the entries from the central directory
		$aE = explode("\x50\x4b\x01\x02", $vZ);
		// Explode to each part
		$aE = explode("\x50\x4b\x03\x04", $aE[0]);
		// Shift out spanning signature or empty entry
		array_shift($aE);

		// Loop through the entries
		foreach ($aE as $vZ)
		{
			$aI = array();
			$aI['Error'] = 0;
			$aI['ErrorMsg'] = '';

			// Retrieving local file header information
			$aP = unpack('v1VN/v1GPF/v1CM/v1FT/v1FD/V1CRC/V1CS/V1UCS/v1FNL', $vZ);

			// Check if data is encrypted
			$bE = ($aP['GPF'] & 0x0001) ? TRUE : FALSE;
			$nF = $aP['FNL'];

			// Special case : value block after the compressed data
			if ($aP['GPF'] & 0x0008)
			{
				$aP1 = unpack('V1CRC/V1CS/V1UCS', substr($vZ, -12));

				$aP['CRC'] = $aP1['CRC'];
				$aP['CS']  = $aP1['CS'];
				$aP['UCS'] = $aP1['UCS'];

				$vZ = substr($vZ, 0, -12);
			}

			// Getting stored filename
			$aI['Name'] = substr($vZ, 26, $nF);

			if (substr($aI['Name'], -1) == '/')
			{
				// is a directory entry - will be skipped
				continue;
			}

			// Truncate full filename in path and filename
			$aI['Path'] = dirname($aI['Name']);
			$aI['Path'] = $aI['Path'] == '.' ? '' : $aI['Path'];
			$aI['Name'] = basename($aI['Name']);

			$vZ = substr($vZ, 26 + $nF);

			if (strlen($vZ) != $aP['CS'])
			{
				$aI['Error'] = 1;
				$aI['ErrorMsg'] = 'Compressed size is not equal with the value in header information.';
			}
			else
			{
				if ($bE)
				{
					$aI['Error'] = 5;
					$aI['ErrorMsg'] = 'File is encrypted, which is not supported from this class.';
				}
				else
				{
					switch($aP['CM'])
					{
						case 0: // Stored
							// Here is nothing to do, the file ist flat.
							break;

						case 8: // Deflated
							$vZ = gzinflate($vZ);
							break;

						case 12: // BZIP2
							if (! extension_loaded('bz2'))
							{
								if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
									@dl('php_bz2.dll');
								else
									@dl('bz2.so');
							}

							if (extension_loaded('bz2'))
							{
								$vZ = bzdecompress($vZ);
							}
							else
							{
								$aI['Error'] = 7;
								$aI['ErrorMsg'] = "PHP BZIP2 extension not available.";
							}
							break;

						default:
							$aI['Error'] = 6;
							$aI['ErrorMsg'] = "De-/Compression method {$aP['CM']} is not supported.";
					}

					if (! $aI['Error'])
					{
						if ($vZ === FALSE)
						{
							$aI['Error'] = 2;
							$aI['ErrorMsg'] = 'Decompression of data failed.';
						}
						else
						{
							if (strlen($vZ) != $aP['UCS'])
							{
								$aI['Error'] = 3;
								$aI['ErrorMsg'] = 'Uncompressed size is not equal with the value in header information.';
							}
							else
							{
								if ($this->equalizeCrc32(crc32($vZ)) != $this->equalizeCrc32($aP['CRC']))
								{
									$aI['Error'] = 4;
									$aI['ErrorMsg'] = 'CRC32 checksum is not equal with the value in header information.';
								}
							}
						}
					}
				}
			}

			$aI['Data'] = $vZ;

			// DOS to UNIX timestamp
			$aI['Time'] = mktime(($aP['FT'] & 0xf800) >> 11,
								 ($aP['FT'] & 0x07e0) >>  5,
								 ($aP['FT'] & 0x001f) <<  1,
								 ($aP['FD'] & 0x01e0) >>  5,
								 ($aP['FD'] & 0x001f),
								(($aP['FD'] & 0xfe00) >>  9) + 1980);

			$arrEntries[] = $aI;
		}

		return $arrEntries;
	}


	/**
	 * Equalize 64bit and 32bit CRC32 values
	 * @param integer
	 * @return integer
	 */
	private function equalizeCrc32($crc)
	{
		$crc = abs($crc);

		if($crc & 0x80000000)
		{
			$crc ^= 0xffffffff;
			$crc += 1;
		}

		return $crc;
	}
}

?>