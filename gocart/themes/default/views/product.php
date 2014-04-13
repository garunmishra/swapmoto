<?php include('header.php'); ?>


<div class="container mar-top">
    	
       <a href="<?php echo site_url($menu['category_info']->slug);?>"><?php echo $menu['category_info']->name; ?></a> 
<?php if(isset($menu['sub_category_info'])){
?>
>> <a href="<?php echo site_url($menu['sub_category_info']->slug);?>"><?php echo $menu['sub_category_info']->name; ?></a>
<?php } ?>
>> <a style="cursor:default; text-decoration:none"><?php echo $product->name;?></a>

        	<!-- popup code !-->
       
        	<div class="span5">
       		 <div class="row primary-img tx-cntr">
			<div id="primary-img" class="span5 sidebar ">
			
				<?php
				if(!isset($product->images[0]) || $product->images[0]==''){
				$photo = '<img   src="'.get_img('gocart/themes/default/assets/img/siteimg/no_picture.png').'" alt="'.$product->seo_title.'"/>' ;
				} else {
				 $photo	= '<img class="responsiveImage" src="'.base_url('uploads/images/medium/'.$product->images[0]).'" alt="'.$product->seo_title.'"/>';
				 }
				echo $photo;
				?>
					</div>
		</div>
        <div class="row">
        <div class="span5 product-images sidebar">
       
		
		<?php 
		if(count($product->images) > 1):?>
		<div class="row">
			<div class="span4 product-images">
				<?php foreach($product->images as $image):?>
				<img class="span1" onclick="$(this).squard('390', $('#primary-img'));" src="<?php echo base_url('uploads/images/medium/'.$image);?>"/>
				<?php endforeach;?>
			</div>
		</div>
		<?php else: ?>
		<div class="row">
			<div class="span4 product-images">
			<img class="span1" onclick="$(this).squard('390', $('#primary-img'));" src="<?php echo get_img('gocart/themes/default/assets/img/siteimg/no_picture.png'); ?>"/>
			</div>
			</div>
		
		<?php endif;?>
        </div>
        </div>
        
        	</div>
       <!-- end !-->
       
        
        
        <div class="span18">
        <div class="page-header mar-topn">
					<h3 style="font-weight:normal"><?php echo $this->common_model->word_crop($product->name,'20','..');?></h3>
                        <div class="row">
                	<div class="fl">
                    Code: <span class="red12"><?php echo $product->sku;?></span>
                    </div>
                	
                </div>
				</div>
                
                <div class="page-header mar-topn pad-bot">
               	<div class="row">
                <div class="fl blk18">Price: <span  class="red18"><?php echo format_currency($product->price); ?></span></div>
                <?php if($product->free_shipping==0){ ?><div class="fr"> Free Shipping! </div> <?php } ?>
                </div>
                </div>
                
                <div class="row">
                <h4>Product Information:</h4>
                <p><?php echo $product->description; ?></p>

                </div>
                <div class="product-cart-form">
					<?php echo form_open('cart/add_to_cart', 'class="form-horizontal"');?>
					<input type="hidden" name="cartkey" value="<?php echo $this->session->flashdata('cartkey');?>" />
					<input type="hidden" name="id" value="<?php echo $product->id?>"/>
					<fieldset>
					<?php if(count($options) > 0): ?>
						<?php foreach($options as $option):
							$required	= '';
							if($option->required)
							{
								$required = ' <p class="help-block">Required</p>';
							}
							?>
							<div class="control-group">
								<label class="control-label"><?php echo $option->name;?></label>
								<?php
								/*
								this is where we generate the options and either use default values, or previously posted variables
								that we either returned for errors, or in some other releases of Go Cart the user may be editing
								and entry in their cart.
								*/

								//if we're dealing with a textfield or text area, grab the option value and store it in value
								if($option->type == 'checklist')
								{
									$value	= array();
									if($posted_options && isset($posted_options[$option->id]))
									{
										$value	= $posted_options[$option->id];
									}
								}
								else
								{
									if(isset($option->values[0]))
									{
										$value	= $option->values[0]->value;
										if($posted_options && isset($posted_options[$option->id]))
										{
											$value	= $posted_options[$option->id];
										}
									}
									else
									{
										$value = false;
									}
								}

								if($option->type == 'textfield'):?>
									<div class="controls">
										<input type="text" name="option[<?php echo $option->id;?>]" value="<?php echo $value;?>" class="span4"/>
										<?php echo $required;?>
									</div>
								<?php elseif($option->type == 'textarea'):?>
									<div class="controls">
										<textarea class="span4" name="option[<?php echo $option->id;?>]"><?php echo $value;?></textarea>
										<?php echo $required;?>
									</div>
								<?php elseif($option->type == 'droplist'):?>
									<div class="controls">
										<select name="option[<?php echo $option->id;?>]">
											<option value=""><?php echo lang('choose_option');?></option>

										<?php foreach ($option->values as $values):
											$selected	= '';
											if($value == $values->id)
											{
												$selected	= ' selected="selected"';
											}?>

											<option<?php echo $selected;?> value="<?php echo $values->id;?>">
												<?php echo($values->price != 0)?'('.format_currency($values->price).') ':''; echo $values->name;?>
											</option>

										<?php endforeach;?>
										</select>
										<?php echo $required;?>
									</div>
								<?php elseif($option->type == 'radiolist'):?>
									<div class="controls">
										<?php foreach ($option->values as $values):

											$checked = '';
											if($value == $values->id)
											{
												$checked = ' checked="checked"';
											}?>
											<label class="radio">
												<input<?php echo $checked;?> type="radio" name="option[<?php echo $option->id;?>]" value="<?php echo $values->id;?>"/>
												<?php echo $option->name;?> <?php echo($values->price != 0)?'('.format_currency($values->price).') ':''; echo $values->name;?>
											</label>
										<?php endforeach;?>
										<?php echo $required;?>
									</div>
								<?php elseif($option->type == 'checklist'):?>
									<div class="controls">
										<?php foreach ($option->values as $values):

											$checked = '';
											if(in_array($values->id, $value))
											{
												$checked = ' checked="checked"';
											}?>
											<label class="checkbox">
												<input<?php echo $checked;?> type="checkbox" name="option[<?php echo $option->id;?>][]" value="<?php echo $values->id;?>"/>
												<?php echo($values->price != 0)?'('.format_currency($values->price).') ':''; echo $values->name;?>
											</label>
											
										<?php endforeach; ?>
									</div>
									<?php echo $required;?>
								<?php endif;?>
								</div>
						<?php endforeach;?>
					<?php endif;?>
					
					<div class="control-group">
						<label class="control-label"><?php echo lang('quantity') ?></label>
						<div class="controls">
							<?php if(!config_item('inventory_enabled') || config_item('allow_os_purchase') || !(bool)$product->track_stock || $product->quantity > 0) : ?>
								<?php if(!$product->fixed_quantity) : ?>
									<input class="span2" type="text" name="quantity" value=""/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php endif; ?>
								<button class="btn btn-primary btn-large" type="submit" value="submit"><i class="icon-shopping-cart icon-white"></i> <?php echo lang('form_add_to_cart');?></button>
							<?php endif;?>
						</div>
					</div>
					
					</fieldset>
					</form>
				</div>
                
                
        </div>
    
    </div>
<div class="container mar-top">
    	<div class="span5">
        	<div class="widget_box">
					<div class="widget_title1">Questions</div>
					<div class="widget_body1">
						<div class="controls">
						<div style="display:none"; id="showmsg"></div>
						<textarea name="faq_box" id="faq_box" class="spanqp"></textarea>
						<button class="btn btn-primary btn-large pull-right" id="faq_btn">Ask this seller A Question</button>
						</div>
                        <!-- code for review and retting -->
						<?php if(empty($feedback)){ ?>
						<div style="display:none"; id="feedback_showmsg"></div>
						<div id="feedback_cont">
						<textarea name="feedback_box" id="feedback_box" class="span2 cart-inp1"></textarea>
						<select name="reting" id="reting">
						<option value="">Select reting</option>
						<option value="0">0</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						</select>
						<button class="btn btn-primary btn-large pull-right" id="feedback_btn">Send Feedback & reting</button>
						</div>
						<?php } else { ?>
						reting : <?php echo $feedback->rate ?>
						review  : <?php echo $feedback->feedback; ?>
						<?php } ?>
						<!--=================Show feedback histry -->
						
						<?php foreach($feedbacklist as $feedback){ ?>
						<?php echo " Feedback : ".$feedback->feedback."<br>"; ?>
						<?php } ?>
						<!-- !-->
						<!-- end -->
                        
                        
                        

					</div>
				</div>
                </div><div class="span18">
        	<div class="widget_box">
					<div class="widget_title1">Seller Rating</div>
					<div class="widget_body1">
						<div class="row">
                        	<div class="fl"><strong>Seller:</strong>    <?php echo $product->firstname; ?></div>
                            <div class="fr"><strong>Ratings:</strong>  5</div>
                            
                        </div>
                        <div class="row">
                        	<div class="fl"><strong>Avg. Ship:</strong>   87.5 Hours</div>
                            </div>
                         <div class="row">
                        	<div class="fl"><a href="#">Seller Statistics</a> <span> | </span> <a href="#">Seller Reviews</a> <span> | </span>  <a href="<?php echo base_url('seller/seller_listed_items/'.$product->user_id); ?>">Seller's Other Items</a></div>
                      </div>

					</div>
				</div>
                </div>
    </div>
	
<div class="container mar-top">
    <div class="span10 sidebar">
    <div class="widget_title2">Similar Product</div>
    <div class="widget_body2">
    
    
    <div class="row">
	
	<?php if(isset($similar_product) && count($similar_product>0)){ 
	foreach($similar_product as $sproduct){ 
	?>
					<div class="span17 margin-right">
						<div class="shop_box">
							<div class="title">RECENTLY LISTED</div>
							<img alt="" src="images/jacket.png">
							<div class="sub-title"><?php echo $sproduct->name; ?></div>
							<div class="info">
								<span class="size">K2</span>
								<span class="size_cm">Size: 160cm</span>
								<span class="condition">Condition: Excellent</span>
								<span class="discount">64% off of Retail</span>
							</div>
							<div class="price">$<?php echo $sproduct->sort_price;?></div>

						</div>
					</div>
					
					<?php }
					} ?>
					
					
					
					

				</div></div>
                </div>
    
    </div>
<script language="javascript">
$('#faq_btn').click(function(){
		var faq = $('#faq_box').val().trim();
		var product_id = '<?php  echo $product->id?>';
		$.post("<?php echo site_url('myaccount/save_faq');?>", { faq: faq, product_id:product_id},
				function(data,status) { 
				if(data==1){
				$('#showmsg').html('Your question has been sent to seller, They will tuch very soon.').show();
				} else{
				$('#showmsg').html('Opps ! try again to ask question.').show();
				}
				});
	});
	
	$('#feedback_btn').click(function(){
	var feedback_val = $('#feedback_box').val().trim();
	var product_id = '<?php  echo $product->id?>';
	var reting = $('#reting').val().trim();
	var haserror = false
	if(feedback_val==''){
	$('#feedback_showmsg').html('Enter feedback').show();
	haserror = true;
	}
	if(reting==''){
	$('#feedback_showmsg').html('Select reting').show();
	haserror = true;
	}
	if(haserror==false){
	var st = window.confirm('Are you sure want to submit feedback');
	if(st==true){
	$.post("<?php echo site_url('myaccount/save_feedback');?>", { feedback_msg: feedback_val, product_id:product_id, reting:reting},
				function(data) { 
				var respose = JSON.parse(data);
				
				if(respose.status==1){
				$('#feedback_cont').hide();
				$('#feedback_showmsg').html(respose.message).show();
				} else{
				$('#feedback_showmsg').html(respose.message).show();
				}
				});
				}
		}
		
	});
</script>
<?php include('footer.php'); ?>