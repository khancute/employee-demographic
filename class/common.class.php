<?php
require_once("database.class.php");
class common extends database
{
	function loadJSFile($files)
	{
		$script = "";
		if (is_array($files))
		{
			foreach($files as $jsFile)
			{
				$script .= "<script type='text/javascript' src='".$jsFile."'></script>\n";
			}
		}
		else
		{
			$script .= "<script type='text/javascript' src='".$files."'></script>\n";	
		}
		
		return $script;
	}
	
	function loadCSSFile($files)
	{
		$style = "";
		if (is_array($files))
		{
			foreach($files as $jsFile)
			{
				$style .= "<link type='text/css' href='".$jsFile."' rel='stylesheet' />\n";
			}
		}
		else
		{
			$style .= "<link type='text/css' href='".$files."' rel='stylesheet' />\n";
		}
		
		return $style;
	}
	
	function getListEselon2()
	{
		$sql = "SELECT * FROM ref_unit_eselon_2 WHERE kode NOT IN (14, 15, 16) ORDER BY uraian";
		$data = $this->dbFetchArray($sql);
		return $data;
	}
	
	function getListEselon3($eselon2)
	{
		$sql = "SELECT * FROM ref_unit_eselon_3 WHERE kode_unit LIKE '".$eselon2."%' ORDER BY uraian";
		$data = $this->dbFetchArray($sql);
		return $data;
	}
	
	function getListEselon4($eselon3)
	{
		$sql = "SELECT * FROM ref_unit_eselon_4 WHERE kode_unit LIKE '".$eselon3."%' ORDER BY uraian";
		$data = $this->dbFetchArray($sql);
		return $data;
	}
	
	function comboEselonData($list)
	{
		$data = array();
		foreach ( $list as $value )
		{
			$data[] = array("value"=>$value['kode'], "text"=>$value['uraian']);
		}
		return $data;
	}
}
?>