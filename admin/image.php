<? include ("../include/include_admin.php");

	ini_set("error_reporting", E_ALL & ~E_NOTICE);
	$dir=$_REQUEST['dir'];
	
	if (empty($_REQUEST['action'])) { $action="view"; } else { $action=$_REQUEST['action']; }

	
	switch ($action) {
	
		case "view":
	
			$tmp=array();
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if ($file!="." && $file!="..") { $tmp[]=$file; }
				}
				closedir($dh);
			}	
			break;
		
		case "upload-action":


			if ($_FILES['file']['tmp_name']!='none') {
				$filename=$_FILES['file']['name'];
				if (move_uploaded_file($_FILES['file']['tmp_name'], $dir . $filename)) { echo "success"; } else { echo "file"; }
			}
			
			header("location:image.php?dir=$dir"); die();
		
			break;

		case "del-action":

			@unlink($dir . $_REQUEST['file']);
			
			header("location:image.php?dir=$dir"); die();
		
			break;

	}	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Peter Shirtcliffe - Content Management System</title>
<link type="text/css" rel="stylesheet" href="reset.css" />
<script language="javascript" type="text/javascript" src="../include/jscript_admin.js"></script>
<style>
div, input, button, select { font-family:Arial, Helvetica, sans-serif; font-size:12px;  }
.uploadbox { padding:3px 10px; background:#eee; border:#ddd solid 1px; }
.imglist { margin-top:10px; }
.imglist li { margin:0 10px 10px 0; float:left; }
.imglist li a.delete { position:absolute; display:block; text-indent:-9999px; background:url(images/btn-delete.gif) no-repeat center top; width:14px; height:14px;  }
.imglist li img { border:#ccc solid 1px; width:80px; height:80px; }
</style>
</head>
<body>

<div class="uploadbox">
	<form action="" method="post" enctype="multipart/form-data" class="form">
	<input type="hidden" name="action" value="upload-action">
	<label>Please select file to upload</label>
	<input type="file" name="file" size="50">
	<input type="hidden" name="dir" value="<? echo $dir; ?>">
	<button type="submit" class="button">Upload</button>
	</form>
</div>
		
<ul class="imglist">
	<? foreach ($tmp as $row) {
		echo "<li><a href=\"javascript:DoDel('?dir=$dir&file=$row&action=del-action')\" class=\"delete\">X</a>";
		echo "<img class=\"pic\" src=\"$dir$row\">";
		echo "</li>";
	 } ?>
		
</ul>


</body>
</html>
