<?php
include "include/include.php";

if (empty($_REQUEST['pg'])) { $pg="home"; } else { $pg=$_REQUEST['pg']; }
if (empty($_REQUEST['action'])) { $action=""; } else { $action=$_REQUEST['action']; }

parseUri();

switch ($pg) {
	case "admin":               header("location: /admin/index.php"); die();                 break;
	case "home":                $inc_body="home.php";            $section=1;                 break;
	case "howitworks":          $inc_body="howitworks.php";      $section=2;    $pgid=1;     break;
	case "faq":                 $inc_body="faq.php";             $section=4;    $pgid=2;     break;
	case "measurementtips":     $inc_body="measurementtips.php"; $section=5;    $pgid=3;     break;
	case "colorideas":          $inc_body="colorideas.php";      $section=5;    $pgid=17;    break;
	case "about":               $inc_body="about.php";           $section=6;    $pgid=4;     break;
        case "community":           $inc_body="about.php";           $section=6;    $pgid=23;    break;
        case "sitemap":             $inc_body="about.php";           $section=6;    $pgid=20;    break;
	case "terms":               $inc_body="terms.php";           $section=0;    $pgid=13;    break;
	case "contact":             $inc_body="contact.php";         $section=6;    $pgid=5;     break;
        case "payment":             $inc_body="payment_template.php";$section=5;    $pgid=24;    break;
        case "return-request":      $inc_body="return.php";          $section=6;    $pgid=21;     break;
	case "design":              $inc_body="sendus.php";          $section=1;    $pgid=6;     break;
	case "consultant":          $inc_body="sendus.php";          $section=1;    $pgid=7;     break;
	case "video":               $inc_body="video.php";           $section=5;    $pgid=8;     break;
	case "gallery":             $inc_body="gallery.php";         $section=3;    $pgid=12;    break;
	case "testimonial":         $inc_body="testimonial.php";     $section=3;    $pgid=14;    break;
	case "template":            $inc_body="template.php";        $section=5;    $pgid=9;     break;
	case "printout":            $inc_body="printout.php";        $section=5;    $pgid=10;    break;
	case "register":            $inc_body="register.php";        $section=0;    $pgid=11;    break;
	case "password":            $inc_body="password.php";        $section=0;    $pgid=18;    break;
	case "checkout":            $inc_body="checkout.php";        $section=0;    $pgid=11;    break;
	case "success":             $inc_body="success.php";         $section=0;    $pgid=15;    break;
	case "fail":                $inc_body="fail.php";            $section=0;    $pgid=16;    break;
	case "installationpackage": $inc_body="about.php";           $section=0;    $pgid=19;    break;
	case "remove_emement":
		require_once( 'include/Order/Basket.php' );
		switch ($_REQUEST['type']) {
			case "measure":
				$Basket = Order_Basket::getinstance();
				$Basket->set_measurement("");
				echo "1|" . number_format($Basket->get_amount(),2,".","");
				break;

			case "assembly":
				$Basket = Order_Basket::getinstance();
				$Basket->set_assembly(0);
				echo "2|" . number_format($Basket->get_amount(),2,".","");
				break;

			case "shipping":
				$Basket = Order_Basket::getinstance();
				$Basket->set_shipping(0);
				echo "3|" . number_format($Basket->get_amount(),2,".","");
				break;

			case "installation":
				$Basket = Order_Basket::getinstance();
				$Basket->set_install("");
				$Basket->set_assembly(0);
				echo "4|" . number_format($Basket->get_amount(),2,".","");
				break;

		}

		die();
		break;

	case "add_element":

		require_once( 'include/Order/Basket.php' );

		switch ($_REQUEST['type']) {
			case "measure":
				$Basket = Order_Basket::getinstance();
				$Basket->set_measurement("Yes");
				echo "1|" . number_format($Basket->get_amount(),2,".","");
				break;

			case "assembly":
				$Basket = Order_Basket::getinstance();
				$Basket->set_assembly(1);
				echo "2|" . number_format($Basket->get_amount(),2,".","");
				break;

			case "shipping":
				$Basket = Order_Basket::getinstance();
				$Basket->set_shipping(1);
				echo "3|" . number_format($Basket->get_amount(),2,".","");
				break;

			case "installation":
				$Basket = Order_Basket::getinstance();
				$Basket->set_install("Yes");
				$Basket->set_assembly(1);
				echo "4|" . number_format($Basket->get_amount(),2,".","");
				break;

		}

		die();
		break;


	//case "save": $_SESSION['basketid']=$_REQUEST['basket']; header("location: checkout"); die(); break;
} // end switch

    if ( $pg == 'checkout' ) {
        require_once( 'include/Order/Imosnet.php' );
        require_once( 'include/Order/Basket.php' );
        $Basket = Order_Basket::getinstance();

        // Instantiate IMOS new class
        // This will try to automatically parse posted XML data
        $ImosNet = new Order_Imosnet();

        // Valid XML data received, insert basket and return ID
        if($ImosNet->isXMLPosted()) {

            $data = $ImosNet->getData();

            if ( $data->products ) {
                $Basket->name = $data->name;
                $Basket->company = $data->company;
                $Basket->surname = $data->surname;
                $Basket->address = $data->address;
                $Basket->post = $data->post;
                $Basket->city = $data->city;
                $Basket->customernumber = $data->customernumber;
                $Basket->setOrderNumber( $data->ordernumber );

                foreach ( $data->products as $v ) {
                    $Basket->add_to_cart( $v['name'], $v['desc'], $v['price'], $v['dimension'], $v['volumn'],$v['pstring'],$v['assembly'] );
                }

                $basket_id = $Basket->save();
                                //mail("danielworks@gmail.com","test","test");
            }
            echo $basket_id;
            die();
        }
        
        // If the current basket is not set in the session, we need to assign it
        if(isset($_GET['order'])) {
            $_SESSION['basketid'] = $Basket->getOrderNumber($_POST['order']);
        }

	/*
        if ( !$ImosNet->isPosted($_POST['order']) ) {


            var_dump($_POST['order']);
            die();

            $data = $ImosNet->getData();

            if ( $data->products ) {
                $Basket->name = $data->name;
                $Basket->company = $data->company;
                $Basket->surname = $data->surname;
                $Basket->address = $data->address;
                $Basket->post = $data->post;
                $Basket->city = $data->city;
                $Basket->customernumber = $data->customernumber;
                $Basket->setOrderNumber( $data->ordernumber );

                foreach ( $data->products as $v ) {
                    $Basket->add_to_cart( $v['name'], $v['desc'], $v['price'], $v['dimension'], $v['volumn'],$v['pstring'],$v['assembly'] );
                }

                $Basket->save();
				//mail("danielworks@gmail.com","test","test");

            }
        } else {

                //print_r($_POST);
                $_SESSION['basketid']=$Basket->getOrderNumber($_POST['order']);
                //print_r($_SESSION); die();
                header("location: checkout"); die();
        }*/
    }

	if (strpos($action,"-")!=false) {
		$temp=explode("-",$action);

		if ($temp[1]=="action") { include($inc_body); $inc_file="";}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo Setting::get_value("website_title"); ?></title>


<link rel="stylesheet" type="text/css" href="reset.css"/>
<link rel="stylesheet" type="text/css" href="common.css"/>
<link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.1.css" media="screen"/>
<link href="js/ui.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="js/jquery.timeentry.css" rel="stylesheet" type="text/css" media="screen"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<?php if($pg=='colorideas') { ?>
<link rel="stylesheet" type="text/css" href="js/jquery.jcarousel.css" media="screen"/>
<?php } ?>
<?php if ($pg=="home") { ?>
<link rel="stylesheet" type="text/css" href="home.css" media="screen"/>
<?php } else { ?>
<link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
<?php } ?>
<?php if ($pg=="howitworks") { ?>
<script src="http://cdn.jquerytools.org/1.2.5/full/jquery.tools.min.js"></script>
<script src="/js/jquery.print-preview.js"></script>
<link rel="stylesheet" type="text/css" href="print.css" media="print"/>
<?php } ?>
<?php /*<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>*/?>
<script language="javascript" src="js/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
<script language="javascript" src="js/jscript.js"></script>
<?php if($pg=='colorideas') { ?>
<script language="javascript" src="js/jquery.jcarousel.pack.js"></script>
<script language="javascript" src="js/colorideas-1.1.js"></script>
<?php } ?>
<script language="javascript" src="js/jquery-ui.js"></script>
<script language="javascript" src="js/jquery.timeentry.min.js"></script>
<?php /*
    <?php if($pg=='return-request'): ?>
<script language="javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.8.1/jquery.validate.min.js"></script>
<?php endif; ?>*/?>

<?php if($pg == "home"):?>
<!-- jQuery library (served from Google) -->

<!-- bxSlider Javascript file -->
<script src="/js/jquery.bxslider.min.js"></script>
<!-- bxSlider CSS file -->
<link href="/js/jquery.bxslider.css" rel="stylesheet" />
<?php endif; ?>

</head>
<body>
<div id="wrapper">
    <div id="header"> <a href="index.php" id="logo"><img src="./images/logo.gif" alt="onlinekitchens.co.nz" /> Online Kitchens.co.nz</a>
        <ul id="jsddm">
            <li><a href="home" id="nav1" <?php if ($section==1) { echo "class=\"selected\""; } ?>>Home</a></li>
            <li><a href="howitworks" id="nav2" <?php if ($section==2) { echo "class=\"selected\""; } ?>>How it works</a></li>
            <li><a href="#" id="nav3" <?php if ($section==3) { echo "class=\"selected\""; } ?>>Our Clients</a>
                <ul style="left:153px; top:30px;background:url(images/menu2-bg.gif) no-repeat center top; width:115px; height:58px;">
                    <li><a href="testimonial"><img src="images/arrow-02.gif" />Testimonials</a></li>
                    <li><a href="gallery" style="border:none;"><img src="images/arrow-02.gif" />Gallery</a></li>
                </ul>
            </li>
            <li><a href="faq" id="nav4" <?php if ($section==4) { echo "class=\"selected\""; } ?>>Faqs</a></li>
            <li><a href="#" id="nav5" <?php if ($section==5) { echo "class=\"selected\""; } ?>>Need Help</a>
                <ul style="left:296px; top:30px;background:url(images/menu4-bg.gif) no-repeat center top; width:129px; height:188px;">
                    <li><a href="measurementtips"><img src="images/arrow-02.gif" />Measurement Tips</a></li>
                    <li><a href="colorideas"><img src="images/arrow-02.gif" />Colour Ideas</a></li>
                    <li><a href="video"><img src="images/arrow-02.gif" />Help Videos</a></li>
                    <li><a href="printout"><img src="images/arrow-02.gif" />PDF Printouts</a></li>
                    <li><a href="template"><img src="images/arrow-02.gif" />Templates</a></li>
                    <li><a href="payment" style="border:none;"><img src="images/arrow-02.gif" />Payment</a></li>
                </ul>
            </li>
            <li><a href="#" id="nav6" <?php if ($section==6) { echo "class=\"selected\""; } ?>>Contact ius</a>
                <ul style="left:425px; top:30px;background:url(images/menu5-bg.gif) no-repeat center top; width:105px; height:98px;">
                    <li><a href="about"><img src="images/arrow-02.gif" />About Us</a></li>
                    <li><a href="contact"><img src="images/arrow-02.gif" />Contact Us</a></li>
                    <li><a href="community" style="border:none;"><img src="images/arrow-02.gif" />Community</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- end header -->
    <?php @include ($inc_body); ?>
    <div id="footer">
        <p class="left">Online Kitchens Ltd &copy; Copyright <?php echo date('Y') ?>. all rights reserved. &nbsp;|&nbsp; <a href="/about">about us</a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="/terms">terms &amp; conditions</a>&nbsp;&nbsp;|&nbsp;&nbsp; <a href="/sitemap">sitemap</a></p>
        <p class="right"><a href="http://www.brownpaperbag.co.nz" target="_blank">{ site design brownpaperbag }</a></p>
    </div>
    <!-- footer -->
</div>
<!-- wrapper -->
</body>
</html>
<?php
ob_end_flush();
?>