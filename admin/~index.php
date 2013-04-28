<? include ("../include/include_admin.php");

	ini_set("error_reporting", E_ALL & ~E_NOTICE);
	if (!IsLogin()) { header ("Location:login.php"); }
	
	if (empty($_REQUEST['pg'])) { $pg="page"; } else { $pg=$_REQUEST['pg']; }
	if (empty($_REQUEST['action'])) { $action="view"; } else { $action=$_REQUEST['action']; }
	
	
	
	switch ($pg) {
		// Admin
		case "news": $inc_body="news.php"; break;
		case "image": $inc_body="file.php"; break;
		case "page": $inc_body="page.php"; break;
		case "gallery": $inc_body="gallery.php"; break;
		case "video": $inc_body="video.php"; break;
		case "news": $inc_body="news.php"; break;
		case "research": $inc_body="research.php"; break;
		case "reference": $inc_body="reference.php"; break;
		case "article": $inc_body="article.php"; break;
		case "member": $inc_body="member.php"; break;
		case "support": $inc_body="support.php"; break;
		case "setting": $inc_body="setting.php"; break;
		case "logout": session_destroy(); header("location:login.php");  break;
		
	}

	$temp=explode("-",$action);
	if (@$temp[1]=="action") { include ($inc_body); }



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>1080: The Facts - Content Management System</title>
<link type="text/css" rel="stylesheet" href="reset.css" />
<link type="text/css" rel="stylesheet" href="style.css" />
<script language="javascript" type="text/javascript" src="../lib/ckeditor/ckeditor.js"></script>
<script language="javascript" type="text/javascript" src="../js/jscript_admin.js"></script>

</head>

<body>

<div id="wrapper">
	<div id="header">
		<a href="?pg=logout" class="view">Logout</a> <a href="../" class="view">View website</a>
		<ul>
			<li <? if ($pg=="page") { echo "class=\"selected\""; } ?>><a href="?pg=page">Pages</a></li>
			<li <? if ($pg=="news") { echo "class=\"selected\""; } ?>><a href="?pg=news">News</a></li>
			<li <? if ($pg=="article") { echo "class=\"selected\""; } ?>><a href="?pg=article">Articles</a></li>
			<li <? if ($pg=="gallery") { echo "class=\"selected\""; } ?>><a href="?pg=gallery">Gallery</a></li>
			<li <? if ($pg=="video") { echo "class=\"selected\""; } ?>><a href="?pg=video">Videos</a></li>
			<li <? if ($pg=="research") { echo "class=\"selected\""; } ?>><a href="?pg=research">Research</a></li>
			<li <? if ($pg=="reference") { echo "class=\"selected\""; } ?>><a href="?pg=reference">Reference</a></li>
			<li <? if ($pg=="member") { echo "class=\"selected\""; } ?>><a href="?pg=member">Member</a></li>
			<li <? if ($pg=="support") { echo "class=\"selected\""; } ?>><a href="?pg=support">Support Orgs</a></li>
			<li <? if ($pg=="setting") { echo "class=\"selected\""; } ?>><a href="?pg=setting">Setting</a></li>
			
		</ul>
	
	</div><!-- end header -->
	
	<div id="main">
		<div id="body"><? include ($inc_body); ?></div><!-- end body -->
	
	
	</div><!-- end main -->
	
	<div id="footer">Copyright 2009 3POINT8, All Rights Reserved.</div>


</div><!-- end wrapper -->


</body>
</html>
