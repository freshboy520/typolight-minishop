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
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleMaintenance
 *
 * Back end module "maintenance".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleMaintenance extends BackendModule
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_maintenance';


	/**
	 * Generate module
	 */
	protected function compile()
	{
		$this->loadLanguageFile('tl_maintenance');

		$this->Template->cacheMessage = '';
		$this->Template->updateMessage = '';

		$this->cacheTables();
		$this->liveUpdate();
		$this->searchIndex();

		$this->Template->href = $this->getReferer(ENCODE_AMPERSANDS);
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['backBT']);
		$this->Template->action = ampersand($this->Environment->request, true);
		$this->Template->selectAll = $GLOBALS['TL_LANG']['MSC']['selectAll'];
		$this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];
	}


	/**
	 * Handle the "clear cache" module
	 */
	private function cacheTables()
	{
		$arrCacheTables = array();

		$arrTmp = scan(TL_ROOT . '/system/tmp');
		$arrHtml = scan(TL_ROOT . '/system/html');

		// Confirmation message
		if (strlen($_SESSION['CLEAR_CACHE']['confirm']))
		{
			$this->Template->cacheMessage = sprintf('<p class="tl_confirm">%s</p>', $_SESSION['CLEAR_CACHE']['confirm']);
			$_SESSION['CLEAR_CACHE']['confirm'] = '';
		}

		// Truncate cache tables
		if ($this->Input->post('FORM_SUBMIT') == 'tl_cache')
		{
			$tables = deserialize($this->Input->post('tables'));

			if (!is_array($tables))
			{
				$this->reload();
			}

			foreach ($tables as $table)
			{
				// Temporary folder
				if ($table == 'temp_folder')
				{
					foreach ($arrTmp as $strFile)
					{
						if ($strFile != '.htaccess')
						{
							@unlink(TL_ROOT . '/system/tmp/' . $strFile);
						}
					}
				}

				// Html folder
				elseif ($table == 'html_folder')
				{
					foreach ($arrHtml as $strFile)
					{
						if ($strFile != 'index.html')
						{
							@unlink(TL_ROOT . '/system/html/' . $strFile);
						}
					}
				}

				// XML sitemaps
				elseif ($table == 'xml_sitemap')
				{
					include(TL_ROOT . '/system/config/dcaconfig.php');
					$this->generateXmlSitemamps();
				}

				// Database table
				else
				{
					$this->Database->execute("TRUNCATE TABLE " . $table);
				}
			}

			$_SESSION['CLEAR_CACHE']['confirm'] = $GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'];
			$this->reload();
		}

		// Get all cachable tables from TL_API
		foreach ($GLOBALS['TL_CACHE'] as $k=>$v)
		{
			$objCount = $this->Database->execute("SELECT COUNT(*) AS count FROM " . $v);

			$arrCacheTables[] = array
			(
				'id' => 'cache_' . $k,
				'value' => specialchars($v),
				'name' => $v,
				'entries' => sprintf($GLOBALS['TL_LANG']['MSC']['entries'], $objCount->count)
			);
		}

		$this->Template->cacheTmp = $GLOBALS['TL_LANG']['tl_maintenance']['clearTemp'];
		$this->Template->cacheHtml = $GLOBALS['TL_LANG']['tl_maintenance']['clearHtml'];
		$this->Template->cacheXml = $GLOBALS['TL_LANG']['tl_maintenance']['clearXml'];
		$this->Template->cacheHeadline = $GLOBALS['TL_LANG']['tl_maintenance']['clearCache'];
		$this->Template->cacheLabel = $GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0];
		$this->Template->cacheEntries = sprintf($GLOBALS['TL_LANG']['MSC']['entries'], (count($arrTmp) - 1));
		$this->Template->htmlEntries = sprintf($GLOBALS['TL_LANG']['MSC']['entries'], (count($arrHtml) - 1));
		$this->Template->cacheHelp = ($GLOBALS['TL_CONFIG']['showHelp'] && strlen($GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1])) ? $GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] : '';
		$this->Template->cacheSubmit = specialchars($GLOBALS['TL_LANG']['tl_maintenance']['clearCache']);
		$this->Template->cacheTables = $arrCacheTables;
	}


	/**
	 * Handle the "live update" module
	 */
	private function liveUpdate()
	{
		$this->Template->updateClass = 'tl_confirm';
		$this->Template->updateHeadline = $GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'];

		// Current version up to date
		$this->Template->updateMessage = sprintf('%s <a href="%sCHANGELOG.txt" title="%s" onclick="this.blur(); window.open(this.href); return false;"><img src="%s" alt="%s" style="vertical-align:text-bottom; padding-left:3px;" /></a>',
												 sprintf($GLOBALS['TL_LANG']['tl_maintenance']['upToDate'], VERSION . '.' . BUILD),
												 $this->Environment->base,
												 specialchars($GLOBALS['TL_LANG']['tl_maintenance']['changelog']),
												 'system/themes/'.$this->getTheme().'/images/changelog.gif',
												 specialchars($GLOBALS['TL_LANG']['tl_maintenance']['changelog']));

		// Newer version available
		if (strlen($GLOBALS['TL_CONFIG']['latestVersion']) && version_compare(VERSION . '.' . BUILD, $GLOBALS['TL_CONFIG']['latestVersion'], '<'))
		{
			$this->Template->updateClass = 'tl_info';
			$this->Template->updateMessage = sprintf($GLOBALS['TL_LANG']['tl_maintenance']['newVersion'], $GLOBALS['TL_CONFIG']['latestVersion']);
		}

		// Live update error
		if (strlen($_SESSION['LIVE_UPDATE']['error']))
		{
			$this->Template->updateClass = 'tl_error';
			$this->Template->updateMessage = $_SESSION['LIVE_UPDATE']['error'];

			$_SESSION['LIVE_UPDATE']['error'] = '';
		}

		// Live update successful
		if (strlen($_SESSION['LIVE_UPDATE']['confirm']))
		{
			$this->Template->updateClass = 'tl_confirm';
			$this->Template->updateMessage = $_SESSION['LIVE_UPDATE']['confirm'];

			$_SESSION['LIVE_UPDATE']['confirm'] = '';
		}

		$this->Template->uid = $GLOBALS['TL_CONFIG']['liveUpdateId'];
		$this->Template->updateServer = 'http://www.inetrobots.com/liveupdate/index.php';

		if ($this->Environment->ssl)
		{
			$this->Template->updateServer = 'https://sslwebsites.net/inetrobots.com/liveupdate/index.php';
		}

		// Run update
		if (strlen($this->Input->get('token')))
		{
			$this->runLiveUpdate();
		}

		$this->Template->version = VERSION . '.' .  BUILD;
		$this->Template->liveUpdateId = $GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'];
		$this->Template->runLiveUpdate = specialchars($GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate']);
		$this->Template->referer = base64_encode($this->Environment->base . $this->Environment->request . '|' . $this->Environment->server);
		$this->Template->backupFiles = $GLOBALS['TL_LANG']['tl_maintenance']['backupFiles'];
		$this->Template->showToc = $GLOBALS['TL_LANG']['tl_maintenance']['showToc'];
	}


	/**
	 * Run the live update
	 */
	private function runLiveUpdate()
	{
		$archive = 'system/tmp/' . $this->Input->get('token');

		// Download the archive
		if (!file_exists(TL_ROOT . '/' . $archive))
		{
			$objRequest = new Request();
			$objRequest->send('http://www.inetrobots.com/liveupdate/request.php?token=' . $this->Input->get('token'));

			if ($objRequest->hasError())
			{
				$this->Template->updateClass = 'tl_error';
				$this->Template->updateMessage = $objRequest->response;

				return;
			}

			$objFile = new File($archive);
			$objFile->write($objRequest->response);
			$objFile->close();
		}

		// Extract the archive
		$objArchive = new Archive(TL_ROOT . '/' . $archive);
		$arrFiles = $objArchive->extract();

		// Show files
		if ($this->Input->get('toc'))
		{
			echo '<div style="font-family:Verdana, sans-serif; font-size:11px; line-height:16px; margin-bottom:12px;">';
			echo '<strong>Table of contents</strong><br /><br />';
			echo nl2br($arrFiles[0]['Data']);
			echo '<br /><br />';
			echo '<a href="' . str_replace('toc=1', 'toc=', $this->Environment->base . $this->Environment->request) . '">Click here to run the update</a><br />';
			echo '<a href="' . $this->Environment->base . 'typolight/main.php?do=maintenance">Click here to go back</a>';
			echo '</div>';

			exit;
		}

		// Create backup
		if ($this->Input->get('bup'))
		{
			$objBackup = new Archive();

			foreach ($arrFiles as $arrFile)
			{
				if ($arrFile['Error'])
				{
					continue;
				}

				$path = ($arrFile['Path'] ? $arrFile['Path'] . '/' : '') . $arrFile['Name'];

				if (!file_exists(TL_ROOT . '/' . $path))
				{
					continue;
				}

				$objBackup->addFile(TL_ROOT . '/' . $path, $path);
			}

			$resBackup = new File('LU' . date('YmdHi') . '.zip');
			$resBackup->write($objBackup->generate());
			$resBackup->close();

			$url = str_replace('bup=1', 'bup=', $this->Environment->base . $this->Environment->request);

			echo '<script type="text/javascript">setTimeout(\'window.location="' . $url . '"\', 1000);</script>';
			echo '<div style="font-family:Verdana, sans-serif; font-size:11px; line-height:16px; margin-bottom:12px;">';
			echo '<a href="' . $url . '">Click here to proceed if you are not using JavaScript</a>';
			echo '</div>';

			exit;
 		}
 
 		$error = false;

		// Replace files
		foreach ($arrFiles as $arrFile)
		{
			if ($arrFile['Name'] == 'TOC.txt')
			{
				continue;
			}

			$path = ($arrFile['Path'] ? $arrFile['Path'] . '/' : '') . $arrFile['Name'];

			// Show errors
			if ($arrFile['Error'])
			{
				echo '<div style="font-family:Verdana, sans-serif; font-size:11px; line-height:16px;">Error updating file <strong>' . $path . '</strong>: ' . $arrFile['ErrorMsg'] . '</div>' . "\n";
				$error = true;

				continue;
			}

			$objFile = new File($path);
			$objFile->write($arrFile['Data']);
			$objFile->close();
		}

		// Exit if there are errors
		if ($error)
		{
			exit;
		}

		// Delete archive
		$this->import('Files');
		$this->Files->delete($archive);

		// Add log entry
		$this->log('Live update from version ' . VERSION . '.' . BUILD . ' to version ' . $GLOBALS['TL_CONFIG']['latestVersion'] . ' completed', 'ModuleMaintenance runLiveUpdate()', TL_GENERAL);

		// Reset latest version
		$GLOBALS['TL_CONFIG']['latestVersion'] = '';
		$this->Config->update("\$GLOBALS['TL_CONFIG']['latestVersion']", '');

		// Redirect
		$this->redirect('typolight/main.php?do=maintenance');
	}


	/**
	 * Handle the "rebuild search index" module
	 */
	private function searchIndex()
	{
		$this->Template->indexer = $this->Environment->base . 'typolight/indexer.php';
		$this->Template->indexHeadline = $GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'];
		$this->Template->indexLabel = $GLOBALS['TL_LANG']['tl_maintenance']['pagesPerCycle'][0];
		$this->Template->indexHelp = ($GLOBALS['TL_CONFIG']['showHelp'] && strlen($GLOBALS['TL_LANG']['tl_maintenance']['pagesPerCycle'][1])) ? $GLOBALS['TL_LANG']['tl_maintenance']['pagesPerCycle'][1] : '';
		$this->Template->indexSubmit = $GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'];
	}
}

?>