<?php
require_once("class/content.class.php");
$content = new content;
?>
<script type="text/javascript">
var mod = '<?php echo $mod?>';
</script>
<script type="text/javascript" src="asset/js/datagrid.js"></script>
<script type="text/javascript" src="asset/js/page/<?php echo $mod?>.js"></script>

<div><?php echo $title ?></div>
<div id="page-content"><?php echo $content->loadContent($mod)?></div>
<div id="page-form" style="display:none;"></div>
