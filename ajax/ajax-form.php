<?php
require_once("../class/content.form.class.php");
$form = new contentForm;
echo $form->loadForm($_GET['mod']);
?>