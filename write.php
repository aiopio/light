<?php

$textarea = isset($_POST['textarea'])?mysql_real_escape_string($_POST['textarea']):'';
require("mysql_db_class.php");
if($textarea){
	//
	$save=isset($_POST['save'])?intval($_POST['save']):'';
	if($save){
		$db->query("UPDATE {$pl}write SET textarea='$textarea' WHERE id='$save'");
	}else{
		//pl_
		$db->query("INSERT INTO {$pl}write SET textarea='$textarea'");
		$save=$db->insert_id();
	}
	die("$save");
}else{

	$id = isset($_GET['id'])?intval($_GET['id']):'';
	$max=$db->get_one("SELECT max(id) FROM {$pl}write");
	
	$id= ($id>$max['max(id)'])?$max['max(id)']:$id;

	$id && $text=$db->get_one("SELECT textarea FROM {$pl}write WHERE id='$id'");
	$id && $textarea=$text['textarea'];
	$min=($id==intval($id/10)*10)?(intval($id/10)-1)*10:(intval($id/10)*10);
	$list="";
	$query=$db->query("SELECT id,textarea FROM {$pl}write LIMIT $min,10");
	while ($rs=$db->fetch_array($query)) {
		$content=explode("\n",$rs['textarea']);
		$title=$content[0]?$content[0]:"No Title";
		$active=($id==$rs['id'])?" class='active'":"";
		$list.="<li{$active}><a href='?id={$rs['id']}'>$title</a></li>";
	}
	$p=($min)<10?'1':($min-1);
	$n=$min+11;

print<<<EOT
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Editor</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>
#editor-wrapper {
font-size: 24px;
line-height: 30px;
font-family: CSKirisameDGM, Georgia, "Times New Roman", serif;
word-wrap: break-word;
color: #0D0D0D;
}
textarea {
margin: 0;
padding: 0;
border: 0;
font-size: 100%;
font: inherit;
vertical-align: baseline;
}
#editor {
height: 600px;
max-height: 600px;
border-radius: 0;
background-color: transparent;
resize: none;
border: 0;
border: none;
box-shadow: none;
z-index: 100;
padding: 0;
margin: 0;
overflow-y: visible;
overflow-x: hidden;
font: inherit;
white-space: pre-wrap;
opacity: 0.6;
outline: none;
}
</style>

</head>
<body>
<div class="row">
<!------>
<div class="col-md-3">
<br>
<ul class="nav" style="padding-left: 20px;">
{$list}
</ul>
<ul class="pager">
  <li><a href="?id=$p">Previous</a></li>
  <li><a href="?id=$n">Next</a></li>
</ul>
</div>
<!---./--->


<div class="col-md-9" id="editor-wrapper">
<textarea class="col-md-8" id="editor">{$textarea}</textarea>
<input type="hidden" id="id" value="{$id}">


<div class="col-md-4">
<br>
<button type="submit" class="btn btn-default" title="Ctrl+S">SAVE</button>
<button type="submit" class="btn btn-default" title="Ctrl+M">NEW</button>
</div>

</div>
<!---./--->
</div>
<script>
function newone(){
document.getElementById('editor').value='';
document.getElementById('id').value='';
}
function save()
{
var xmlhttp;
if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange=function()
{
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
  {
   	console.log("resulte:");
   	console.log(xmlhttp.responseText);
   	document.getElementById('id').value=xmlhttp.responseText;
  }
}
xmlhttp.open("POST","write.php",true);
var id=document.getElementById('id').value;
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send("save="+id+"&textarea="+encodeURIComponent(document.getElementById('editor').value));
}

var isCtrl = false;
document.onkeyup=function(e){
    if(e.keyCode == 17) isCtrl=false;
}

document.onkeydown=function(e){
    if(e.keyCode == 17) isCtrl=true;
    if(e.keyCode == 83 && isCtrl == true) {
        //run code for CTRL+S -- to save!
        console.log('CTRL+S:save');
        save();
        return false;
    }
    if(e.keyCode == 77 && isCtrl == true){
    	//run code for CTRL+M -- to new
    	console.log('CTRL+M:new');
    	newone();
    	return false;
    }
}
</script>
</body>
</html>
EOT;
}
?>