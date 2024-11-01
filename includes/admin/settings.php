<?php

function swifno_shipping_settings_page(){
	$swifno_opts  = get_option('swifno_opts');

	if ( $_GET['status'] == 1 ) {
	  	echo '<div class="notice notice-success is-dismissible">
            <p>Merchant details sucessfully updated</p>
        </div>';
  	}

  	if ( $_GET['status'] != 1 ) {
  		echo '<div class="notice notice-danger is-dismissible">
            <p>Merchant details failed to update.</p>
        </div>';
  	}

	if ( !class_exists( 'WooCommerce' )  ) {
	  	echo '<div class="notice notice-warning is-dismissible">
            <p>You need WooCommerce to Initiate Swifno Deliveries.</p>
        </div>';
  	}

	if ( class_exists( 'WooCommerce' ) && $swifno_opts['swifno_key'] == NULL ) {
	  	echo '<div class="notice notice-warning is-dismissible">
            <p>You can now register your merchant key </p>
        </div>';
  	}

  	// if ( class_exists( 'WooCommerce' ) && $swifno_opts['swifno_key'] != NULL || $swifno_opts['swifno_package_category'] == NULL || $swifno_opts['swifno_package_size'] == NULL || $swifno_opts['swifno_pickup_location'] == NULL || $swifno_opts['swifno_pickup_state'] == NULL || $swifno_opts['swifno_pickup_from_time'] == NULL || $swifno_opts['swifno_pickup_to_time'] == NULL ) {
	  // 	echo '<div class="notice notice-warning is-dismissible">
   //          <p>You can now set your <b> Delivery Category, Size, Location, State, Time </b>.</p>
   //      </div>';
  	// }


	?>

	<div class="swifno-wrapper" style="margin-top: 50px">
		<button class="accordion"><?php echo swifno_shipping_check_woocommerce(); ?>Install & Activate WooCommerce</button>
		<div class="panel">
			<p><?php echo wooCommerceGuide(); ?></p>
		</div>
		<button class="accordion"><?php echo swifno_shipping_validate_merchant() ?></span>Validate Merchant</button>
		<div class="panel">
			<form action="admin-post.php" method="POST">
				<input type="hidden" name="action" value="swifno_shipping_save_merchant">
				<?php wp_nonce_field('swifno_shipping_merchant_verify') ?>
				<div class="container-row">
					<h1>Validate Merchant Key</h1>
					<hr>
					<label for="email"><b>Test Mode</b></label><br>	
					<select class="select-form" name="swifno_mode" required>
						<option value="" >Select Delivery Mode</option>
						<option value="test" <?php echo $swifno_opts['swifno_mode'] ? 'selected'  : ''?>>Test</option>
						<option value="live" <?php echo $swifno_opts['swifno_mode'] ? 'selected'  : ''?>>Live</option>
					</select><br>	

					<label for="email"><b>Merchant Key</b></label>
					<input 
						type="text" 
						placeholder="Enter Swifno Merchant Key" 
						name="swifno_key" 
						value="<?php echo $swifno_opts['swifno_key'] ? $swifno_opts['swifno_key']  : ''?>"  
						required/>

					<hr>
					
					<label for="email"><b>Secret Key</b></label>
					<input 
						type="text" 
						placeholder="Enter Swifno Secret Key" 
						name="swifno_secret_key" 
						value="<?php echo $swifno_opts['swifno_secret_key'] ? $swifno_opts['swifno_secret_key']  : ''?>"  
						required/>

					<hr>

					<button type="submit" class="registerbtn">Validate Key</button>
				</div>

				<div class="container-row signin">
					<p>Do not have a Swifno Account? <a href="https://www.swifno.com" target="_blank">Sign in</a></p>
				</div>
			</form>
		</div>

		<button class="accordion"><?php echo swifno_shipping_delivery_settings() ?></span>Delivery Settings</button>
		<div class="panel">
			<?php
				if($swifno_opts['swifno_key'] != NULL){
					?>
					<form action="admin-post.php" method="POST">

					<input type="hidden" name="action" value="swifno_shipping_save_settings">

					<?php wp_nonce_field('swifno_shipping_settings_verify') ?>

					<div class="container">
						<h1>Update Delivery Settings</h1>

						<hr>

						<label for="email"><b>Package Category</b></label><br>	
						<select class="select-form" name="swifno_package_category" id="package_category" required>
							<option value="">Select Package Category</option>
						</select><br>

						<label for="email"><b>Package Size</b></label><br>	
						<select class="select-form" name="swifno_package_size" id="package_size" required>

						</select><br>

						<label for="location"><b>Pickup Address</b></label><br>
						<input type="text" class="form-control"  name="swifno_pickup_location" value="<?php echo $swifno_opts['swifno_pickup_location'] ? $swifno_opts['swifno_pickup_location'] : ''; ?>" style="width:400px"><br>

						<label for="email"><b>Pickup State</b></label><br>

						<select class="select-form" name="swifno_pickup_state"  required >

							<option value="<?php echo $swifno_opts['swifno_full_state']; ?>">
								<?php echo $swifno_opts['swifno_full_state'] ? $swifno_opts['swifno_full_state'] : 'Select Pickup State'; ?>
							</option>

							<option value="NG:AB-Abia, Nigeria">Abia</option>
							<option value="NG:FC-Abuja, Nigeria">Abuja</option>
							<option value="NG:AD-Abuja, Nigeria">Adamawa</option>
							<option value="NG:AK-Akwa Ibom, Nigeria">Akwa Ibom</option>
							<option value="NG:AN-Anambra, Nigeria">Anambra</option>
							<option value="NG:BA-Bauchi, Nigeria">Bauchi</option>
							<option value="NG:BY-Bayelsa, Nigeria">Bayelsa</option>
							<option value="NG:BE-Benue, Nigeria">Benue</option>
							<option value="NG:BO-Borno, Nigeria">Borno</option>
							<option value="NG:CR-Cross River, Nigeria">Cross River</option>
							<option value="NG:DE-Delta, Nigeria">Delta</option>
							<option value="NG:EB-Ebonyi, Nigeria">Ebonyi</option>
							<option value="NG:ED-Edo, Nigeria">Edo</option>
							<option value="NG:EK-Ekiti, Nigeria">Ekiti</option>
							<option value="NG:EN-Enugu, Nigeria">Enugu</option>
							<option value="NG:GO-Gombe, Nigeria">Gombe</option>
							<option value="NG:IM-Imo, Nigeria">Imo</option>
							<option value="NG:JI-Jigawa, Nigeria">Jigawa</option>
							<option value="NG:KD-Kaduna, Nigeria">Kaduna</option>
							<option value="NG:KN-Kaduna, Nigeria">Kano</option>
							<option value="NG:KT-Katsina, Nigeria">Katsina</option>
							<option value="NG:KE-Kebbi, Nigeria">Kebbi</option>
							<option value="NG:KO-Kogi, Nigeria">Kogi</option>
							<option value="NG:KW-Kwara, Nigeria">Kwara</option>
							<option value="NG:LA-Lagos, Nigeria">Lagos</option>
							<option value="NG:NA-Nasarawa, Nigeria">Nasarawa</option>
							<option value="NG:NI-Niger, Nigeria">Niger</option>
							<option value="NG:OG-Ogun, Nigeria">Ogun</option>
							<option value="NG:ON-Ondo, Nigeria">Ondo</option>
							<option value="NG:OS-Osun, Nigeria">Osun</option>
							<option value="NG:OY-Oyo, Nigeria">Oyo</option>
							<option value="NG:PL-Plateau, Nigeria">Plateau</option>
							<option value="NG:RI-Rivers, Nigeria">Rivers</option>
							<option value="NG:SO-Sokoto, Nigeria">Sokoto</option>
							<option value="NG:TA-Taraba, Nigeria">Taraba</option>
							<option value="NG:YO-Yobe, Nigeria">Yobe</option>
							<option value="NG:ZA-Zamfara, Nigeria">Zamfara</option>
						</select><br>

						<label for="Time"><b>Pick From Time</b></label>
						<input type="time" class="form-control"  name="swifno_pickup_from_time" value="<?php echo $swifno_opts['swifno_pickup_from_time'] ? $swifno_opts['swifno_pickup_from_time'] : '7:20'; ?>">


						<label for="Time"><b>Pick To Time</b></label>
						<?php echo $swifno_opts['swifno_pickup_to_time']; ?>
						<input type="time" class="form-control" name="swifno_pickup_to_time" value="<?php echo $swifno_opts['swifno_pickup_to_time'] ? $swifno_opts['swifno_pickup_to_time'] : '21:30'; ?>"><br><br><br><br>

						<label for="key"><b>Insurance</b></label>
	        			<input type="checkbox" name="swifno_insurance" id="swifno_insurance" class="form-control" <?php echo $swifno_opts['swifno_insurance'] ? 'checked' : ''; ?> > <br><br><br><br>
	        			
	        			<div <?php echo $swifno_opts['swifno_itemvalue'] == null ? 'style="display:none" id="itemvalue"' : ''; ?> id="itemvalue" >
	        				<label for="key"><b>Item Has Value ?</b></label>
							<input type="checkbox" name="swifno_itemvalue"  class="form-control" <?php echo $swifno_opts['swifno_itemvalue'] ? 'checked' : ''; ?> >
	        			</div>
						<hr>

						<button type="submit" class="registerbtn">Save Delivery Settings</button>
					</div>
				</form>
					<?php
				}else{
					echo "<h1>Please Validate Merchant</h1>";
				}
			?>
				
		</div>
	</div>

	<?php
		if($swifno_opts['swifno_key'] != NULL){
			?>
		<script>
		
		    var URL = '<?php echo swifno_shipping_state_mode($swifno_opts['swifno_mode']) ?>'; 
		    var swifno_auth_token ='<?php echo $swifno_opts['swifno_auth_token']; ?>';
		    var pickup_state_db ='<?php echo get_option('woocommerce_default_country'); ?>';
		    var pickup_location ='<?php echo get_option('woocommerce_store_address') ?>';
		    
		    
		        
		        jQuery(document).on("change", "#swifno_insurance", function(){
		            if (this.checked) {
		                jQuery("#itemvalue").show();
		                jQuery("#itemvalue").attr("required",true);
		            }else{
		                jQuery("#itemvalue").val("");
		                jQuery("#itemvalue").hide();
		                jQuery("#itemvalue").attr("required",false);
		            }
		        });
		            
	            jQuery.ajax ({
	                type: "GET",
	                url: URL,
	                data: {"action":"categories","auth_token":swifno_auth_token},
	                crossDomain: true,
	                success:function(responceData)
	                {
	                    var data=responceData;
	                    if(data.RESPONSECODE==1)
	                    {
		                    var options='<option value="<?php echo $swifno_opts['swifno_package_category']; ?>"><?php echo $swifno_opts['swifno_package_category'] ? $swifno_opts['swifno_package_category'] : 'Select Package Category'; ?></option>';
	                        jQuery.each(data.CATEGORYLIST,function(field,value) {
	                            options+='<option  value="'+value['cat_name']+'" >'+value['cat_name']+'</option>';
	                        });
	                        jQuery("#package_category").html(options);
	                    }
	                }
	            });
		        
		        jQuery.ajax ({
		            type: "GET",
		            url: URL,
		            data: {"action":"sizes","auth_token":swifno_auth_token},
		            crossDomain: true,
		            success:function(responceData)
		            {
		                var data=responceData;
		                if(data.RESPONSECODE==1)
		                {
		                    var options='<option value="<?php echo $swifno_opts['swifno_package_size']; ?>"><?php echo $swifno_opts['swifno_package_size'] ? $swifno_opts['swifno_package_size'] : 'Select Package Size'; ?></option>';
		                    jQuery.each(data.SIZELIST,function(field,value) {
		                        options+='<option  value="'+value['size_name']+'" >'+value['size_name']+'</option>';
		                    });
		                    
		                    jQuery("#package_size").html(options);
		                }
		            }
		        });
		</script>

		<?php
		}
}
