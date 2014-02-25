<?php
require_once("class/dashboard.class.php");
$dashboard = new dashboard;

$jenjangUsiaData = $dashboard->gridJenjangUsia();
$jenjangUsiaHeader = array("Jenjang Usia", "Jumlah", "%");
$jenjangUsia = $dashboard->createGrid($jenjangUsiaHeader, $jenjangUsiaData, "Jenjang Usia");

?>
<link type="text/css" href="asset/css/dashboard-grid.css" rel="stylesheet" />
<div id="dashboard-header">&nbsp;</div>
<div id="pejabatContainer" style="margin: 0 auto; width:90%;"></div>
<div id="jenjangUsiaContainer" style="margin: 0 auto; width:90%;"><?php echo $jenjangUsia; ?></div>

