<? 
	
	
	if (empty($_REQUEST['sort'])) { $sort=0; } else { $sort=$_REQUEST['sort']; }
	if (empty($_REQUEST['way'])) { $way="up"; } else { $way=$_REQUEST['way']; }
	if (empty($_REQUEST['page'])) { $page=1; } else { $page=$_REQUEST['page']; }
	if (empty($_REQUEST['tag'])) { $tag=""; } else { $tag=$_REQUEST['tag']; }
	
	$set_url="sort=$sort&way=$way&page=$page";
	$title="Gallery Manager";
	
	$table="tbl_gallery_gal";
	$dir="../upload/gallery_images/";
	
	switch ($action) {
		case "view":
			$title.=" - <span>List</span>";
		
			$df=new Dataface($table);
			
		
			$df->set_field(array("Picture Title","gal_title",400));
			$df->set_field(array("Category","gal_tag",100));
			$df->set_icon(array("status","edit","delete"));
			$df->set_page_size(30);
			//$df->set_order(false,"t_order","asc");
			
			// Get all tag category
			$rs=$df->get_row_result("Select distinct(gal_tag) as tag from tbl_gallery_gal order by gal_tag");
			
			if ($tag=="") { 
				$sql="select * from tbl_gallery_gal";
			} else {
				$sql="select * from tbl_gallery_gal where gal_tag='$tag'";
			}
			$url="?pg=$pg";
			
			break;
		case "add":	
		case "edit":		
		
			$df=new Dataface($table);
			$df->set_dir($dir);
			
			if ($action=="add") { $text=array(); } else { $text=$df->get_record($_REQUEST['id']); }
			
			
			$title.=" - <span>" . ucwords($action) . "</span>";
			$back_url="?pg=$pg&" . $set_url;
			
			$df->set_form("text","Picture Title","gal_title",$text['gal_title'],"class=\"text-large\"","");
			$df->set_form("tag","Category","gal_tag",$text['gal_tag'],"class=\"text-large\"","select distinct(gal_tag) as tag from tbl_gallery_gal where gal_tag!=''");
			$df->set_form("file","Picture File","gal_pic",$text['gal_pic'],"size=\"60\" class=\"text-large\"","");
			$df->set_form("textarea","Description","gal_desc",$text['gal_desc'],"class=\"textarea-full\" style=\"height:200px\"",""); 


			break;	
			
		case "add-action":
		case "edit-action":
			$df=new Dataface($table);
			$df->set_dir($dir);
			$df->set_resize(500);
			
			foreach ($_POST as $row => $key) {
				$_POST[$row]=stripslashes(stripslashes($key));
			}
				
			
			if ($action=="add-action") {
				$df->save("add",$_POST, array());
			} else {
				$df->save("save",$_POST, array());
			}
			
			header ("location: ?pg=$pg&" . $set_url); die();
			
			break;	
			
		case "delete-action":			
		
			$df=new Dataface($table);
			$df->delete($_REQUEST['id']);

			header ("location: ?pg=$pg&" . $set_url); die();
			
		case "status-action":			
		
			$df=new Dataface($table);
			$df->status($_REQUEST['id']);

			header ("location: ?pg=$pg&" . $set_url); die();
			
		case "order-action":			
		
			$df=new Dataface($table);
			$df->update_order($_REQUEST['id'],$_REQUEST['order'],$_REQUEST['move'],"");		

			header ("location: ?pg=$pg&" . $set_url); die();
			break;				
		
	}
	
	
	

?>

<? if ($action=="view") { ?>
<h1><? echo $title; ?></h1>

<div class="submenu" style="width:720px; margin-left:10px;">
<form action="" method="get" style="float:left;">
	<? echo $df->get_hidden_field("pg=$pg&" . $set_url); ?>
	Category: 
	<select name="tag" onchange="this.form.submit()">
		<? if ($tag=="") { ?><option value="" selected="selected">Please select...</option><? } ?>
		<? foreach ($rs as $row) { ?>
		<option value="<? echo $row['tag']; ?>" <? if ($row['tag']==$_REQUEST['tag']) { echo "selected"; } ?>><? echo $row['tag']; ?></option>
		<? } ?>
	</select>
	
</form>
<a href="<? echo $url; ?>&id=0&action=add" class="add"><img src="skin/images/icon/new.png" /></a>
</div>
<div class="clearIt"></div>
<? $df->process_list($sql,$sort,$way, $page, " cellspacing=\"0\" class=\"cms-table\"", $url); ?>

<? $df->page_split("?pg=$pg&sort=$sort&way=$way",$page); ?>

<? } ?>


<? if ($action=="add" || $action=="edit") { ?>
<h1><? echo $title; ?></h1>
<div class="submenu">

<a href="<? echo $back_url; ?>" class="back">&laquo;&nbsp;Back</a></div>
<? $df->process_form($_GET); ?>
<? } ?>