<?php
	
	if (empty($_REQUEST['sort'])) { $sort=0; } else { $sort=$_REQUEST['sort']; }
	if (empty($_REQUEST['way'])) { $way="up"; } else { $way=$_REQUEST['way']; }
	if (empty($_REQUEST['page'])) { $page=1; } else { $page=$_REQUEST['page']; }
	if (empty($_REQUEST['sw_type'])) { $sw_type = 'Benchtop'; } else { $sw_type=$_REQUEST['sw_type']; }
	
	$set_url="sort=$sort&way=$way&page=$page&sw_type=$sw_type";
	$title="Color Ideas Swatch Manager";
	
	$table="tbl_swatch_sw";
	$dir="../upload/swatch_images/";
    
    $sql = "select sw_id, sw_title from tbl_swatch_sw where sw_type LIKE 'other' order by sw_order asc";
    $gresult = $g_dbconn->Execute($sql);
    $col = array(0 => '- pick one -');
	while ($g = $gresult->fetchrow()) {
        $col[$g['sw_id']] = $g['sw_title'];
    }
    	
	switch ($action) {
		case "view":
			$title.=" - <span>List</span>";
		
			$df=new Dataface($table);
			
		
			$df->set_field(array("Swatch Title","sw_title",500));
			$df->set_icon(array("status","order","edit","delete"));
			$df->set_page_size(30);
			$df->set_order(false,"sw_order","asc");
	
			$sql="select * from tbl_swatch_sw where sw_type = '$sw_type'";
			$url="?pg=$pg&sw_type=$sw_type";
			
			break;
		case "add":	
		case "edit":		
		
			$df=new Dataface($table);
			$df->set_dir($dir);
			
			if ($action=="add") { $text=array(); } else { $text=$df->get_record($_REQUEST['id']); }
			
			
			$title.=" - <span>" . ucwords($action) . "</span>";
			$back_url="?pg=$pg&" . $set_url;
			
			$df->set_form("text","Swatch Title","sw_title",$text['sw_title'],"class=\"text-large\"","");
			$df->set_form("file","Swatch Image 172x172px","sw_image",$text['sw_image'],"size=\"60\" class=\"file-large\"","");
			$df->set_form("listbox","Swatch Type","sw_type",$text['sw_type'],"class=\"text-large\"",array('Benchtop', 'Other'));
            $df->set_form("listbox_custom","Suggested Cupboard color","cupboard_id",$text['cupboard_id'],"class=\"text-large\"",$col);
            $df->set_form("listbox_custom","Suggested Wall color","wall_id",$text['wall_id'],"class=\"text-large\"",$col);
			
			break;	
			
		case "add-action":
		case "edit-action":
			$df=new Dataface($table);
			$df->set_dir($dir);
			
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
			$df->update_order($_REQUEST['id'],$_REQUEST['order'],$_REQUEST['move'],"where sw_type = '$sw_type'");		

			header ("location: ?pg=$pg&" . $set_url); die();
			break;				
		
	}
	
	
	

?>

<?php if ($action=="view") { ?>
<h1><?php echo $title; ?></h1>
<div class="submenu" style="width:720px; margin-left:10px;">
<form action="" method="get" style="float:left;">
	<?php echo $df->get_hidden_field("pg=$pg&" . $set_url); ?>
	Category: 
	<select name="sw_type" onchange="this.form.submit()">
		<?php foreach (array('Benchtop' => 'Benchtop', 'Other' => 'Other') as $value => $text) { ?>
		<option value="<?php echo $value; ?>" <?php if ($value==$_REQUEST['sw_type']) { echo "selected"; } ?>><?php echo $text; ?></option>
		<?php } ?>
	</select>
	
</form>
<a href="<?php echo $url; ?>&id=0&action=add&sw_type=<?php echo $sw_type; ?>" class="add"><img src="skin/images/icon/new.png" /></a></div>

<?php $df->process_list($sql,$sort,$way, $page, " cellspacing=\"0\" class=\"cms-table\"", $url); ?>

<?php $df->page_split("?pg=$pg&sort=$sort&way=$way",$page); ?>

<?php } ?>


<?php if ($action=="add" || $action=="edit") { ?>
<h1><?php echo $title; ?></h1>
<div class="submenu"><a href="<?php echo $back_url; ?>" class="back">&laquo;&nbsp;Back</a></div>
<?php $df->process_form($_GET); ?>
<?php } ?>