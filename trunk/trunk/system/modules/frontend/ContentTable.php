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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ContentTable
 *
 * Front end content element "table".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ContentTable extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_table';


	/**
	 * Generate content element
	 */
	protected function compile()
	{
		$rows = deserialize($this->tableitems);

		$this->Template->id = 'table_' . $this->id;
		$this->Template->summary = specialchars($this->summary);
		$this->Template->useHeader = $this->thead ? true : false;
		$this->Template->useFooter = $this->tfoot ? true : false;
		$this->Template->sortable = $this->sortable ? true : false;

		$arrHeader = array();
		$arrBody = array();
		$arrFooter = array();

		// Table header
		if ($this->thead)
		{
			foreach ($rows[0] as $i=>$v)
			{
				// Set table sort cookie
				if ($this->sortable && $i == $this->sortIndex)
				{
					$co = 'TS_TABLE_' . $this->id;
					$so = ($this->sortOrder == 'descending') ? 'desc' : 'asc';

					if (!strlen($this->Input->cookie($co)))
					{
						setcookie($co, $i . '|' . $so, 0, '/');
					}
				}

				// Add cell
				$arrHeader[] = array
				(
					'class' => 'head_'.$i . (($i == 0) ? ' col_first' : '') . (($i == (count($rows[0]) - 1)) ? ' col_last' : ''),
					'content' => (strlen($v) ? preg_replace('/[\n\r]+/i', '<br />', $v) : '&nbsp;')
				);
			}

			array_shift($rows);
		}

		$this->Template->header = $arrHeader;
		$limit = $this->tfoot ? (count($rows)-1) : count($rows);

		// Table body
		for ($j=0; $j<$limit; $j++)
		{
			$class_tr = '';

			if ($j == 0)
			{
				$class_tr = ' row_first';
			}

			if ($j == ($limit - 1))
			{
				$class_tr = ' row_last';
			}

			$class_eo = (($j % 2) == 0) ? ' even' : ' odd';

			foreach ($rows[$j] as $i=>$v)
			{
				$class_td = '';

				if ($i == 0)
				{
					$class_td = ' col_first';
				}

				if ($i == (count($rows[$j]) - 1))
				{
					$class_td = ' col_last';
				}

				$arrBody['row_' . $j . $class_tr . $class_eo][] = array
				(
					'class' => 'col_'.$i . $class_td,
					'content' => (strlen($v) ? preg_replace('/[\n\r]+/i', '<br />', $v) : '&nbsp;')
				);
			}
		}

		$this->Template->body = $arrBody;

		// Table footer
		if ($this->tfoot)
		{
			foreach ($rows[(count($rows)-1)] as $i=>$v)
			{
				$arrFooter[] = array
				(
					'class' => 'foot_'.$i . (($i == 0) ? ' col_first' : '') . (($i == (count($rows[(count($rows)-1)]) - 1)) ? ' col_last' : ''),
					'content' => (strlen($v) ? preg_replace('/[\n\r]+/i', '<br />', $v) : '&nbsp;')
				);
			}
		}

		$this->Template->footer = $arrFooter;
	}
}

?>