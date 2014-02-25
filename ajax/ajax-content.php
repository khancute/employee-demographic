<?php
require_once("../class/content.class.php");
$content = new content;
echo $content->loadContent($_GET['mod']);
?>