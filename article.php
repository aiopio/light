<?php
	require("mysql_db_class.php");
	$id = isset($_GET['id'])?intval($_GET['id']):'';
	$max=$db->get_one("SELECT max(id) FROM {$pl}write");

	$id= ($id>$max['max(id)'])?$max['max(id)']:$id;

	$id && $text=$db->get_one("SELECT textarea FROM {$pl}write WHERE id='$id'");
	$id && $textarea=$text['textarea'];
	$content=explode("\n",$textarea);
	$title=$content[0]?$content[0]:"No Title";
	$show="<p>".implode("</p><p>",$content)."</p>";
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
p{margin: 0 0 10px;}
#editor-wrapper {
font-size: 24px;
line-height: 30px;
font-family: CSKirisameDGM, Georgia, "Times New Roman", serif;
word-wrap: break-word;
color: #0D0D0D;
}
#editor-highlighter {
cursor: text;
border: 0;
border: none;
z-index: 1;
padding: 0;
margin: 0;
font: inherit;
overflow-y: visible;
overflow-x: hidden;
white-space: pre-wrap;
color:#333333;
}
#editor-highlighter p {
margin-top: 30px;
}
#editor-highlighter p:first-child {
margin-top: 0;
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
	<div id="editor-wrapper">
		<div id="editor-highlighter">$show</div>
	</div>
</div>
</body>
</html>
EOT;
?>