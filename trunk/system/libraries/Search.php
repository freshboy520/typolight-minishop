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
 * Class Search
 *
 * Provide methods to handle indexing and searching.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Library
 */
class Search extends System
{

	/**
	 * Current object instance (Singleton)
	 * @var object
	 */
	protected static $objInstance;


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}


	/**
	 * Return the current object instance (Singleton)
	 * @return object
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new Search();
		}

		return self::$objInstance;
	}


	/**
	 * Index a single file
	 * @param array
	 * @return boolean
	 */
	public function indexPage($arrData)
	{
		$this->import('String');
		$this->import('Database');

		$arrSet['url'] = $arrData['url'];
		$arrSet['title'] = $arrData['title'];
		$arrSet['checksum'] = md5($arrData['content']);
		$arrSet['pid'] = $arrData['pid'];

		// Return if the page is indexed and up to date
		$objIndex = $this->Database->prepare("SELECT id, checksum FROM tl_search WHERE url=?")
								   ->limit(1)
								   ->execute($arrSet['url']);

		if ($objIndex->numRows && $objIndex->checksum == $arrSet['checksum'])
		{
			return false;
		}

		$arrSet['filesize'] = number_format((strlen($arrData['content']) / 1024 ), 2, '.', '');
		$arrData['content'] = str_replace(array("\n", "\r", "\t", '&#160;', '&nbsp;'), ' ', $arrData['content']);

		$strHead = preg_replace('/^.*(<head[^>]*>.*<\/head>).*$/is', '$1', $arrData['content']);
		$strBody = preg_replace('/^.*(<body[^>]*>.*<\/body>).*$/is', '$1', $arrData['content']);

		unset($arrData['content']);
		$tags = array();

		// Get description
		if (preg_match('/<meta[^>]+name="description"[^>]+content="([^"]*)"[^>]*>/i', $strHead, $tags))
		{
			$arrData['description'] = trim(preg_replace('/ +/', ' ', $this->String->decodeEntities($tags[1])));
		}

		// Get keywords
		if (preg_match('/<meta[^>]+name="keywords"[^>]+content="([^"]*)"[^>]*>/i', $strHead, $tags))
		{
			$arrData['keywords'] = trim(preg_replace('/ +/', ' ', $this->String->decodeEntities($tags[1])));
		}

		// Strip non-indexable areas
		$strBody = preg_replace('/<style[^>]*>(?>.*<\/style>)/is', '', $strBody);
		$strBody = preg_replace('/<!-- indexer::stop -->(?>.*?<!-- indexer::continue -->)/is', '', $strBody);
		$strBody = preg_replace('/<script[^>]*>(?>.*?<\/script>)/is', '', $strBody);

		// Read title and alt attributes
		if (preg_match_all('/<* (title|alt)="([^"]*)"[^>]*>/i', $strBody, $tags))
		{
			$arrData['keywords'] .= ' ' . implode(', ', array_unique($tags[2]));
		}

		$arrSet['text'] = $arrData['title'] . ' ' . $arrData['description'] . ' ' . strip_tags($strBody) . ' ' . $arrData['keywords'];
		$arrSet['text'] = trim(preg_replace('/ +/', ' ', $this->String->decodeEntities($arrSet['text'])));

		$arrSet['tstamp'] = time();

		// Save the page
		if ($objIndex->numRows)
		{
			$this->Database->prepare("UPDATE tl_search %s WHERE id=?")
						   ->set($arrSet)
						   ->execute($objIndex->id);

			$intInsertId = $objIndex->id;
		}

		else
		{
			$objInsertStmt = $this->Database->prepare("INSERT INTO tl_search %s")
											->set($arrSet)
											->execute();

			$intInsertId = $objInsertStmt->insertId;
		}

		$strText = str_replace(array('´', '`'), "'", $arrSet['text']);
		unset($arrSet);

		// Remove special characters
		if (function_exists('mb_eregi_replace'))
		{
			$strText = mb_eregi_replace("[^[:alnum:]'-]|- | -|' | '", ' ', $strText);
		}
		else
		{
			$strText = preg_replace(array('/- /', '/ -/', "/' /", "/ '/", '/[^\pN\pL\'-]/u'), ' ', $strText);
		}

		// Split words
		$arrWords = preg_split('/ +/', utf8_strtolower($strText));
		$strStopwords = sprintf('%s/system/modules/frontend/languages/%s/stopwords.txt', TL_ROOT, $GLOBALS['TL_LANGUAGE']);

		// Remove stopwords
		if (file_exists($strStopwords))
		{
			$arrWords = array_diff($arrWords, array_map('trim', file($strStopwords)));
		}

		$arrIndex = array();

		// Index words
		foreach ($arrWords as $strWord)
		{
			if (utf8_strlen($strWord) < $GLOBALS['TL_CONFIG']['minWordLength'])
			{
				continue;
			}

			if (array_key_exists($strWord, $arrIndex))
			{
				$arrIndex[$strWord]++;
				continue;
			}

			$arrIndex[$strWord] = 1;
		}

		// Remove existing index
		$this->Database->prepare("DELETE FROM tl_search_index WHERE pid=?")
					   ->execute($intInsertId);

		// Create new index
		foreach ($arrIndex as $k=>$v)
		{
			$this->Database->prepare("INSERT INTO tl_search_index (pid, word, relevance) VALUES (?, ?, ?)")
						   ->execute($intInsertId, $k, $v);
		}

		// Remove dynamic stop words
		$this->Database->prepare("DELETE FROM tl_search_index WHERE relevance>=?")
					   ->execute($GLOBALS['TL_CONFIG']['dynamicStopword']);

		return true;
	}


	/**
	 * Search the database and return the result object
	 * @param string
	 * @param boolean
	 * @param array
	 * @param integer
	 * @param integer
	 * @return object
	 * @throws Exception
	 */
	public function searchFor($strKeywords, $blnOrSearch=false, $arrPid=false, $intRows=0, $intOffset=0)
	{
		$this->import('String');
		$this->import('Database');

		// Clean keywords
		$strKeywords = utf8_strtolower($strKeywords);
		$strKeywords = $this->String->decodeEntities($strKeywords);

		if (function_exists('mb_eregi_replace'))
		{
			$strKeywords = mb_eregi_replace('[^[:alnum:] \*\'"\+-]', '', $strKeywords);
		}
		else
		{
			$strKeywords = preg_replace('/[^\pN\pL \*\'"\+-]/iu', ' ', $strKeywords);
		}

		// Check keyword string
		if (!strlen($strKeywords))
		{
			throw new Exception('Empty keyword string');
		}

		// Split keywords
		$arrChunks = array();
		preg_match_all('/"[^"]+"|[\+\-]?[^ ]+\*?/', $strKeywords, $arrChunks);

		$arrPhrases = array();
		$arrKeywords = array();
		$arrWildcards = array();
		$arrIncluded = array();
		$arrExcluded = array();

		foreach ($arrChunks[0] as $strKeyword)
		{
			if (substr($strKeyword, -1) == '*' && strlen($strKeyword) > 1)
			{
				$arrWildcards[] = str_replace('*', '%', $strKeyword);
				continue;
			}

			switch (substr($strKeyword, 0, 1))
			{
				// Phrases
				case '"':
					if (($strKeyword = trim(substr($strKeyword, 1, -1))) != false)
					{
						$arrPhrases[] = '[[:<:]]' . str_replace(array(' ', '*'), array('[^[:alnum:]]+', ''), $strKeyword) . '[[:>:]]';
					}
					break;

				// Included keywords
				case '+':
					if (($strKeyword = trim(substr($strKeyword, 1))) != false)
					{
						$arrIncluded[] = $strKeyword;
					}
					break;

				// Excluded keywords
				case '-':
					if (($strKeyword = trim(substr($strKeyword, 1))) != false)
					{
						$arrExcluded[] = $strKeyword;
					}
					break;

				// Wildcards
				case '*':
					if (strlen($strKeyword) > 1)
					{
						$arrWildcards[] = str_replace('*', '%', $strKeyword);
					}
					break;

				// Normal keywords
				default:
					$arrKeywords[] = $strKeyword;
					break;
			}
		}

		$intPhrases = count($arrPhrases);
		$intWildcards = count($arrWildcards);
		$intIncluded = count($arrIncluded);
		$intExcluded = count($arrExcluded);

		$intKeywords = 0;
		$arrValues = array();

		// Remember found words so we can highlight them later
		$strQuery .= "SELECT pid AS sid, GROUP_CONCAT(word) AS matches";

		// Get the number of wildcard matches
		if (!$blnOrSearch && $intWildcards)
		{
			$strQuery .= ", (SELECT COUNT(*) FROM tl_search_index WHERE (" . implode(' OR ', array_fill(0, $intWildcards, 'word LIKE ?')) . ") AND pid=sid) AS wildcards";
			$arrValues = array_merge($arrValues, $arrWildcards);
		}

		// Count the number of matches
		$strQuery .= ", COUNT(*) AS count";

		// Get the relevance
		$strQuery .= ", SUM(relevance) AS relevance";

		// Get URL, title, text and file size from tl_search
		$strQuery .= ", (SELECT url FROM tl_search WHERE tl_search.id=tl_search_index.pid) AS url";
		$strQuery .= ", (SELECT title FROM tl_search WHERE tl_search.id=tl_search_index.pid) AS title";
		$strQuery .= ", (SELECT filesize FROM tl_search WHERE tl_search.id=tl_search_index.pid) AS filesize";
		$strQuery .= ", (SELECT text FROM tl_search WHERE tl_search.id=tl_search_index.pid) AS text";

		$arrAllKeywords = array();

		// Get keywords
		if (count($arrKeywords))
		{
			$arrAllKeywords[] = implode(' OR ', array_fill(0, count($arrKeywords), 'word=?'));
			$arrValues = array_merge($arrValues, $arrKeywords);
			$intKeywords += count($arrKeywords);
		}

		// Get included keywords
		if ($intIncluded)
		{
			$arrAllKeywords[] = implode(' OR ', array_fill(0, $intIncluded, 'word=?'));
			$arrValues = array_merge($arrValues, $arrIncluded);
			$intKeywords += $intIncluded;
		}

		// Get keywords from phrases
		if ($intPhrases)
		{
			foreach ($arrPhrases as $strPhrase)
			{
				$arrWords = explode('[^[:alnum:]]+', utf8_substr($strPhrase, 7, -7));
				$arrAllKeywords[] = implode(' OR ', array_fill(0, count($arrWords), 'word=?'));
				$arrValues = array_merge($arrValues, $arrWords);
				$intKeywords += count($arrWords);
			}
		}

		// Get wildcards
		if ($intWildcards)
		{
			$arrAllKeywords[] = implode(' OR ', array_fill(0, $intWildcards, 'word LIKE ?'));
			$arrValues = array_merge($arrValues, $arrWildcards);
		}

		$strQuery .= " FROM tl_search_index WHERE (" . implode(' OR ', $arrAllKeywords) . ")";

		// Get phrases
		if ($intPhrases)
		{
			$strQuery .= " AND (" . implode(($blnOrSearch ? ' OR ' : ' AND '), array_fill(0, $intPhrases, 'pid IN(SELECT id FROM tl_search WHERE text REGEXP ?)')) . ")";
			$arrValues = array_merge($arrValues, $arrPhrases);
		}

		// Include keywords
		if ($intIncluded)
		{
			$strQuery .= " AND pid IN(SELECT pid FROM tl_search_index WHERE " . implode(' OR ', array_fill(0, $intIncluded, 'word=?')) . ")";
			$arrValues = array_merge($arrValues, $arrIncluded);
		}

		// Exclude keywords
		if ($intExcluded)
		{
			$strQuery .= " AND pid NOT IN(SELECT pid FROM tl_search_index WHERE " . implode(' OR ', array_fill(0, $intExcluded, 'word=?')) . ")";
			$arrValues = array_merge($arrValues, $arrExcluded);
		}

		// Limit results to a particular set of pages
		if (is_array($arrPid) && count($arrPid))
		{
			$strQuery .= " AND pid IN(SELECT id FROM tl_search WHERE pid IN(" . implode(',', $arrPid) . "))";
		}

		$strQuery .= " GROUP BY pid";

		// Make sure to find all words
		if (!$blnOrSearch)
		{
			// Number of keywords without wildcards
			$strQuery .= " HAVING count >= " . $intKeywords;

			// Dynamically add the number of wildcard matches
			if ($intWildcards)
			{
				$strQuery .= " + wildcards";
			}
		}

		// Sort by relevance
		$strQuery .= " ORDER BY relevance DESC";

		// Return result
		$objResultStmt = $this->Database->prepare($strQuery);

		if ($intRows > 0)
		{
			$objResultStmt->limit($intRows, $intOffset);
		}

		return $objResultStmt->execute($arrValues);
	}
}

?>