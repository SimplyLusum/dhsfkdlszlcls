<? 
	
	
	if (empty($_REQUEST['sort'])) { $sort=0; } else { $sort=$_REQUEST['sort']; }
	if (empty($_REQUEST['way'])) { $way="up"; } else { $way=$_REQUEST['way']; }
	if (empty($_REQUEST['page'])) { $page=1; } else { $page=$_REQUEST['page']; }
	
	$set_url="sort=$sort&way=$way&page=$page";
	$title="Shipping Manager";
	
	$table="tbl_shipping_shp";
	
	
	switch ($action) {
		case "view":
			$title.=" - <span>List</span>";
		
			$df=new Dataface($table);
			
		
			$df->set_field(array("Shipping Title","shp_title",200));
			$df->set_field(array("Shipping Cost","shp_price1",100));
			$df->set_field(array("Installation Cost","shp_price2",100));
			$df->set_field(array("Measurement Cost","shp_price3",100));
			$df->set_icon(array("status","order","edit","delete"));
			$df->set_page_size(30);
			$df->set_order(false,"shp_order","asc");
	
			$sql="select * from tbl_shipping_shp";
			$url="?pg=$pg";
			
			break;
		case "add":	
		case "edit":		
		
			$df=new Dataface($table);
			$df->set_dir($dir);
			
			if ($action=="add") { $text=array(); } else { $text=$df->get_record($_REQUEST['id']); }
			
			
			$title.=" - <span>" . ucwords($action) . "</span>";
			$back_url="?pg=$pg&" . $set_url;
			
			$df->set_form("text","Shipping Title (City)","shp_title",$text['shp_title'],"class=\"text-large\"","");
			$df->set_form("text","Shipping Price","shp_price1",$text['shp_price1'],"class=\"text-large\"","");
			$df->set_form("text","Install Price","shp_price2",$text['shp_price2'],"class=\"text-large\"","");
			$df->set_form("text","Measurement Price","shp_price3",$text['shp_price3'],"class=\"text-large\"","");

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

<? if ($action=="view") { ?>
<h1><? echo $title; ?></h1>
<div class="submenu"><a href="<? echo $url; ?>&id=0&action=add" class="add"><img src="skin/images/icon/new.png" /></a></div>

<? $df->process_list($sql,$sort,$way, $page, " cellspacing=\"0\" class=\"cms-table\"", $url); ?>

<? $df->page_split("?pg=$pg&sort=$sort&way=$way",$page); ?>

<? } ?>


<? if ($action=="add" || $action=="edit") { ?>
<h1><? echo $title; ?></h1>
<div class="submenu"><a href="<? echo $back_url; ?>" class="back">&laquo;&nbsp;Back</a></div>
<? $df->process_form($_GET); ?>
<? } ?>