<?php 
	
    /* get groups */
    $sql = "select grp_id, grp_name from tbl_termgroup_grp order by grp_order asc";
    $gresult = $g_dbconn->Execute($sql);
    $gc = 0; 
    $gs = array(0 => '- pick one -');
    while ($g = $gresult->fetchrow()) { 
		$options .= "<option value=\"".$g['grp_id']."\" ";
        if ($g['grp_id']==$_REQUEST['faq_type']) $options .= "selected"; 
        $options .= ">".$g['grp_name']."</option>";
        $gs[$g['grp_id']] = $g['grp_name'];
        
        if ($gc == 0) $grpdefalut = $g['grp_id'];
        $gc ++;
	}
	
	if (empty($_REQUEST['sort'])) { $sort=0; } else { $sort=$_REQUEST['sort']; }
	if (empty($_REQUEST['way'])) { $way="up"; } else { $way=$_REQUEST['way']; }
	if (empty($_REQUEST['page'])) { $page=1; } else { $page=$_REQUEST['page']; }
    if (empty($_REQUEST['faq_type'])) { $faq_type = $grpdefalut; } else { $faq_type=$_REQUEST['faq_type']; }
	
	$set_url="sort=$sort&way=$way&page=$page";
	$title="Terms Manager";
	
	$table="tbl_term_term";
	
	
	switch ($action) {
		case "view":
			$title.=" - <span>List</span>";
		
			$df=new Dataface($table);
			
		
			$df->set_field(array("Question","term_title",500));
			$df->set_icon(array("status","order","edit","delete"));
			$df->set_page_size(30);
			$df->set_order(false,"term_order","asc");
	
			$sql="select * from tbl_term_term where group_id = ".$faq_type;
			$url="?pg=$pg";
			
			break;
		case "add":	
		case "edit":		
		
			$df=new Dataface($table);
			$df->set_dir($dir);
			
			if ($action=="add") { $text=array(); } else { $text=$df->get_record($_REQUEST['id']); }
			
			
			$title.=" - <span>" . ucwords($action) . "</span>";
			$back_url="?pg=$pg&" . $set_url;
			
            $df->set_form("listbox_custom","Group","group_id",$text['group_id'],"class=\"text-large\"",$gs);
			$df->set_form("text","Question","term_title",$text['term_title'],"class=\"text-large\"","");
			$df->set_form("textarea","Answer","term_desc",$text['term_desc'],"class=\"textarea-full\" style=\"height:200px\"",""); 


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
			$df->update_order($_REQUEST['id'],$_REQUEST['order'],$_REQUEST['move'],"");		

			header ("location: ?pg=$pg&" . $set_url); die();
			break;				
		
	}
	
	
	

?>

<?php if ($action=="view") { ?>
<h1><?php echo $title; ?></h1>
<div class="submenu" style="width:720px; margin-left:10px;">
    <form action="" method="get" style="float:left;">
	<?php echo $df->get_hidden_field("pg=$pg&" . $set_url); ?>
	FAQ Group: 
	<select name="faq_type" onchange="this.form.submit()">
		<?php echo $options; ?>
	</select>
	
    </form>
    <a href="<?php echo $url; ?>&id=0&action=add" class="add"><img src="skin/images/icon/new.png" /></a>
</div>

<?php $df->process_list($sql,$sort,$way, $page, " cellspacing=\"0\" class=\"cms-table\"", $url); ?>

<?php $df->page_split("?pg=$pg&sort=$sort&way=$way",$page); ?>

<?php } ?>


<?php if ($action=="add" || $action=="edit") { ?>
<h1><?php echo $title; ?></h1>
<div class="submenu"><a href="<?php echo $back_url; ?>" class="back">&laquo;&nbsp;Back</a></div>
<?php $df->process_form($_GET); ?>
<?php } ?>