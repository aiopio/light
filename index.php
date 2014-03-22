<?php
//Datebase class
require("mysql_db_class.php");
//Page
$page=isset($_GET['page'])?intval($_GET['page']):0;
$p=$page-1;
$n=$page+1;
$p=$p<1?'0':$p;
//List
$min=intval($page)*10;
$list="";
$query=$db->query("SELECT id,textarea FROM {$pl}write ORDER BY id DESC LIMIT $min,10");
while ($rs=$db->fetch_array($query)) {
	$content=explode("\n",$rs['textarea']);
	$title=$content[0]?$content[0]:"No Title";
	$list.="<li><a href='article.php?id={$rs['id']}'>$title</a></li>";
}
//Print
print<<<EOT
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>$title</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>
/* Customize container */
@media (min-width: 768px) {
  .container {
    max-width: 730px;
  }
}
.navlist{
font-size: 24px;
line-height: 30px;
font-family: CSKirisameDGM, Georgia, "Times New Roman", serif;
word-wrap: break-word;
color: #0D0D0D;
}
</style>
</head>
<body>
<div class="container">
	<div class="header">
        <ul class="nav nav-pills pull-right">
			<li class="active"><a href="index.php">Home</a></li>
        </ul>
        <h3 class="text-muted">PULOGE</h3>
	</div>
	<ul class="nav navlist">
	$list
	</ul>
	<ul class="pager">
		<li><a href="?page=$p">Previous</a></li>
		<li><a href="?page=$n">Next</a></li>
	</ul>
</div>
</body>
</html>
EOT;
?>