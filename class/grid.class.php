<?php
require_once("database.class.php");
require_once("form.class.php");
class grid extends database
{
	var $gridRowPerPage = 10;
	var $gridCurrentPage = 1;
	var $gridTotalRow;
	var $gridTotalPage;
	var $gridData;
	var $gridRows;
	var $gridId = "data-grid";
	var $gridFieldCount;
	var $gridFieldId;
	var $isChild = false;
	var $withRowSelection = false;
	var $withChildGrid = false;
	var $gridChild;
	
	function gridHeaderFromArray($headerData)
	{
		$gridHeader = "<tr>\n";
		$gridHeader .= "<td class='grid-header' colspan='2'>No.</td>\n";
		foreach ( $headerData as $header )
		{
			$gridHeader .= "<td class='grid-header'><a href='#'>".$header."</a></td>\n";
		}
		$gridHeader .= "</tr>\n";
		return $gridHeader;
	}
	
	function gridHeaderFromHTML($headerData)
	{
		
		return $gridHeader;
	}
	
	function dataGridHeader($gridHeader)
	{
		if ( is_array ($gridHeader) )
		{
			$return = $this->gridHeaderFromArray($gridHeader);
		}
		else
		{
			$return = $gridHeader;
		}
		return $return;
	}
	
	function dataGrid($gridHeader, $gridRows, $gridFilter = "", $gridFunctions = "", $useFooter = true)
	{
		$fieldCount = $this->gridFieldCount;
		$grid = "<div class='grid-container' id='".$this->gridId."'>\n";
		$grid .= "<table cellpadding='0' cellspacing='0' align='center' width='100%' border='0'>\n";
		$grid .= ($gridFilter!="") ? $this->dataGridFilter($gridFilter) : "";
		$grid .= $this->dataGridHeader($gridHeader);
		$grid .= $this->dataGridRows($gridRows);
		$grid .= ($useFooter) ? $this->dataGridFooter($fieldCount) : "";
		$grid .= ($useFooter && $gridFunctions!="") ? $this->dataGridFunctions($fieldCount, $gridFunctions) : "";
		$grid .= "</table>\n";
		$grid .= "</div>\n";
		
		return $grid;
	}
	
	function dataGridRows($gridRows)
	{
		$return = "";
		
		$rowSelectEvent = ($this->withRowSelection) ? $this->gridRowSelectEvent() : "";
		
		if ( $gridRows != "null" )
		{
			$rowIndex = 1;
			$rowNumber = ( ($this->gridCurrentPage - 1) * $this->gridRowPerPage ) + 1;
			foreach ( $gridRows as $row )
			{
				$showChildEvent = ($this->withChildGrid) ? $this->gridShowChildEvent($this->gridId."-".$rowNumber) : "";
				$haveChild = ($this->withChildGrid) ? "<img class='child-show-button' src='asset/img/arrow_down.png' showChild='false' width='12' title='lihat detail...' onclick='".$rowSelectEvent.$showChildEvent."'>" : "";
				$evenOddRow = ( $rowIndex % 2 == 0 ) ? "even-row" : "odd-row";
				$return .= "<tr class='grid-row ".$evenOddRow."' rowId='".$row[$this->gridFieldId]."' id='".$this->gridId."-".$rowNumber."' onclick='".$rowSelectEvent."'>\n";
				$return .= "<td align='right' style='background:#D0DEF0; border-bottom:#CCC 1px dotted; border-right:#CCC 1px dotted;' width='5'>".$haveChild."</td>\n";
				$return .= "<td class='grid-cell' width='10'>".$rowNumber."</td>\n";
				foreach ( $row as $cells )
				{
					$return .= "<td class='grid-cell'>".$cells."</td>\n";
				}
				$return .= "</tr>\n";
				$rowNumber++;
			}
		}
		
		return $return;
	}
	
	function gridRowSelectEvent()
	{
		$return = "";
		$return .= "rowSelection(this, \"".$this->gridFieldId."\", \"".$this->gridId."\");";
		return $return;
	}
	
	function gridShowChildEvent($gridRowId)
	{
		$return = "";
		$return .= "showGridChild(this, \"".$gridRowId."\", \"".$this->gridFieldId."\", \"".$this->gridId."\", \"".$this->gridChild."\", ".($this->gridFieldCount-1).");";
		return $return;
	}
	
	function dataGridFilter($filterField)
	{
		$form = new form;
		$return = "";
		$return .= "<tr>\n";
		$return .= "<td colspan='".$this->gridFieldCount."' class='grid-filter'>\n";
		$return .= "<div id='grid-filter-field' showFilter='false'>
					<img class='filter-show-button' src='asset/img/arrow_up.png' width='12' onclick='filterVisibility(\"".$this->gridId."\")'>
					Pencarian
					</div>";
		$return .= "<form method='GET'>\n";
		foreach ( $filterField as $filter )
		{
			$return .= "<div class='filter-field'>\n";
			if  ( $filter['type'] == 'text' )
			{
				$filterInput = $form->textBox($filter['name'], $filter['id'], $filter['label'], "", false, $filter['attr']);
			}
			elseif  ( $filter['type'] == 'combo' )
			{
				$filterInput = $form->comboBox($filter['name'], $filter['id'], $filter['label'], $filter['data'], $filter['nullvalue'], "-", $filter['attr']);
			}
			$return .= $filterInput;
			$return .= "</div>\n";
		}
		$return .= "<div class='filter-field'>\n";
		$return .= $form->button("btn_filter", "btn_filter", "Cari");
		$return .= "</form>\n";
		$return .= "</div>\n";
		$return .= "</td>\n";
		$return .= "</tr>\n";
		return $return;
	}
	
	function getGridData($sql)
	{
		$this->getGridInfo($sql);
		$sql = $sql.$this->gridLimit();
		$data = $this->dbFetchArray($sql);
		$this->gridRows = $data;
		return $data;
	}
	
	function getGridInfo($sql)
	{
		$sql = $sql;
		$data = $this->dbFetchArray($sql);
		$this->gridTotalRow = count($data);
		$this->gridTotalPage = ceil( $this->gridTotalRow / $this->gridRowPerPage );
		$this->gridData = $data;
	}
	
	function gridLimit()
	{
		$startingRow = ($this->gridCurrentPage - 1) * $this->gridRowPerPage;
		$limitStatement = " LIMIT ".$startingRow.", ".$this->gridRowPerPage;
		return $limitStatement;
	}
	
	function dataGridFunctions($colCount, $buttons)
	{
		$form = new form;
		$return = "";
		$return .= "<tr>\n";
		$return .= "<td colspan='".$colCount."' class='grid-functions'>\n";
		$return .= "<div>\n";
		$return .= $form->inlineButton($buttons);
		$return .= "</div>\n";
		$return .= "</td>\n";
		$return .= "</tr>\n";
		return $return;	
	}
	
	function dataGridFooter($colCount)
	{
		$prevPage = ( $this->gridCurrentPage > 1 ) ? $this->gridCurrentPage - 1 : 1;
		$nextPage = ( $this->gridCurrentPage < $this->gridTotalPage ) ? $this->gridCurrentPage + 1 : $this->gridTotalPage;
		$return = "";
		$return .= "<tr>\n";
		$return .= "<td colspan='".$colCount."'>\n";
		$return .= "<div class='grid-footer'>\n";
		$return .= "<div class='grid-footer-items'>
						<a href='javascript:gotoPage(1)'><img src='asset/img/go_first.png' width='16' /></a>
						<a href='javascript:gotoPage(".$prevPage.")'><img src='asset/img/back.png' width='16' /></a>
						<span class='grid-footer-info'>Halaman ".$this->gridCurrentPage." dari total ".$this->gridTotalPage." Halaman</span>
						<a href='javascript:gotoPage(".$nextPage.")'><img src='asset/img/forward.png' width='16' /></a>
						<a href='javascript:gotoPage(".$this->gridTotalPage.")'><img src='asset/img/finish.png' width='16' /></a>
					</div>\n";
		$return .= "</div>\n";
		$return .= "</td>\n";
		$return .= "</tr>\n";
		return $return;
	}
}
?>