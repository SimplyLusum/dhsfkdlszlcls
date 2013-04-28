<?php
	
require_once( 'include/Order/Basket.php' );
$Basket = Order_Basket::getinstance();

// Already assigned in index.php

// $_SESSION['basketid']
    
    /*if ( $Basket->getOrderNumber() && $Basket->getCustomerHash() ) {
        //$Basket->save();
        header("location: checkout"); die();
    }*/


   
    if (empty($_GET['step'])) { $step=1; } else { $step=$_GET['step']; }
	   
	switch ($action) {
		case "del-action":
			$Basket->remove_from_cart($_REQUEST['id']);
			header("location: checkout"); die(); 
			break;
		case "step2-action":
			$Basket->set_city($_POST['city']);
			$Basket->save_info($_POST);
			
			header("location: checkout_3"); die(); 
			break;
		/*case "step2-action":
			$date=$_POST['date'] . " " . $_POST['time'];
			
			$Basket->set_measurement($date);
			header("location: checkout_3"); die(); 
			break;*/
		case "step31-action":
			$Basket->set_measurement("Yes");
			header("location: checkout_4"); die(); 
			break;

		case "step32-action":
			$Basket->set_measurement("");
			header("location: checkout_4"); die(); 
			break;
		
		case "step3-action":
			
			$Basket->set_assembly($_POST['assembly']);
			
			if ($_POST['install']==1) {
				header("location: checkout_31.php"); die();
			} else { 
				header("location: checkout_32.php"); die();
			}
			break;
	
			
		case "step4-action":
			
			switch ($_POST['assembly']) {
				case "0":
					$Basket->set_assembly(0);
					$Basket->set_install("");
					break;
				case "1":
					$Basket->set_assembly(1);
					$Basket->set_install("");
					break;
				case "2":
					$Basket->set_assembly(1);
					$Basket->set_install("yes");
					break;
			}
			
			
			header("location: checkout_5"); die(); 
			
			break;
			
		case "step5-action":
		
			if ($_POST['shipping']==0) { 
				$Basket->set_shipping(0);
			} else {
				$Basket->set_shipping(1);
			}
			
			header("location: checkout_6"); die(); 
		
			break;
			
		case "step6-action":
			include ("class/emailform.class.php"); 
			switch ($_POST['card']) {
				case "paypal": die(); break;
				case "bank":
					$_SESSION['transid']=$Basket->save_transaction("bank");
					
					if ($_SESSION['transid']!=0) {
						$info=$Basket->get_info();	
						$tmp=array();
						$tmp['name']=$info['name']; $tmp['reference']=$_SESSION['transid'];
						
						$sendto= Setting::get_value("email_order");
						
						$email=new EmailForm(9);
						$email->generate_email($tmp);
						$email->send($info['name'],$info['email']);
						$email->send("Admin",$sendto);
					}
					
					header("location: success"); die();
					break;
				case "creditcard":
					$_SESSION['transid']=$Basket->save_transaction("creditcard");
					header("location: payment.php"); die();
				
				
			} 
		
			 
			
			break;			
	
	}
	
	/* add test item to cart - test only */
	//$Basket->add_to_cart("Your Kitchen","Molestie molestie iusto",28800);
	
	switch ($step) {
		case 1: 
			
			$list=$Basket->get_cart_list();
                    
			break;
		case 2: 
			$info=$Basket->get_info();
			$city=$Basket->get_shipping_list();		
			break;
		
		case 21:
			$text=$Basket->get_info();
			break;
		
			
		case 3: break;
		
		case 4:
			$text=$Basket->get_info();
			
			if ($text['assembly']==0 && $text['install']==0) { $check=1; }
			
			if ($text['assembly']!=0 && $text['install']==0) { $check=2; }
			if ($text['install']!=0) { $check=3; }
			
			break;	
		case 5:
		
			$text=$Basket->get_info();
			if ($text['delivery']==0) { $check=1; } else { $check=2; }
			
		case 6: 
			$summary=$Basket->get_summary();
			break;	
	
	} // end switch

?>
<? if ($step==1) { ?>

<div id="checkout" class="general">
	<div id="progress" class="step1"></div>
	<div id="frame">
		<div id="cart">
        <div class="title">Your Kitchen<span>Price indicated below is as a flatpack</span></div>
        	<table>
				<? $x=1; foreach ($list as $row) { ?>
				<tr>
                	<td></td>
					<td class="col1"><img src="images/camera.gif" /></td>
					<td class="col2"><? echo $row['title']; ?></td>
					<td class="col3">$<? echo number_format($row['price'],2); ?></td>
				</tr>
				<? } ?>
			</table>
            
        </div>
        <div id="sum">
            <p class="left">Current Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$</b>&nbsp;&nbsp;<span><? echo number_format($Basket->get_total() + $Basket->get_admin_cost(),2,".",""); ?></span></p>
            <p class="right"><a href="checkout_2"><img src="images/btn-proceed1.gif" /></a></p>
        </div>
    </div><!-- frame -->
<div id="end"></div>


<? } ?>


<? if ($step==2) { ?>

<div id="checkout" class="general">
	<div id="progress" class="step2">
    	<a href="checkout_1" id="back1">Step 1</a>
    </div>
	<div id="frame">
		<div id="form">
		<div class="title">Enter Delivery Details</div>
		<form action="checkout" method="post" onsubmit="return validate(this)">
		<input type="hidden" name="pg" value="checkout" />
		<input type="hidden" name="action" value="step2-action" />
		
		<div class="half1"><label>First Name</label><input type="text" name="name" class="textbox" value="<? echo $info['name']; ?>" /><span>*</span></div>
		<div class="half2"><label>Street Address</label><input type="text" name="address" class="textbox" value="<? echo $info['address']; ?>" /><span>*</span></div>
		<div class="half1"><label>Surname</label><input type="text" name="surname" class="textbox" value="<? echo $info['surname']; ?>" /><span>*</span></div>
		<div class="half2"><label>Suburb</label><input type="text" name="suburb" class="textbox" value="<? echo $info['suburb']; ?>" /><span>*</span></div>
		<div class="half1"><label>Email Address</label><input type="text" name="email" class="textbox" value="<? echo $info['email']; ?>" /><span>*</span></div>
		<div class="half2"><label>City</label>
		<select name="city" class="listbox">
		<? foreach ($city as $row) { ?>
		<option value="<? echo $row['shp_id']; ?>" <? if ( $Basket->get_city() == $row['shp_id'] || $info['city'] == $row['shp_title'] ) { echo "selected"; } ?> /><? echo $row['shp_title']; ?></option>
		<? } ?>
		</select><span>*</span>
		</div>
		<div class="half1"><label>Re-enter Email Address</label><input type="text" name="email2" class="textbox" value="<? echo $info['email']; ?>" /><span>*</span></div>
		<div class="half2"><label>Postcode</label><input type="text" name="post" class="textbox" value="<? echo $info['post']; ?>" /><span>*</span></div>
		<div class="half1"><label>Daytime Phone No</label><input type="text" name="phone" class="textbox" value="<? echo $info['phone']; ?>" /><span>*</span></div>
		<div class="half1"><label>Preferred Phone No</label><input type="text" name="prephone" class="textbox" value="<? echo $info['prephone']; ?>" /><span>*</span></div>
		<div class="half2"><label>Preferred Contact</label><input type="radio" name="precontact" value="8am - 5pm" <? if (empty($info['precontact']) || $info['precontact']=='8am - 5pm') { echo 'checked="checked"'; } ?> />8am - 5pm<input type="radio" name="precontact" value="5pm - 9pm" <? if ($info['precontact']=='5pm - 9pm') { echo 'checked="checked"'; } ?> />5pm - 9pm</div>
		
		<div class="full"><label>Delivery Instructions<br />(optional)</label><textarea name="instruction" class="textarea"><? echo $info['instruction']; ?></textarea></div>
		</div>
        <div id="sum">
            <p class="left">Current Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$</b>&nbsp;&nbsp;<span><? echo number_format($Basket->get_total() + $Basket->get_admin_cost(),2,".",""); ?></span></p>
            <p class="right"><a href="checkout_1"><img src="images/btn-back.gif" /></a>&nbsp;&nbsp;<input type="image" src="images/btn-proceed.gif" /></p>
        </div>
		
		</form>
	</div><!-- frame -->
	<div id="end"></div>
    
    <script type="text/javascript">
	function validate(frm) {
		error='';
		if (frm.name.value=='') { error+="- First Name\n"; }
		if (frm.surname.value=='') { error+="- Surname\n"; }
		if (frm.address.value=='') { error+="- Address\n"; }
		if (frm.suburb.value=='') { error+="- Suburb\n"; }
		if (frm.email.value=='') { error+="- Email Address\n"; }
		if (frm.email2.value=='') { error+="- Re-enter Email Address\n"; }
		if (frm.post.value=='') { error+="- Postcode\n"; }
		if (frm.phone.value=='') { error+="- Daytime Phone No\n"; }
		if (frm.prephone.value=='') { error+="- Preferred Phone No\n"; }
		
		if (error!='') { alert('Please enter following required fields:\n\n' + error); return false; } else { return true; }
	
	}
	</script>

</div><!-- checkout -->



<? } ?>




<? if ($step==3) { // Select measurement required ?>


<div id="checkout" class="general">
	<div id="progress" class="step3">
    	<a href="checkout_1" id="back1">Step 1</a>
    	<a href="checkout_2" id="back2">Step 2</a>
    </div>
	<div id="frame">
		
		
		<div id="questionbox">
			<h2>Would you like Measurement assistance?</h2>
            <div class="text">
            <p>Suptat umolestie molesrerit quistie iusto bolamehend molestie molesrerit quismolestie molesrerit quistie iusto bolaat umolestie 
            molesrerit quistie iusto bat umolestie molesrerit quistie iusto, costing $ <span class="price"><? echo $Basket->get_measurement_cost(); ?></span></p>
            </div>
        
		</div>
        <div id="sum">
            <p class="left">Current Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$</b>&nbsp;&nbsp;<span><? echo number_format($Basket->get_amount(),2,".",""); ?></span></p>
            <p class="right">
            <a href="index.php?pg=checkout&action=step31-action"><img src="images/btn-yes.gif" /></a>
			<a href="index.php?pg=checkout&action=step32-action"><img src="images/btn-proceedwithout.gif" /></a>
            </p>
        </div>        
				
	</div><!-- frame -->
	<div id="end"></div>

</div><!-- checkout -->

<? } ?>

<? if ($step==4) { // Yes - assembly type ?>

<div id="checkout" class="general">
	<div id="progress" class="step4">
    	<a href="checkout_1" id="back1">Step 1</a>
    	<a href="checkout_2" id="back2">Step 2</a>
    	<a href="checkout_3" id="back3">Step 3</a>
    </div>
	<div id="frame">
		<form action="/" method="post">
		<input type="hidden" name="pg" value="checkout" />
		<input type="hidden" name="action" value="step4-action" />
		
		<div id="questionbox">
			<h2>Please select your assembly type</h2>
            <div class="text">
            	<p>Suptat umolestie molesrerit quistie iusto bolamehend molestie molesrerit quismolestie molesrerit quistie iusto bolaat umolestie molesrerit quistie iusto bat umolestie molesrerit quistie iusto.</p>
        	</div>
            
            <div class="assembly">
          		<div class="option1">
                	<img src="images/img-flatpack.jpg" />
                    <div class="type <? if ($check==1) { echo 'selected'; } ?>" id="shipping1">
                    	
                    	<p class="name"><label><input type="radio" name="assembly" value="0" <? if ($check==1) { echo 'checked="checked"'; } ?> onclick="update_assembly(1)" />&nbsp;&nbsp;Flatpack</label></p>
                    	<p class="price">$0</p>
                    </div>
                </div>
          		<div class="option2">
                	 <img src="images/img-assembly.jpg" />
                    <div class="type <? if ($check==2) { echo 'selected'; } ?>" id="shipping2">
                    	<p class="name"><label><input type="radio" name="assembly" value="1" <? if ($check==2) { echo 'checked="checked"'; } ?> onclick="update_assembly(2)" />&nbsp;&nbsp;Assembled</label></p>
                    	<p class="price">$<? echo number_format($Basket->get_assembly_cost(),0); ?></p>
		             </div>
				   
                </div>
          		<div class="option3">
                	 <img src="images/img-both.jpg" />
                    <div class="type <? if ($check==3) { echo 'selected'; } ?>" id="shipping3">
                    	<p class="name"><label><input type="radio" name="assembly" value="2" <? if ($check==3) { echo 'checked="checked"'; } ?> onclick="update_assembly(3)" />&nbsp;&nbsp;Installed</label></p>
                    	<p class="price">$<? echo number_format($Basket->get_assembly_cost() + $Basket->get_install_cost(),0); ?></p>
		             </div>
				  
                </div>

          	</div>            
            
		</div>
		<div id="sum">
            <p class="left">Current Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$</b>&nbsp;&nbsp;<span><? echo number_format($Basket->get_amount(),2,".",""); ?></span></p>
            <p class="right">
			<input type="image" src="images/btn-proceed1.gif" />
            </p>
        </div>  
		</form>
	</div><!-- frame -->
	<div id="end"></div>

</div><!-- checkout -->

<script type="text/javascript">
function update_assembly(id) {
	
	if (id==1) {
		$("#shipping1").addClass('selected');	
		$("#shipping2").removeClass('selected');	
		$("#shipping3").removeClass('selected');	
	} 
	
	if (id==2) {
		$("#shipping1").removeClass('selected');	
		$("#shipping2").addClass('selected');	
		$("#shipping3").removeClass('selected');	
	}
	
	if (id==3) {
		$("#shipping1").removeClass('selected');	
		$("#shipping2").removeClass('selected');	
		$("#shipping3").addClass('selected');	
	}	
	
}

</script>


<? } ?>


<? if ($step==5) { ?>


<script type="text/javascript">
function update_shipping(id) {
	
	if (id==1) {
		$("#shipping1").addClass('selected');	
		$("#shipping2").removeClass('selected');	
	} else {
		$("#shipping1").removeClass('selected');	
		$("#shipping2").addClass('selected');	
		
	}
	
}

</script>
<div id="checkout" class="general">
	<div id="progress" class="step5">
    	<a href="checkout_1" id="back1">Step 1</a>
    	<a href="checkout_2" id="back2">Step 2</a>
    	<a href="checkout_3" id="back3">Step 3</a>    
    	<a href="checkout_4" id="back4">Step 4</a>    
    </div>
	<div id="frame">
		<form action="/" method="post">
		<input type="hidden" name="pg" value="checkout" />
		<input type="hidden" name="action" value="step5-action" />
		
		<div id="questionbox">
			<h2>Shipping Details</h2>
            <div class="text">
            	<p>Suptat umolestie molesrerit quistie iusto bolamehend molestie molesrerit quismolestie molesrerit quistie iusto bolaat umolestie molesrerit quistie iusto bat umolestie molesrerit quistie iusto.</p>
        	</div>
            <? if ($Basket->is_assembly()) { ?>
            <div class="shipping">
          		<div class="option1">
                    <div class="type <? if ($check==1) { echo 'selected'; } ?>" id="shipping1">
                    	<p class="name"><label><input type="radio" name="shipping" value="0" <? if ($check==1) { echo 'checked="checked"'; } ?> onclick="update_shipping(1)" />&nbsp;&nbsp;Pickup</label></p>
                    	<p class="price">$0</p>
                    </div>
                </div>
          		<div class="option2">
                    <div class="type <? if ($check==2) { echo 'selected'; } ?>" id="shipping2">
                    	<p class="name"><label><input type="radio" name="shipping" value="1" <? if ($check==2) { echo 'checked="checked"'; } ?> onclick="update_shipping(2)" />&nbsp;&nbsp;Delivered</label></p>
                    	<p class="price">$<? echo number_format($Basket->get_shipping_cost(),0); ?></p>
		             </div>
				   <p class="location"><b>Shipped to</b> Auckland</p>
                </div>
          	</div>   
            <? } else { ?>
            <div class="shipping">
          		<div class="option1">
                    <div class="type disabled" id="shipping1">
                    	<p class="name"><label><input type="radio" name="shipping" value="0" disabled="disabled" />&nbsp;&nbsp;Pickup</label></p>
                    	<p class="price">$0</p>
                    </div>
                </div>
          		<div class="option2">
                    <div class="type selected" id="shipping2">
                    	<p class="name"><label><input type="radio" name="shipping" value="1" checked="checked" />&nbsp;&nbsp;Delivered</label></p>
                    	<p class="price">$<? echo number_format($Basket->get_shipping_cost(),0); ?></p>
		             </div>
				   <p class="location"><b>Shipped to</b> <? echo $Basket->get_city(); ?></p>
                </div>
           </div> 
           <? } ?>                     
            
		</div>
		<div id="form-end">
        	<div id="current"><label>Current Total</label><span>$</span><? echo number_format($Basket->get_amount(),2); ?></div>
			<input type="image" src="images/btn-proceed1.gif" />
		</div>
		</form>
	</div><!-- frame -->
	<div id="end"></div>

</div><!-- checkout -->

	
	
	
<? } ?>
	


<? if ($step==6) { // Yes - measurement required?>

<div id="checkout" class="general">
	<div id="progress" class="step6">
    	<a href="checkout_1" id="back1">Step 1</a>
    	<a href="checkout_2" id="back2">Step 2</a>
    	<a href="checkout_3" id="back3">Step 3</a>    
    	<a href="checkout_4" id="back4">Step 4</a>    
    	<a href="checkout_5" id="back5">Step 5</a>    
    </div>
	<div id="frame">
		
	  <div id="questionbox">
        	
			<h2>Please check your order</h2>
            <div class="text" style="margin:0;">
            	<p>Suptat umolestie molesrerit quistie iusto bolamehend molestie molesrerit quismolestie molesrerit quistie iusto bolaat umolestie molesrerit quistie iusto bat umolestie molesrerit quistie iusto.</p>
        	</div>
      </div>      
     
            <div id="required" class="summary">
				<table>
                	<tr>
	                    <td class="item">Your Kitchen<span>Molestie molestie iusto, hendrerit quis luptatum.</span></td>
                        <td class="link"><a href="checkout_1">Overview</a></td>
    	                <td class="price">$<? echo number_format($summary['subtotal'],2); ?></td>
    	                <td class="function"></td>
                    </tr>
                	<tr id="req1" <? if (!$Basket->is_measurement()) { echo 'style="display:none"'; } ?>>
	                    <td class="item">Measurement assistance<span>Including transportation and work completion</span></td>
                        <td class="link"><a href="checkout_3">Measure</a></td>
    	                <td class="price" id="req1price">$<? echo number_format($Basket->get_measurement_cost(),2); ?></td>
    	                <td class="function"><a href="javascript:remove_element('measure')" class="remove">remove</a></td>
                    </tr>
                	<tr id="req2" <? if (!$Basket->is_assembly()) { echo 'style="display:none"'; } ?>>
	                    <td class="item">Assembly<span>Molestie molestie iusto, hendrerit quis luptatum.</span></td>
    	                <td class="link"><a href="checkout_4">Assembly</a></td>
                        <td class="price" id="req2price">$<? echo number_format($Basket->get_assembly_cost(),2); ?></td>
                        <td class="function"><a href="javascript:remove_element('assembly')" class="remove">remove</a></td>
                    </tr>
                    <tr id="req3" <? if (!$Basket->is_shipping()) { echo 'style="display:none"'; } ?>>
	                    <td class="item">Shipping<span>Shipping to Auckland with shipping company</span></td>
    	                <td class="link"><a href="checkout_5">Shipping</a></td>
                        <td class="price" id="req3price">$<? echo number_format($Basket->get_shipping_cost(),2); ?></td>
                        <td class="function"><a href="javascript:remove_element('shipping')" class="remove">remove</a></td>
                    </tr>
                	<tr id="req4" <? if (!$Basket->is_install() || !$Basket->is_assembly()) { echo 'style="display:none"'; } ?>>
	                    <td class="item">Installation<span>Molestie molestie iusto, hendrerit quis luptatum.</span></td>
                        <td class="link"><a href="checkout_3">Installation</a></td>
    	                <td class="price" id="req4price">$<? echo number_format($Basket->get_installation_cost(),2); ?></td>
                        <td class="function"><a href="javascript:remove_element('installation')" class="remove">remove</a></td>
                    </tr>
                </table>
			</div> 

          <div id="questionbox">
                <h2 style="margin:0;">Not Required</h2>
          </div>                    
             <div id="notrequired" class="summary">  
             	<table>  
                	<tr id="nreq1" <? if ($Basket->is_measurement()) { echo 'style="display:none"'; } ?>>
	                    <td class="item">Measurement assistance<span>Including transportation and work completion</span></td>
                        <td class="link"><a href="checkout_3">Measure</a></td>
    	                <td class="price" id="nreq1price">$<? echo number_format($Basket->get_measurement_cost(),2); ?></td>
    	                <td class="function"><a href="javascript:add_element('measure')" class="add">add</a></td>
                    </tr>
                	<tr id="nreq2" <? if ($Basket->is_assembly()) { echo 'style="display:none"'; } ?>>
	                    <td class="item">Assembly<span>Molestie molestie iusto, hendrerit quis luptatum.</span></td>
    	                <td class="link"><a href="checkout_4">Assembly</a></td>
                        <td class="price" id="nreq2price">$<? echo number_format($Basket->get_assembly_cost(),2); ?></td>
                        <td class="function"><a href="javascript:add_element('assembly')" class="add">add</a></td>
                    </tr>
                    <tr id="nreq3" <? if ($Basket->is_shipping()) { echo 'style="display:none"'; } ?>>
	                    <td class="item">Shipping<span>Shipping to Auckland with shipping company</span></td>
    	                <td class="link"><a href="checkout_5">Shipping</a></td>
                        <td class="price" id="nreq3price">$<? echo number_format($Basket->get_shipping_cost(),2); ?></td>
                        <td class="function"><a href="javascript:add_element('shipping')" class="add">add</a></td>
                    </tr>
                	<tr id="nreq4" <? if ($Basket->is_install() || $Basket->is_assembly()) { echo 'style="display:none"'; } ?>>
	                    <td class="item">Installation<span>Molestie molestie iusto, hendrerit quis luptatum.</span></td>
                        <td class="link"><a href="checkout_3">Installation</a></td>
    	                <td class="price" id="nreq4price">$<? echo number_format($Basket->get_installation_cost(),2); ?></td>
                        <td class="function"><a href="javascript:add_element('installation')" class="add">add</a></td>
                    </tr>
                </table>
               
            </div>     
            
		<div id="sum1">
            <p class="left">Please select payment method</p>
            <p class="right">Current Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$</b>&nbsp;&nbsp;<span id="total"><? echo number_format($Basket->get_amount(),2,".",""); ?></span></p>
        </div>             

		<div id="sum" style="background:none;">
        <form action="/" method="post">
		<input type="hidden" name="pg" value="checkout" />
		<input type="hidden" name="action" value="step6-action" />
            <div class="left" style="width:500px;">
            	<table>
                	<tr>
                		<td><input type="radio" name="card" value="bank" checked="checked" />&nbsp;<img src="images/card-bank.jpg" /></td>
                		<? /* <td><input type="radio" name="card" value="paypal" />&nbsp;<img src="images/card-paypal.jpg" /></td> */ ?>
                		<td><input type="radio" name="card" value="creditcard" />&nbsp;<img src="images/card-credit.jpg" /></td>
                    </tr>
                </table>
            </div>
            <p class="right"><input type="image" src="images/btn-placeorder.gif"></span></p>
        </form>
        </div>             

     
		
	</div><!-- frame -->
	<div id="end"></div>
    
    <script type="text/javascript">
	function remove_element(type) {
		
		var dataString="pg=remove_emement&type=" + type;
		
		$.ajax({  
		   type: "GET",  
		   url: "index.php",  
		   data: dataString,  
		   success: function(msg) {  
		   		
		   		result = msg.split("|");
				
				switch (result[0]) {
					case "1": //measure
						$("#req1").hide();	
						$("#nreq1").show();
						
						break;	
					case "2": //assembly
						$("#req2").hide();	
						$("#nreq2").show();
						break;	
					case "3": //shipping
						$("#req3").hide();	
						$("#nreq3").show();
						break;
					case "4": //install
						$("#req2").hide();	
						$("#nreq2").show();					
						$("#req4").hide();	
						$("#nreq4").show();
						break;								

				}
				
				$("#total").html(result[1]);
		   }  
		});
		
	}
	
	
	function add_element(type) {
		
		var dataString="pg=add_element&type=" + type;
		
		$.ajax({  
		   type: "GET",  
		   url: "index.php",  
		   data: dataString,  
		   success: function(msg) {  
		   	
		   		result = msg.split("|");
				
				switch (result[0]) {
					case "1": //measure
						$("#req1").show();	
						$("#nreq1").hide();
						break;	
					case "2": //assembly
						$("#req2").show();	
						$("#nreq2").hide();
						break;	
					case "3": //shipping
						$("#req3").show();	
						$("#nreq3").hide();
						break;
					case "4": //install
						$("#req2").show();	
						$("#nreq2").hide();					
						$("#req4").show();	
						$("#nreq4").hide();
						break;								

				}
				
				$("#total").html(result[1]);
		   }  
		});
		
	}	
	
	
	
	</script>

</div><!-- checkout -->


<? } ?>


