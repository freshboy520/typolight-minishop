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
 * Class StyleSheets
 *
 * Provide methods to handle style sheets.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class StyleSheets extends Backend
{

	/**
	 * Import String library
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('String');
	}


	/**
	 * Update a particular style sheet in the root folder
	 */
	public function updateStyleSheet()
	{
		$objStyleSheet = $this->Database->prepare("SELECT * FROM tl_style_sheet WHERE id=?")
										->limit(1)
										->execute(CURRENT_ID);

		if ($objStyleSheet->numRows)
		{
			$this->writeStyleSheet($objStyleSheet->fetchAssoc());
		}
	}


	/**
	 * Update all style sheets in the root folder
	 */
	public function updateStyleSheets()
	{
		$objStyleSheets = $this->Database->execute("SELECT * FROM tl_style_sheet");
		$arrStyleSheets = $objStyleSheets->fetchEach('name');

		// Delete old style sheets
		foreach (scan(TL_ROOT) as $file)
		{
			if (is_dir(TL_ROOT . '/' . $file))
			{
				continue;
			}

			if (is_array($GLOBALS['TL_CONFIG']['rootFiles']) && in_array($file, $GLOBALS['TL_CONFIG']['rootFiles']))
			{
				continue;
			}

			$objFile = new File($file);

			if ($objFile->extension == 'css' && !in_array($objFile->filename, $arrStyleSheets))
			{
				$objFile->delete();
			}

			$objFile->close();
		}

		$objStyleSheets->reset();

		// Create new style sheets
		while ($objStyleSheets->next())
		{
			$this->writeStyleSheet($objStyleSheets->row());
		}
	}


	/**
	 * Write a style sheet to a file
	 * @param array
	 */
	private function writeStyleSheet($row)
	{
		if (!strlen($row['id']) || !strlen($row['name']))
		{
			return;
		}

		$objFile = new File($row['name'].'.css');
		$objFile->write("/**\n * Style sheet " . $row['name'] . "\n */");

		$objDefinitions = $this->Database->prepare("SELECT * FROM tl_style WHERE pid=? ORDER BY category, sorting")
										 ->execute($row['id']);

		if($objDefinitions->numRows)
		{
			$curCategory = null;

			foreach ($objDefinitions->fetchAllAssoc() as $v)
			{
				$newCategory = '';

				if ($curCategory !== $v['category'])
				{
					$newCategory = $v['category'];
					$curCategory = $newCategory;
				}

				$objFile->append($this->compileDefinition($v, $newCategory, true));
			}
		}

		$objFile->close();
	}


	/**
	 * Compile format definitions and return them as string
	 * @param array
	 * @param string
	 * @param boolean
	 * @return string
	 */
	public function compileDefinition($row, $category='', $blnWriteToFile=false)
	{
		$return = $blnWriteToFile ? '' : '
<pre>';

		// Category
		if (strlen($category)) $return .= '

/**
 * '.$category.'
 */';

		// Comment
		if (strlen($row['comment']))
		{
			$comment = preg_replace('@^\s*/\*+@', '', $row['comment']);
			$comment = preg_replace('@\*+/\s*$@', '', $comment);

			$return .= '
/* ' . trim($comment) . ' */';
		}

		// Selector
		$arrSelector = trimsplit(',', $this->String->decodeEntities($row['selector']));

		$return .= '
'.implode(",\n", $arrSelector).'
{';

		// Size and position
		if ($row['size'])
		{
			// Width
			$row['width'] = deserialize($row['width']);

			if (strlen($row['width']['value'])) $return .= '
	width:'.$row['width']['value'].(($row['width']['value'] == 'auto') ? '' : $row['width']['unit']).';';

			// Height
			$row['height'] = deserialize($row['height']);

			if (strlen($row['height']['value'])) $return .= '
	height:'.$row['height']['value'].(($row['height']['value'] == 'auto') ? '' : $row['height']['unit']).';';

			// Top/right/bottom/left
			$row['trbl'] = deserialize($row['trbl']);

			if (is_array($row['trbl']))
			{
				foreach ($row['trbl'] as $k=>$v)
				{
					if (strlen($v) && $k != 'unit') $return .= '
	'.$k.':'.$v.(($v == 'auto') ? '' : $row['trbl']['unit']).';';
				}
			}

			// Position
			if (strlen($row['position'])) $return .= '
	position:'.$row['position'].';';

			// Overflow
			if (strlen($row['overflow'])) $return .= '
	overflow:'.$row['overflow'].';';

			// Float
			if (strlen($row['floating'])) $return .= '
	float:'.$row['floating'].';';

			// Clear
			if (strlen($row['clear'])) $return .= '
	clear:'.$row['clear'].';';

			// Display
			if (strlen($row['display'])) $return .= '
	display:'.$row['display'].';';
		}

		// Margin, padding and alignment
		if ($row['alignment'])
		{
			// Margin
			if (strlen($row['margin']) || strlen($row['align']))
			{
				$row['margin'] = deserialize($row['margin']);

				if (is_array($row['margin']))
				{
					$top = $row['margin']['top'];
					$right = $row['margin']['right'];
					$bottom = $row['margin']['bottom'];
					$left = $row['margin']['left'];
				}

				// Overwrite left and right margin if an alignment is set
				if (strlen($row['align']))
				{
					if (in_array($row['align'], array('left', 'center')))
					{
						$right = 'auto';
					}

					if (in_array($row['align'], array('center', 'right')))
					{
						$left = 'auto';
					}
				}

				// Try to shorten definition
				if (strlen($top) && strlen($right) && strlen($bottom) && strlen($left))
				{
					if ($top == $right && $top == $bottom && $top == $left) $return .= '
	margin:'.$top.(($top == 'auto') ? '' : $row['margin']['unit']).';';

					elseif ($top == $bottom && $right == $left) $return .= '
	margin:'.$top.$row['margin']['unit'].' '.$right.(($right == 'auto') ? '' : $row['margin']['unit']).';';

					else $return .= '
	margin:'.$top.$row['margin']['unit'].' '.$right.(($right == 'auto') ? '' : $row['margin']['unit']).' '.$bottom.$row['margin']['unit'].' '.$left.(($left == 'auto') ? '' : $row['margin']['unit']).';';
				}

				else
				{
					$arrDir = array('top'=>$top, 'right'=>$right, 'bottom'=>$bottom, 'left'=>$left);

					foreach ($arrDir as $k=>$v)
					{
						if (strlen($v)) $return .= '
	margin-'.$k.':'.(($v == 'auto') ? 'auto' : $v.$row['margin']['unit']).';';
					}
				}
			}

			// Padding
			$row['padding'] = deserialize($row['padding']);

			if (is_array($row['padding']))
			{
				$top = $row['padding']['top'];
				$right = $row['padding']['right'];
				$bottom = $row['padding']['bottom'];
				$left = $row['padding']['left'];

				// Try to shorten definition
				if (strlen($top) && strlen($right) && strlen($bottom) && strlen($left))
				{
					if ($top == $right && $top == $bottom && $top == $left) $return .= '
	padding:'.$top.$row['padding']['unit'].';';

					elseif ($top == $bottom && $right == $left) $return .= '
	padding:'.$top.$row['padding']['unit'].' '.$right.$row['padding']['unit'].';';

					else $return .= '
	padding:'.$top.$row['padding']['unit'].' '.$right.$row['padding']['unit'].' '.$bottom.$row['padding']['unit'].' '.$left.$row['padding']['unit'].';';
				}

				else
				{
					$arrDir = array('top'=>$top, 'right'=>$right, 'bottom'=>$bottom, 'left'=>$left);

					foreach ($arrDir as $k=>$v)
					{
						if (strlen($v)) $return .= '
	padding-'.$k.':'.$v.$row['padding']['unit'].';';
					}
				}
			}

			// Text alignment
			if (strlen($row['textalign'])) $return .= '
	text-align:'.$row['textalign'].';';

			// Vertical alignment
			if (strlen($row['verticalalign'])) $return .= '
	vertical-align:'.$row['verticalalign'].';';
		}

		// Background
		if ($row['background'])
		{
			// Background color
			if (strlen($row['bgcolor'])) $return .= '
	background-color:#'.$row['bgcolor'].';';

			// Background image
			if (strlen($row['bgimage'])) $return .= '
	background-image:url("'.$row['bgimage'].'");';

			// Background position
			if (strlen($row['bgposition'])) $return .= '
	background-position:'.$row['bgposition'].';';

			// Background repeat
			if (strlen($row['bgrepeat'])) $return .= '
	background-repeat:'.$row['bgrepeat'].';';
		}

		// Border
		if ($row['border'])
		{
			// Border width
			$row['borderwidth'] = deserialize($row['borderwidth']);

			if (is_array($row['borderwidth']))
			{
				$top = $row['borderwidth']['top'];
				$right = $row['borderwidth']['right'];
				$bottom = $row['borderwidth']['bottom'];
				$left = $row['borderwidth']['left'];

				// Try to shorten definition
				if (strlen($top) && strlen($right) && strlen($bottom) && strlen($left) && $top == $right && $top == $bottom && $top == $left)
				{
					$return .= '
	border:'.$top.$row['borderwidth']['unit'].(strlen($row['borderstyle']) ? ' '.$row['borderstyle'] : '').(strlen($row['bordercolor']) ? ' #'.$row['bordercolor'] : '').';';
				}

				elseif (strlen($top) && strlen($right) && strlen($bottom) && strlen($left) && $top == $bottom && $left == $right)
				{
					$return .= '
	border-width:'.$top.$row['borderwidth']['unit'].' '.$right.$row['borderwidth']['unit'].';';

					if (strlen($row['borderstyle']))
						$return .= '
	border-style:'.$row['borderstyle'].';';

					if (strlen($row['bordercolor']))
						$return .= '
	border-color:#'.$row['bordercolor'].';';
				}

				else
				{
					$arrDir = array('top'=>$top, 'right'=>$right, 'bottom'=>$bottom, 'left'=>$left);

					foreach ($arrDir as $k=>$v)
					{
						if (strlen($v)) $return .= '
	border-'.$k.':'.$v.$row['borderwidth']['unit'].(strlen($row['borderstyle']) ? ' '.$row['borderstyle'] : '').(strlen($row['bordercolor']) ? ' #'.$row['bordercolor'] : '').';';
					}
				}
			}

			// Border collapse
			if (strlen($row['bordercollapse'])) $return .= '
	border-collapse:'.$row['bordercollapse'].';';
		}

		// Font
		if ($row['font'])
		{
			// Font family
			if (strlen($row['fontfamily'])) $return .= '
	font-family:'.$row['fontfamily'].';';

			// Font style
			$row['fontstyle'] = deserialize($row['fontstyle']);

			if (is_array($row['fontstyle']))
			{
				if (in_array('bold', $row['fontstyle'])) $return .= '
	font-weight:bold;';

				if (in_array('italic', $row['fontstyle'])) $return .= '
	font-style:italic;';

				if (in_array('normal', $row['fontstyle'])) $return .= '
	font-weight:normal;';

				if (in_array('underline', $row['fontstyle'])) $return .= '
	text-decoration:underline;';

				if (in_array('line-through', $row['fontstyle'])) $return .= '
	text-decoration:line-through;';

				if (in_array('overline', $row['fontstyle'])) $return .= '
	text-decoration:overline;';

				if (in_array('notUnderlined', $row['fontstyle'])) $return .= '
	text-decoration:none;';

				if (in_array('small-caps', $row['fontstyle'])) $return .= '
	font-variant:small-caps;';
			}

			// Font size
			$row['fontsize'] = deserialize($row['fontsize']);

			if (strlen($row['fontsize']['value'])) $return .= '
	font-size:'.$row['fontsize']['value'].$row['fontsize']['unit'].';';

			// Font color
			if (strlen($row['fontcolor'])) $return .= '
	color:#'.$row['fontcolor'].';';

			// Line height
			$row['lineheight'] = deserialize($row['lineheight']);

			if (strlen($row['lineheight']['value'])) $return .= '
	line-height:'.$row['lineheight']['value'].$row['lineheight']['unit'].';';

			// White space
			if (strlen($row['whitespace'])) $return .= '
	white-space:nowrap;';
		}

		// List
		if ($row['list'])
		{
			// List bullet
			if (strlen($row['liststyletype'])) $return .= '
	list-style-type:'.$row['liststyletype'].';';

			// Custom symbol
			if (strlen($row['liststyleimage'])) $return .= '
	list-style-image:url("'.$row['liststyleimage'].'");';
		}

		// Custom code
		if (strlen($row['own']))
		{
			$own = preg_split('/[\n\r]+/i', trim($this->String->decodeEntities($row['own'])));

			$return .= '
	'.implode('
	', $own);
		}

		// Close format definition
		return $return.'
}'.(($blnWriteToFile) ? '' : '
</pre>');
	}


	/**
	 * Return a form to choose an existing style sheet and import it
	 * @param object
	 * @return string
	 */
	public function importStyleSheet()
	{
		if ($this->Input->get('key') != 'import')
		{
			return '';
		}

		// Import CSS
		if ($this->Input->post('FORM_SUBMIT') == 'tl_style_sheet_import')
		{
			if (!$this->Input->post('source') || !is_array($this->Input->post('source')))
			{
				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['all_fields'];
				$this->reload();
			}

			foreach ($this->Input->post('source') as $strCssFile)
			{
				// Folders cannot be imported
				if (is_dir(TL_ROOT . '/' . $strCssFile))
				{
					$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['importFolder'], basename($strCssFile));
					continue;
				}

				$objFile = new File($strCssFile);

				// Check file extension
				if ($objFile->extension != 'css')
				{
					$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $objFile->extension);
					continue;
				}

				$strFile = $objFile->getContent();
				$strFile = str_replace("\r", '', $strFile);
				$strSource = preg_replace('/\.css$/i', '', basename($strCssFile));

				$objStyleSheet = $this->Database->prepare("SELECT * FROM tl_style_sheet WHERE name=?")
												->limit(1)
												->execute($strSource);

				// Update style sheet
				if ($objStyleSheet->numRows)
				{
					$this->Database->prepare("DELETE FROM tl_style WHERE pid=?")
								   ->execute($objStyleSheet->id);

					$insertId = $objStyleSheet->id;
				}

				// Create a new style sheet
				else
				{
					$objStyleSheet = $this->Database->prepare("INSERT INTO tl_style_sheet (tstamp, name, media) VALUES (?, ?, ?)")
													->execute(time(), preg_replace('/\.css$/i', '', basename($strCssFile)), array('all'));

					$insertId = $objStyleSheet->insertId;
				}

				$intSorting = 0;
				$strComment = '';
				$strCategory = '';
				$arrChunks = array();

				if (!is_numeric($insertId) || $insertId < 0)
				{
					throw new Exception('Invalid insert ID');
				}

				$strFile = str_replace('/**/', '[__]', $strFile);

				if (substr($strFile, 0, 2) != '/*')
				{
					$strFile = "\n" . $strFile;
				}

				preg_match_all('@(/\*.*\*\/)|([^\/}]*{[^}]*})@Us', $strFile, $arrChunks);

				// Skip comment block at the very top (if any)
				if (strpos($arrChunks[0][0], '*/') !== false)
				{
					array_shift($arrChunks[0]);
				}

				// Create format definitions
				foreach ($arrChunks[0] as $strChunk)
				{
					$strChunk = trim($strChunk);
					$strType = (substr($strChunk, 0, 2) == '/*') ? ((substr($strChunk, 0, 3) == '/**') ? 'CATEGORY' : 'COMMENT') : 'DEFINITION';
					$strChunk = preg_replace('/[\n\r\t]+/', ' ', $strChunk);

					switch ($strType)
					{
						case 'CATEGORY':
							$strCategory = str_replace(array('/*', '*/', '*', '[__]'), '', $strChunk);
							$strCategory = trim(preg_replace('/\s+/', ' ', $strCategory));
							break;

						case 'COMMENT':
							$strComment = str_replace(array('/*', '*/', '*', '[__]'), '', $strChunk);
							$strComment = trim(preg_replace('/\s+/', ' ', $strComment));
							break;

						case 'DEFINITION':
							$strChunk = preg_replace('@/\*.*\*/@U', '', $strChunk);
							$strChunk = str_replace('[__]', '/**/', $strChunk);
							$strChunk = trim(preg_replace('/\s+/', ' ', $strChunk));

							$arrDefinition = array
							(
								'pid' => $insertId,
								'category' => $strCategory,
								'comment' => $strComment,
								'sorting' => $intSorting += 128,
								'selector' => trim(preg_replace('@ ?{.*$@', '', $strChunk)),
								'attributes' => trim(preg_replace('/.*{ ?(.*) ?}.*/', '$1', $strChunk))
							);

							$this->createDefinition($arrDefinition);
							$strComment = '';
							break;
					}
				}

				$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['CONFIRM']['css_exists'], $strSource);
			}

			setcookie('BE_PAGE_OFFSET', 0, 0, '/');
			$this->reload();
		}

		$objTree = new FileTree($this->prepareForWidget($GLOBALS['TL_DCA']['tl_style_sheet']['fields']['source'], 'source', null, 'source', 'tl_style_sheet'));

		// Return form
		return '
<div id="tl_buttons">
<a href="'.$this->getReferer(ENCODE_AMPERSANDS).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_style_sheet']['import'][1].'</h2>'.$this->getMessages().'

<form action="'.ampersand($this->Environment->request, ENCODE_AMPERSANDS).'" id="tl_style_sheet_import" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_style_sheet_import" />

<div class="tl_tbox">
  <h3><label for="source">'.$GLOBALS['TL_LANG']['tl_style_sheet']['source'][0].'</label></h3>'.$objTree->generate().(strlen($GLOBALS['TL_LANG']['tl_style_sheet']['source'][1]) ? '
  <p class="tl_help">'.$GLOBALS['TL_LANG']['tl_style_sheet']['source'][1].'</p>' : '').'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="save" id="save" class="tl_submit" alt="import style sheet" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_style_sheet']['import'][0]).'" onclick="return confirm(\''. $GLOBALS['TL_LANG']['ERROR']['css_exists'] . '\');" />
</div>

</div>
</form>';
	}


	/**
	 * Create a format definition and insert it into the database
	 * @param array
	 */
	private function createDefinition($arrDefinition)
	{
		$arrSet = array
		(
			'pid' => $arrDefinition['pid'],
			'sorting' => $arrDefinition['sorting'],
			'tstamp' => time(),
			'comment' => $arrDefinition['comment'],
			'category' => $arrDefinition['category'],
			'selector' => $arrDefinition['selector']
		);

		$arrAttributes = array_map('trim', explode(';', $arrDefinition['attributes']));

		foreach ($arrAttributes as $strDefinition)
		{
			// Skip empty definitions
			if (!strlen(trim($strDefinition)))
			{
				continue;
			}

			// Handle important definitions
			if (preg_match('/important/i', $strDefinition))
			{
				$arrSet['own'][] = $strDefinition;
				continue;
			}

			$arrChunks = array_map('trim', explode(':', $strDefinition));
			$strKey = strtolower($arrChunks[0]);

			switch ($strKey)
			{
				case 'width':
				case 'height':
					$arrSet['size'] = 1;
					$arrSet[$strKey]['value'] = preg_replace('/[^0-9auto\.-]+/', '', $arrChunks[1]);
					$arrSet[$strKey]['unit'] = ($arrChunks[1] == 'auto') ? '' : preg_replace('/[^ceimnptx%]/', '', $arrChunks[1]);
					break;

				case 'top':
				case 'right':
				case 'bottom':
				case 'left':
					$arrSet['size'] = 1;
					$arrSet['trbl'][$strKey] = preg_replace('/[^0-9auto\.-]+/', '', $arrChunks[1]);
					$arrSet['trbl']['unit'] = ($arrChunks[1] == 'auto') ? '' : preg_replace('/[^ceimnptx%]/', '', $arrChunks[1]);
					break;

				case 'position':
				case 'overflow':
				case 'clear':
				case 'display':
					$arrSet['size'] = 1;
					$arrSet[$strKey] = $arrChunks[1];
					break;

				case 'float':
					$arrSet['size'] = 1;
					$arrSet['floating'] = $arrChunks[1];
					break;

				case 'margin':
				case 'padding':
					$arrSet['alignment'] = 1;
					$arrTRBL = preg_split('/\s+/', $arrChunks[1]);
					$strUnit = ($arrTRBL[0] == 'auto') ? '' : preg_replace('/[^ceimnptx%]/', '', $arrTRBL[0]);
					switch (count($arrTRBL))
					{
						case 1:
							$varValue = preg_replace('/[^0-9auto\.-]+/', '', $arrTRBL[0]);
							$arrSet[$strKey] = array
							(
								'top' => $varValue,
								'right' => $varValue,
								'bottom' => $varValue,
								'left' => $varValue,
								'unit' => $strUnit
							);
							break;

						case 2:
							$varValue_1 = preg_replace('/[^0-9auto\.-]+/', '', $arrTRBL[0]);
							$varValue_2 = preg_replace('/[^0-9auto\.-]+/', '', $arrTRBL[1]);
							$arrSet[$strKey] = array
							(
								'top' => $varValue_1,
								'right' => $varValue_2,
								'bottom' => $varValue_1,
								'left' => $varValue_2,
								'unit' => $strUnit
							);
							break;

						case 4:
							$arrSet[$strKey] = array
							(
								'top' => preg_replace('/[^0-9auto\.-]+/', '', $arrTRBL[0]),
								'right' => preg_replace('/[^0-9auto\.-]+/', '', $arrTRBL[1]),
								'bottom' => preg_replace('/[^0-9auto\.-]+/', '', $arrTRBL[2]),
								'left' => preg_replace('/[^0-9auto\.-]+/', '', $arrTRBL[3]),
								'unit' => $strUnit
							);
							break;
					}
					break;

				case 'margin-top':
				case 'margin-right':
				case 'margin-bottom':
				case 'margin-left':
					$arrSet['alignment'] = 1;
					$strName = str_replace('margin-', '', $strKey);
					$varValue = preg_replace('/[^0-9auto\.-]+/', '', $arrChunks[1]);
					$strUnit = ($arrChunks[1] == 'auto') ? '' : preg_replace('/[^ceimnptx%]/', '', $arrChunks[1]);
					$arrSet['margin'][$strName] = preg_replace('/[^0-9auto\.-]+/', '', $varValue);
					$arrSet['margin']['unit'] = $strUnit;
					break;

				case 'padding-top':
				case 'padding-right':
				case 'padding-bottom':
				case 'padding-left':
					$arrSet['alignment'] = 1;
					$strName = str_replace('padding-', '', $strKey);
					$varValue = preg_replace('/[^0-9\.-]+/', '', $arrChunks[1]);
					$strUnit = preg_replace('/[^ceimnptx%]/', '', $arrChunks[1]);
					$arrSet['padding'][$strName] = preg_replace('/[^0-9\.-]+/', '', $varValue);
					$arrSet['padding']['unit'] = $strUnit;
					break;

				case 'align':
				case 'text-align':
				case 'vertical-align':
					$arrSet['alignment'] = 1;
					$arrSet[str_replace('-', '', $strKey)] = $arrChunks[1];
					break;

				case 'background-color':
					$arrSet['background'] = 1;
					$arrSet['bgcolor'] = str_replace('#', '', $arrChunks[1]);
					break;

				case 'background-image':
					$arrSet['background'] = 1;
					$arrSet['bgimage'] = preg_replace('/url\("?([^"\)]+)"?\)/i', '$1', $arrChunks[1]);
					break;

				case 'background-position':
					$arrSet['background'] = 1;
					if (preg_match('/[0-9]+/', $arrChunks[1]))
					{
						$arrSet['own'][] = $strDefinition;
					}
					else
					{
						$arrSet['bgposition'] = $arrChunks[1];
					}
					break;

				case 'background-repeat':
					$arrSet['background'] = 1;
					$arrSet['bgrepeat'] = $arrChunks[1];
					break;

				case 'border':
					$arrSet['border'] = 1;
					$arrWSC = preg_split('/\s+/', $arrChunks[1]);
					$varValue = preg_replace('/[^0-9\.-]+/', '', $arrWSC[0]);
					$strUnit = preg_replace('/[^ceimnptx%]/', '', $arrWSC[0]);
					$arrSet['borderwidth'] = array
					(
						'top' => $varValue,
						'right' => $varValue,
						'bottom' => $varValue,
						'left' => $varValue,
						'unit' => $strUnit
					);
					if (strlen($arrWSC[1]))
					{
						$arrSet['borderstyle'] = $arrWSC[1];
					}
					if (strlen($arrWSC[2]))
					{
						$arrSet['bordercolor'] = str_replace('#', '', $arrWSC[2]);
					}
					break;

				case 'border-top':
				case 'border-right':
				case 'border-bottom':
				case 'border-left':
					$arrSet['border'] = 1;
					$arrWSC = preg_split('/\s+/', $arrChunks[1]);
					$strName = str_replace('border-', '', $strKey);
					$varValue = preg_replace('/[^0-9\.-]+/', '', $arrWSC[0]);
					$strUnit = preg_replace('/[^ceimnptx%]/', '', $arrWSC[0]);
					$arrSet['borderwidth'][$strName] = preg_replace('/[^0-9\.-]+/', '', $varValue);
					$arrSet['borderwidth']['unit'] = $strUnit;
					if (strlen($arrWSC[1]))
					{
						$arrSet['borderstyle'] = $arrWSC[1];
					}
					if (strlen($arrWSC[2]))
					{
						$arrSet['bordercolor'] = str_replace('#', '', $arrWSC[2]);
					}
					break;

				case 'border-width':
					$arrSet['border'] = 1;
					$arrTRBL = preg_split('/\s+/', $arrChunks[1]);
					$strUnit = preg_replace('/[^ceimnptx%]/', '', $arrTRBL[0]);
					switch (count($arrTRBL))
					{
						case 1:
							$varValue = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[0]);
							$arrSet['borderwidth'] = array
							(
								'top' => $varValue,
								'right' => $varValue,
								'bottom' => $varValue,
								'left' => $varValue,
								'unit' => $strUnit
							);
							break;
						case 2:
							$varValue_1 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[0]);
							$varValue_2 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[1]);
							$arrSet['borderwidth'] = array
							(
								'top' => $varValue_1,
								'right' => $varValue_2,
								'bottom' => $varValue_1,
								'left' => $varValue_2,
								'unit' => $strUnit
							);
							break;
						case 4:
							$arrSet['borderwidth'] = array
							(
								'top' => preg_replace('/[^0-9\.-]+/', '', $arrTRBL[0]),
								'right' => preg_replace('/[^0-9\.-]+/', '', $arrTRBL[1]),
								'bottom' => preg_replace('/[^0-9\.-]+/', '', $arrTRBL[2]),
								'left' => preg_replace('/[^0-9\.-]+/', '', $arrTRBL[3]),
								'unit' => $strUnit
							);
							break;
					}
					break;

				case 'border-color':
					$arrSet['border'] = 1;
					$arrSet['bordercolor'] = str_replace('#', '', $arrChunks[1]);
					break;

				case 'border-style':
				case 'border-collapse':
					$arrSet['border'] = 1;
					$arrSet[str_replace('-', '', $strKey)] = $arrChunks[1];
					break;

				case 'font-family':
					$arrSet['font'] = 1;
					$arrSet[str_replace('-', '', $strKey)] = $arrChunks[1];
					break;

				case 'font-size':
					$arrSet['font'] = 1;
					$arrSet['fontsize'] = array
					(
						'value' => preg_replace('/[^0-9\.-]+/', '', $arrChunks[1]),
						'unit' => preg_replace('/[^ceimnptx%]/', '', $arrChunks[1])
					);
					break;

				case 'font-weight':
					$arrSet['font'] = 1;
					$arrSet['fontstyle'][] = $arrChunks[1];
					break;

				case 'font-style':
					$arrSet['font'] = 1;
					if ($arrChunks[1] == 'italic')
					{
						$arrSet['fontstyle'][] = 'italic';
					}
					break;

				case 'text-decoration':
					$arrSet['font'] = 1;
					switch ($arrChunks[1])
					{
						case 'underline':
							$arrSet['fontstyle'][] = 'underline';
							break;
						case 'none':
							$arrSet['fontstyle'][] = 'notUnderlined';
							break;
						case 'overline':
						case 'line-through':
							$arrSet['fontstyle'][] = $arrChunks[1];
							break;
					}
					break;

				case 'font-variant':
					$arrSet['font'] = 1;
					if ($arrChunks[1] == 'small-caps')
					{
						$arrSet['fontstyle'][] = 'small-caps';
					}
					break;

				case 'color':
					$arrSet['font'] = 1;
					$arrSet['fontcolor'] = str_replace('#', '', $arrChunks[1]);
					break;

				case 'line-height':
					$arrSet['font'] = 1;
					$arrSet['lineheight'] = array
					(
						'value' => preg_replace('/[^0-9\.-]+/', '', $arrChunks[1]),
						'unit' => preg_replace('/[^ceimnptx%]/', '', $arrChunks[1])
					);
					break;

				case 'white-space':
					$arrSet['font'] = 1;
					$arrSet['whitespace'] = ($arrChunks[1] == 'nowrap') ? 1 : '';
					break;

				case 'list-style-type':
					$arrSet['list'] = 1;
					$arrSet[str_replace('-', '', $strKey)] = $arrChunks[1];
					break;

				case 'list-style-image':
					$arrSet['list'] = 1;
					$arrSet['liststyleimage'] = preg_replace('/url\("?([^"\)]+)"?\)/i', '$1', $arrChunks[1]);
					break;

				default:
					$arrSet['own'][] = $strDefinition;
					break;
			}
		}

		if (count($arrSet['own']))
		{
			$arrSet['own'] = implode(";\n", $arrSet['own']) . ';';
		}

		$this->Database->prepare("INSERT INTO tl_style %s")->set($arrSet)->execute();
	}
}

?>