<?php
@session_start();
require_once("database.class.php");
class dashboard
{
	function createChart($chartSeries, $chartName, $chartType, $chartContainer, $chartTitle, $chartOption = array('size'=>85, 'color'=>"#000000", 'connectorColor'=>"#000000", 'cursor'=>"pointer") )
	{
		$chart = "";
		$chart .= "var ".$chartName.";\n";
		$chart .= "$(document).ready(function() {\n";
		$chart .= $chartName." = new Highcharts.Chart({ \n";
		$chart .= "chart: {
								renderTo: '".$chartContainer."', 
								backgroundColor: '#DEE7F6', 
							},\n";
		$chart .= "title: {text: '".$chartTitle."'},\n";
		$chart .= "tooltip: {formatter: function(){return '<b>'+ this.point.name +'</b>: '+ this.y +' ('+Math.round(this.percentage)+' %)';}},\n";
		$chart .= "plotOptions: {".$chartType.": {allowPointSelect: true, size: ".$chartOption['size'].", cursor: '".$chartOption['cursor']."', dataLabels: {
							enabled: true, 
							color: '".$chartOption['color']."', 
							connectorColor: '".$chartOption['connectorColor']."', 
							formatter: function(){
											return '<b>'+ this.point.name +'</b>: '+ this.y +' ('+Math.round(this.percentage)+' %)';
										},
							style: {
									fontSize: '8px',
									fontFamily: '\"Trebuchet MS\"'
								}, 
							}}},\n";
		$chart .= "series: [{type: '".$chartType."', name: '".$chartName."', data: [".$chartSeries."]}],\n";
		$chart .= "});\n";
		$chart .= "});\n";
		return $chart;
	}
	
	function createJSObject($arrayData)
	{
		$jsObject = "";
		foreach ( $arrayData as $data )
		{
			$jsObject .= "['".$data['xAxis']."', ".$data['yAxis']."], ";
		}
		return $jsObject;
	}
	
	function chartTingkatPendidikan()
	{
		$db = new database;
		$sql = "SELECT  uraian as xAxis,
						COUNT(tingkat_pendidikan) as yAxis
				FROM (
					SELECT b.nip, MAX(kode_pendidikan) as tingkat_pendidikan
					FROM data_pegawai_unitkerja a
					JOIN data_pegawai_pendidikan b ON a.nip = b.nip
					GROUP BY nip 
				) y
				JOIN ref_pendidikan x ON y.tingkat_pendidikan = x.kode
				GROUP BY tingkat_pendidikan";
		$result = $db->dbFetchArray($sql);
		return $result;
	}
	
	function chartJenisKelamin()
	{
		$db = new database;
		$sql = "SELECT (CASE WHEN jenis_kelamin = 0 THEN 'L' ELSE 'P' END) as xAxis,
					   COUNT(b.nip) as yAxis
				FROM data_pegawai_unitkerja a
				JOIN data_pegawai b ON a.nip = b.nip
				GROUP BY jenis_kelamin";
		$result = $db->dbFetchArray($sql);
		return $result;
	}
	
	function createGrid($gridHeader, $gridData, $gridTitle)
	{
		$grid = "";
		$grid .= "<table align='center' cellpadding='0' cellspacing='0' class='dashboard-grid'>\n";
		$grid .= "<tr><td class='grid-title' colspan='".(count($gridHeader))."'>".$gridTitle."</td></tr>\n";
		$grid .= "<tr class='grid-header'>\n";
		foreach ( $gridHeader as $header )
		{
			$grid .= "<td>".$header."</td>\n";
		}
		$grid .= "</tr>\n";
		
		$rowNumber = 1;
		foreach ( $gridData as $data )
		{
			$rowStyle = ( $rowNumber % 2 == 0 ) ? "grid-row-even" : "grid-row-odd";
			$grid .= "<tr class='grid-row ".$rowStyle."'>\n";
			foreach( $data as $cell )
			{
				$grid .= "<td>".$cell."</td>\n";
			}
			$grid .= "</tr>\n";
			$rowNumber++;
		}
		
		$grid .= "</table>";
		return $grid;
	}
	
	function gridJenjangUsia()
	{
		$db = new database;
		
		$sql = "SELECT COUNT(*) as totalRow
				FROM data_pegawai_unitkerja a
				JOIN data_pegawai b ON a.nip = b.nip";
		$rsTotal = $db->dbFetchArray($sql);
		
		$sql = "SELECT jenjangUsia, COUNT(nip) as jumlah
				FROM (
					SELECT b.nip, tanggal_lahir, TIMESTAMPDIFF(YEAR, tanggal_lahir, NOW()),
						   (CASE 
								 WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, NOW()) >= 18 AND TIMESTAMPDIFF(YEAR, tanggal_lahir, NOW()) <= 35 THEN '18-35'
								 WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, NOW()) >= 36 AND TIMESTAMPDIFF(YEAR, tanggal_lahir, NOW()) <= 40 THEN '36-40'
								 WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, NOW()) >= 41 AND TIMESTAMPDIFF(YEAR, tanggal_lahir, NOW()) <= 45 THEN '41-45'
								 WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, NOW()) >= 46 AND TIMESTAMPDIFF(YEAR, tanggal_lahir, NOW()) <= 50 THEN '46-50'
								 WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, NOW()) >= 51 AND TIMESTAMPDIFF(YEAR, tanggal_lahir, NOW()) <= 55 THEN '51-55'
								 WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, NOW()) > 55 THEN '>55'
						   END) as jenjangUsia
					FROM data_pegawai_unitkerja a
					JOIN data_pegawai b ON a.nip = b.nip
				) a
				GROUP BY jenjangUsia";	
		$result = $db->dbFetchArray($sql);
		
		for($i = 0; $i < sizeof($result); $i++)
		{
			$result[$i]['persentase'] = (round( ( $result[$i]['jumlah'] / $rsTotal[0]['totalRow'] ) * 100 ))."%";
			$result[$i]['jumlah'] = $result[$i]['jumlah']." Org";	
		}
		
		return $result;
	}
}
?>