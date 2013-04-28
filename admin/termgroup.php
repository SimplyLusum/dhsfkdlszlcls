<?php
if (empty($_REQUEST['sort'])) {
    $sort = 0;
} else {
    $sort = $_REQUEST['sort'];
}
if (empty($_REQUEST['way'])) {
    $way = "up";
} else {
    $way = $_REQUEST['way'];
}
if (empty($_REQUEST['page'])) {
    $page = 1;
} else {
    $page = $_REQUEST['page'];
}

$set_url = "sort=$sort&way=$way&page=$page";
$title = "Terms & Conditions Groups Manager";

$table = "tbl_termgroup_grp";
$dir = "../upload/grp_images/";


switch ($action) {
    case "view":
        $title.=" - <span>List</span>";

        $df = new Dataface($table);

        $df->set_field(array("Group Name", "grp_name", 500));
        $df->set_icon(array("status", "order", "edit", "delete"));
        $df->set_page_size(30);
        $df->set_order(false, "grp_order", "asc");

        $sql = "select * from tbl_termgroup_grp";
        $url = "?pg=$pg";

        break;
    case "add":
    case "edit":

        $df = new Dataface($table);
        $df->set_dir($dir);

        if ($action == "add") {
            $text = array();
        } else {
            $text = $df->get_record($_REQUEST['id']);
        }

        $title.=" - <span>" . ucwords($action) . "</span>";
        $back_url = "?pg=$pg&" . $set_url;

        $df->set_form("text", "Group Name", "grp_name", $text['grp_name'], "class=\"text-large\"", "");
        $df->set_form("htmleditor", "Group Description", "grp_description", $text['grp_description'], "class=\"textarea-full\" style=\"height:200px\"", "");
        $df->set_form("text", "Side column Title", "grp_title", $text['grp_title'], "class=\"text-large\"", "");
        $df->set_form("htmleditor", "Side column Content", "grp_desc", $text['grp_desc'], "class=\"textarea-full\" style=\"height:200px\"", "");
        $df->set_form("file", "Side column Image", "grp_pic", $text['grp_pic'], "size=\"50\" class=\"file-large\"", "");

        break;

    case "add-action":
    case "edit-action":
        $df = new Dataface($table);
        $df->set_dir($dir);

        foreach ($_POST as $row => $key) {
            $_POST[$row] = stripslashes(stripslashes($key));
        }


        if ($action == "add-action") {
            $df->save("add", $_POST, array());
        } else {
            $df->save("save", $_POST, array());
        }

        header("location: ?pg=$pg&" . $set_url);
        die();

        break;

    case "delete-action":

        $df = new Dataface($table);
        $df->delete($_REQUEST['id']);

        header("location: ?pg=$pg&" . $set_url);
        die();

    case "status-action":

        $df = new Dataface($table);
        $df->status($_REQUEST['id']);

        header("location: ?pg=$pg&" . $set_url);
        die();

    case "order-action":

        $df = new Dataface($table);
        $df->update_order($_REQUEST['id'], $_REQUEST['order'], $_REQUEST['move'], "");

        header("location: ?pg=$pg&" . $set_url);
        die();
        break;
}
?>

<?php if ($action == "view") { ?>
    <h1><?php echo $title; ?></h1>
    <div class="submenu"><a href="<?php echo $url; ?>&id=0&action=add" class="add"><img src="skin/images/icon/new.png" /></a></div>

    <?php $df->process_list($sql, $sort, $way, $page, " cellspacing=\"0\" class=\"cms-table\"", $url); ?>

    <?php $df->page_split("?pg=$pg&sort=$sort&way=$way", $page); ?>

<?php } ?>


<?php if ($action == "add" || $action == "edit") { ?>
    <h1><?php echo $title; ?></h1>
    <div class="submenu"><a href="<?php echo $back_url; ?>" class="back">&laquo;&nbsp;Back</a></div>
    <?php $df->process_form($_GET); ?>
<?php } ?>