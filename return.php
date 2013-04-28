<? include ("class/emailform.class.php"); ?>
<? $page=new Page($pgid); ?>
<? if (!empty($_POST)) { 
            $email=new EmailForm(11);
            $email->generate_email($_POST);
            $email->send("Administrator",Setting::get_value("email_contact"));
            $page2=new Page('22');
    }
?>
<div id="contact" class="general">
	<h1><? echo $page->get_title(); ?></h1>
	<div class="left">
		<? echo $page->get_content1(); ?>
		
		<? if (!empty($_POST)): ?>
		<div class="message">Thank you, your message has been sent. We will contact you as soon as possible.</div>
                <? echo $page2->get_content1(); ?>
		<? else: ?>
		<form action="" method="post" class="form" id="form-return">
                    
                        <label>First Name:</label><input type="text" name="first_name" class="textbox required" />
                        <label>Last Name:</label><input type="text" name="last_name" class="textbox required" />
                        <label>Online Kitchens Order Number:</label><input type="text" name="online_kitchens_order_number" class="textbox required" />
                        <label>Street Address:</label><input type="text" name="street_address" class="textbox required" />
                        <label>City:</label><input type="text" name="city" class="textbox required" />
                        <label>State/Province:</label><input type="text" name="state_province" class="textbox required" />
                        <label>ZIP/Postal Code:</label><input type="text" name="zip_postal_code" class="textbox required" />
                        <label>Country:</label><input type="text" name="country" class="textbox required" value="New Zealand" />
                        <label>Email Address:</label><input type="text" name="email" class="textbox required email" />
                        <label>Daytime Phone Number:</label><input type="text" name="daytime_phone_number" class="textbox required" />
                        <label>Products(s) You Are Returning</label><input type="text" name="products_you_are_returning" class="textbox required" />
                        <label>Reason For Return:</label><select name="reason_for_return" class="select required">
                            <option value="-">Please select one</option>
                            <option value="Defective/damaged product">Defective/damaged product</option>
                            <!--option value="Didn't meet expectations/needs">Didn't meet expectations/needs</option-->
                            <option value="Duplicate shipment">Duplicate shipment</option>
                            <!--option value="Changed my mind">Changed my mind</option-->
                            <option value="Wrong item shipped">Wrong item shipped</option>
                            <!--option value="Technical problems with product">Technical problems with product</option-->
                            <option value="Other">Other</option>
                            
                        </select>
			<label>Comments:</label><textarea name="comments" class="textarea required"></textarea>
			<input type="image" src="images/btn-submit.gif" class="button" />
		</form>
                <?php endif; ?>
		
	</div>
	<div class="right"><img src="<? echo $page->get_pic(); ?>" /></div>

</div><!-- howitwork -->
<script type="text/javascript">
    $(function(){
        $("#form-return").validate();
    });
</script>