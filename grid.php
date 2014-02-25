<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Grid Sample</title>
<link type="text/css" href="asset/css/grid.css" rel="stylesheet" />
<script type="text/javascript" src="asset/js/jquery.js"></script>
<script type="text/javascript" src="asset/js/datagrid.js"></script>
</head>

<body>
<?php
require_once("class/content.class.php");
$content = new content;
echo $content->loadContent("data_pegawai_pendidikan");
?>
</body>
</html>