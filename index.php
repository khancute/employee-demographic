<?php
$mod = $_GET['mod'];
$title = ucwords(str_replace("-", " ", $mod));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><!-- Limpid ♥ IE in quirks mode -->

<title>Sistem Informasi Demografi Kepegawaian - Bapepem-LK | <?php echo $title ?></title>
<link type="text/css" href="asset/css/main.css" rel="stylesheet" />
<link type="text/css" href="asset/css/grid.css" rel="stylesheet" />
<link type="text/css" href="asset/css/superfish.css" rel="stylesheet" />
<link type="text/css" href="asset/css/custom-theme/jquery-ui-1.8.9.custom.css" rel="stylesheet" />
<script type="text/javascript" src="asset/js/jquery.js"></script>
<script type="text/javascript" src="asset/js/jquery-ui-1.8.9.custom.min.js"></script>
<script type="text/javascript" src="asset/js/hoverIntent.js"></script>
<script type="text/javascript" src="asset/js/superfish.js"></script>
<script type="text/javascript" src="asset/js/supersubs.js"></script>
<script type="text/javascript" src="asset/js/highcharts.js"></script>
<script type="text/javascript">
jQuery(function(){
	jQuery('ul.sf-menu').supersubs({
			minWidth: 10,
			maxWidth: 30,
			extraWidth: 1
		})
	.superfish();
	$("input:submit, input:button").button();
});

</script>

</head>
<body>
<div id="header">
	<div id="app-title">
    	<div id="app-logo">Sistem Informasi Demografi Kepegawaian - Bapepem-LK</div>
    	<div id="app-notification">Selamat datang, user | logout</div>
    </div>
    <div id="app-nav">
    <?php include("layout/menu.php");?>
    </div>
</div>
<!--<div id="footer"> &nbsp; </div>-->
<div id="content">
	<div id="west-section"><?php include("layout/west-section.php");?></div>
    <div id="center-section"><?php include("layout/center-section.php");?></div>
    <div id="east-section"><?php include("layout/east-section.php");?></div>
</div>
</body>
</html>