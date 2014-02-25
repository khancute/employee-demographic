<?php
class form
{
	function textBox($objName, $objId, $objLabel, $objValue, $objIsRequired = false, $objAttr = array())
	{
		$formObject = "";
		$formObject .="<div id='form-field'>\n";
		$formObject .="<div id='form-field-label'>".$objLabel."</div>\n";
		$formObject .="<div id='form-field-object'>";
		$formObject .= "<input type='text' name='".$objName."' id='".$objId."' value='".$objValue."' ";
		if( sizeof($objAttr) > 0 )
		{
			foreach ( $objAttr as $key=>$attr )
			{
				$formObject .= $key."='".$attr."' ";
			}
		}
		$formObject .= " />\n";
		$formObject .="</div>\n";
		$formObject .= ($objIsRequired) ? "<div id='form-field-required'>*</div>\n" : "";
		$formObject .="</div>\n";
		return $formObject;
	}
	
	function button($objName, $objId, $objValue, $objType = "button", $objAttr = array())
	{
		$formObject = "";
		$formObject .="<div id='form-field'>\n";
		$formObject .="<div id='form-field-label'>&nbsp;</div>\n";
		$formObject .="<div id='form-field-object'>";
		$formObject .= "<input type='".$objType."' name='".$objName."' id='".$objId."' value='".$objValue."' ";
		if( sizeof($objAttr) > 0 )
		{
			foreach ( $objAttr as $key=>$attr )
			{
				$formObject .= $key."='".$attr."' ";
			}
		}
		$formObject .= " />\n";
		$formObject .="</div>\n";
		$formObject .="</div>\n";
		return $formObject;
	}
	
	function inlineButton($object)
	{
		$formObject = "";
		$formObject .="<div id='form-field'>\n";
		$formObject .="<div id='form-field-object'>";
		foreach ( $object as $obj )
		{
			$formObject .= "<input type='button' name='".$obj['name']."' id='".$obj['id']."' value='".$obj['value']."' ";
			if( sizeof($obj['attr']) > 0 )
			{
				foreach ( $obj['attr'] as $key=>$attr )
				{
					$formObject .= $key."='".$attr."' ";
				}
			}
			$formObject .= " />\n";
		}
		$formObject .="</div>\n";
		$formObject .="</div>\n";
		return $formObject;
	}
	
	function textArea($objName, $objId, $objLabel, $objCols = 35, $objRows = 3, $objAttr = array(), $objIsRequired = false, $objValue)
	{
		$formObject = "";
		$formObject .="<div id='form-field'>\n";
		$formObject .="<div id='form-field-label'>".$objLabel."</div>\n";
		$formObject .="<div id='form-field-object'>";
		$formObject .= "<textarea name='".$objName."' id='".$objId."' rows='".$objRows."' cols='".$objCols."' ";
		if( sizeof($objAttr) > 0 )
		{
			foreach ( $objAttr as $key=>$attr )
			{
				$formObject .= $key."='".$attr."' ";
			}
		}
		$formObject .= " >".$objValue."</textarea>\n";
		$formObject .="</div>\n";
		$formObject .= ($objIsRequired) ? "<div id='form-field-required'>*</div>\n" : "";
		$formObject .="</div>\n";
		return $formObject;
	}
	
	function comboBox($objName, $objId, $objLabel, $objData, $objDefaultNull = true, $objSelectedValue = "-", $objAttr = array())
	{
		$formObject = "";
		$formObject .="<div id='form-field'>\n";
		$formObject .="<div id='form-field-label'>".$objLabel."</div>\n";
		$formObject .="<div id='form-field-object'>";
		$formObject .= "<select name='".$objName."' id='".$objId."' ";
		if( sizeof($objAttr) > 0 )
		{
			foreach ( $objAttr as $key=>$attr )
			{
				$formObject .= $key."='".$attr."' ";
			}
		}
		$formObject .= " >\n";
		
		if($objDefaultNull)
		{
			$formObject .= "<option value='-'>&nbsp;&nbsp;-&nbsp;&nbsp;</option>\n";
		}
		
		foreach ( $objData as $data )
		{
			$selected = ( $data['value'] == $objSelectedValue ) ? "selected" : "";
			$formObject .= "<option value='".$data['value']."' ".$selected.">".$data['text']."</option>\n";
		}
		$formObject .= "</select>\n";
		$formObject .="</div>\n";
		$formObject .= ($objIsRequired) ? "<div id='form-field-required'>*</div>\n" : "";
		$formObject .="</div>\n";
		
		return $formObject;
	}
}
?>