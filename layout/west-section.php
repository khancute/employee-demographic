
<?php
require_once("class/dashboard.class.php");
$dashboard = new dashboard;

$tingkatPendidikanData = $dashboard->chartTingkatPendidikan();
$tingkatPendidikanSeries = $dashboard->createJSObject($tingkatPendidikanData);
$tingkatPendidikan = $dashboard->createChart($tingkatPendidikanSeries, "tingkatPendidikan", "pie", "tingkatPendidikanContainer", "Tingkat Pendidikan");

$jenisKelaminData = $dashboard->chartJenisKelamin();
$jenisKelaminSeries = $dashboard->createJSObject($jenisKelaminData);
$jenisKelamin = $dashboard->createChart($jenisKelaminSeries, "jenisKelamin", "pie", "jenisKelaminContainer", "Jenis Kelamin");

?>
<script type="text/javascript"><?php echo $tingkatPendidikan;?></script>
<script type="text/javascript"><?php echo $jenisKelamin;?></script>
<div id="dashboard-header">&nbsp;</div>
<div id="tingkatPendidikanContainer" style="margin: 0 auto; width:90%; height:200px;"></div>
<div id="jenisKelaminContainer" style="margin: 0 auto; width:90%; height:200px;"></div>