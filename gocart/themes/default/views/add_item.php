<?php  include('header.php');?>
<?php $GLOBALS['option_value_count']		= 0;
?>
	<script type="text/javascript">
		<?php $timestamp = time();?>
		
		
           
		function del_img(img_name,sr,isremove=true){
			var con = window.confirm('Are you sure want to remove this image from list?');
			if(con==true){
			$('#img_'+sr).remove();
			$('#hid_'+sr).remove();
			if(isremove==true){
			$.post("<?php echo site_url('UploadImage/remove_image');?>", { img_name: img_name},
				function(data) { 
				});
			}
			}
		}
	</script>
    <?php  ?>
<div class="container mar-top">
    	<div class="row header_widgets">
    		<div class="span4">
    			<div class="head_widget">
    				<span class="edit pull-left">&nbsp;</span>
    				<h3>LIST YOUR ITEM</h3>
    				<p>Take some photo % write a few words. It's free to list.</p>
    			</div>
    			<!-- head_widget -->
    		</div>
    		<div class="span4">
    			<div class="head_widget">
	    			<span class="ship pull-left">&nbsp;</span>
	    			<h3>SHIP YOUR ITEM</h3>
	    			<p>Update the tracking number in My Account.</p>
	    		</div>
    			<!-- head_widget -->
    		</div>
    		<div class="span4">
    			<div class="head_widget">
	    			<span class="dollar pull-left">&nbsp;</span>
	    			<h3>GET PAID</h3>
	    			<p>We will mail you a check.</p>
    			</div>
    			<!-- head_widget -->
    		</div>
    	</div>
    	<!-- /.row -->
		<div class="row">
			<div class="span9 content_body fl">
            	
                <div class="tab-pane" id="product_photos">
				<div class="row">
				</div>
				<div class="row">
					<div class="span8">
						
						<div id="gc_photos">
							
						
						</div>
					</div>
				</div>
			</div>
                
                
                
                
                
                
                
                
                
                
                
						<div class="form_wrapper">
							<div class="title bdr-top-right"><span class="picture">&nbsp;</span> UPLOAD PICTURES</div>
							<div class="box center pagination-centered">
								<span class="camera">&nbsp;</span>
                                <div id="brandImage">
                                <?php
								foreach($images as $key=>$img){
									?>
                                    <img id='img_<?php echo $key; ?>' class='image_cls' src='<?php echo site_url('uploads/images/small/'.$img);?>' width='50' hieght='50' onClick="del_img('<?php echo $img; ?>','<?php echo $key; ?>',false)">
									<?php
								}
								?>
                              
                                </div>
								<div class="bold light-gray">Add a photo or two!</div>
								<p class="short">Or three, or more! Guests love photos that highlight the features of your space.</p>
								
                           
                   
				   <form class="ajaxform" method="post" enctype="multipart/form-data" action='<?php echo site_url('UploadImage/upload_Image');?>'>
                <input type="hidden" name="do" value="upload"/>
                Upload your image <input type="file" name="Filedata" id="photo" onChange="abc()" />
				<input type="hidden" name="timestamp" value="<?php echo $timestamp;?>" />
				<input type="hidden" name="token" value="<?php echo md5('unique_salt' . $timestamp);?>" />
				<input type="hidden" name="customer_id" value="<?php echo $customer['id'];?>" />
				</form>
							</div>
							<!-- /.box.center -->
                            <?php echo form_open('/secure/add_item/'.$id, array('id'=>'listitem') ); ?>
                            <div id="img_cant"> <?php  
								foreach($images as $key=>$img){
									?>
                                    <input type="hidden" value="<?php echo $img; ?>" name="images[]" id="hid_<?php echo $key; ?>">
                                    <?php } ?>
                                    </div>
							<div class="title"><span class="title-edit">&nbsp;</span> Give us a Title for what you are selling</div>
							<div class="box">
							<?php
						$data	= array('placeholder'=>lang('name'), 'name'=>'name', 'id'=>'name', 'value'=>set_value('name', $name), 'class'=>'text-box');
						echo form_input($data);
						?>
                        <div id="nameError" class="errorfield"></div>
								<span class="help-block pagination-right">50 characters remaining</span>
							</div>
							<!-- /.box.center -->
							<div class="title"><span class="title-edit">&nbsp;</span> Tell a Story ABOUT YOUR ITEM</div>
							<div class="box">
								<label for="">You Know your item best. The more information you can provide, the faster it will sell.</label>								
                                <?php
						$data	= array('name'=>'description', 'class'=>'text-box','id'=>'description', 'value'=>set_value('description', $description));
						echo form_textarea($data);
						?>
                        <div id="descriptionError" class="errorfield"></div>
								<span class="help-block pagination-right">50 characters remaining</span>
							</div>
							<!-- /.box.center -->
							<div class="title"><span class="grid">&nbsp;</span> Choose a Category that best fits.</div>
							<div class="box"> 
								<div class="styled-select">
                                                                  
							<select name="category" id="category_id" class="text-box">
                                                    <option value=''>Select category</option>
                                                    <?php 
							foreach($category_list as $catval):
							?>
                                              <option value="<?php echo  $catval->id; ?>" <?php if(isset($cat_id) && $cat_id==$catval->id){ ?> selected="selected"<?php }?>><?php echo( $catval->name); ?></option>
                            <?php
							endforeach;
				?>
                            </select>
								</div>
                                 <div id="categoryError" class="errorfield"></div>
                                
                                <div id='sub_category_container' <?php if(!isset($sub_category_data)){ ?> style="display:none" <?php } ?>>								
                                <div id='sub_category_list' class="styled-select">
                                   <?php echo (isset($sub_category_data)? $sub_category_data:'');?>
                                </div>
                                </div>
                                
                                
                                
								<div class="line">&nbsp;</div>
								<div class="styled-select"> 
                                <select name="company_id" id="company_id" class="text-box">
                            <option value=''>select Brand</option>
                            <?php 
							foreach($company_list as $company_val):
							?>
                            <option value="<?php echo  $company_val->company_id; ?>"
							 <?php if($company_id==$company_val->company_id){ ?>selected="selected" <?php } ?>><?php echo( $company_val->company_name); ?></option>
                            <?php
							endforeach;
							?>
                             <option value="other">Other</option>
                            </select>
                           
                            </div>         
                             <div id="companyError" class="errorfield"></div>                   
                            <div id='othercatCantner' style="display:none"></div>
                            <div id='modelContener'  <?php if(!isset($model_data)){ ?> style="display:none" <?php } ?>>	
							 <?php echo (isset($model_data)? $model_data:'');?>
							</div>
								<div class="line">&nbsp;</div>

								<div class="styled-select">
									<?php
	 	$options = array(	 '0'	=> 'Select Condetion',
							'1'	=> 'Old',
							'2'	=> 'Good',
							'3' => 'Exilent'
							);
		echo form_dropdown('condition', $options, set_value('condition',$condition), 'class="text-box"');
		?>
								</div>
								<div class="styled-select">
									<?php
	 	$options = array(	 '0'	=> lang('disabled')
							,'1'	=> lang('enabled')
							);
		echo form_dropdown('enabled', $options, set_value('enabled',$enabled), 'class="text-box"');
		?>
								</div>
                                <div class="styled-select">
                                <?php

		$options = array(	 '1'	=> lang('shippable')
							,'0'	=> lang('not_shippable')
							);
		echo form_dropdown('shippable', $options, set_value('shippable',$shippable), 'class="text-box"');
		?></div>
        
        <div class="styled-select">
                               
                               <?php
		$options = array(	 '1'	=> lang('taxable')
							,'0'	=> lang('not_taxable')
							);
		echo form_dropdown('taxable', $options, set_value('taxable',$taxable), 'class="text-box"');
		?>
                               </div>
								<div class="styled-select">
                                
                                <?php
		$options = array(	 '0'	=> 'Select Quantity'
							,'1'	=> '1'
							,'2'	=> '2'
							,'3'	=> '3'
							,'4'	=> '4'
							,'5'	=> '5'
							,'10'	=> '10'
							,'20'	=> '20'
							,'40'	=> '40'
							,'50'	=> '50'
							,'100'	=> '100'							
							);
		echo form_dropdown('quantity', $options, set_value('quantity',$quantity), 'class="text-box"');
		?>
								</div>
								
							</div>
							<!-- /.box.center -->
							<div class="title"><span class="money">&nbsp;</span> SET YOUR PRICE</div>
							<div class="box clearfix">
                            <?php
		$data	= array('name'=>'price', 'value'=>set_value('price', $price), 'class'=>'text-box', 'placeholder'=>"Retail Price");
		echo form_input($data);?>
		
		<?php
		$data	= array('name'=>'saleprice', 'value'=>set_value('saleprice', $saleprice), 'class'=>'text-box','placeholder'=>"Enter Price");
		echo form_input($data);?>
								
								
                                <button name="submit" value="submit" type="submit" class="pull-right btnn red"><?php echo lang('form_save');?></button>
							</div>
							<!-- /.box -->
						</div>
                        </form>
						<!-- form_wrapper -->

			</div>
			<!-- .span9 -->			
			<div class="span3 sidebar fr">

				<div class="widget_box pagination-centered mtop">
					<h3 class="redc">PHOTO TIP</h3>
					<p>Listings with multiple images are 5x more likely to sell</p>
					<p>Would you buy anything used if you didn’t see a picture of it?</p>
				</div>
				<!-- widget_box -->
				<div class="widget_box pagination-centered mtop">
					<h3 class="redc">LISTING YOUR ITEM IS FREE</h3>
					<p>If your item does not sell, we don’t charge you. We handle all the payments processing and customer service. All you need to do is list and ship your gear then we send you a check.</p>
				</div>
				<!-- widget_box -->		
				<div class="widget_box pagination-centered mtop">
					<h3 class="redc">TALK ABOUT YOUR ITEM</h3>
					<p>Take some photos &amp; write a few  words. it's free to list.</p>
				</div>
				<!-- widget_box -->							
				
			</div>
			<!-- span3 -->
			
		</div>
		<!-- row -->
		<?php include('feature.php'); ?>
		<!-- .row -->
    </div>
    <?php include('footer.php'); ?>
<script>
$(document).ready(function() {
	// apply class in uploadify
	//$('#file_upload-button').attr('class','btnn red');
	
	
	
	
	
	
	
	$('#category_id').change(function()
	{ 
		$.post("<?php echo site_url('secure/ajaxrequest');?>", { id: $('#category_id').val(), type:'getsubcat'},
		function(data) { if(data) {
			$('#sub_category_list').html(data);
			$('#sub_category_container').show();
		} else {
			$('#sub_category_list').html('');
			$('#sub_category_container').hide();
		}
	});
	});
	
	
	
	$('#company_id').change( function(){
	var catval =  $('#company_id').val();
	if('other'==catval){
		$("#modelContener").html('');
	$('#othercatCantner').html('<input type="text" name="newcompany" id="newcompany" class="text-box" placeholder="Other Brand"><input type="text" name="newmodelname" id="newmodelname" class="text-box" placeholder="Other Model">').show();
	}
	else{
	$('#othercatCantner').html('').hide();
	// show model
	$.post("<?php echo site_url('secure/ajaxrequest');?>", { id: catval, type:'getmodelcat'},
	function(data) { 
	
	$('#modelContener').html(data).show();
	
	});
	// 										
	}
	});
	// submit form validation js
	$('#listitem').submit(function(){
		var haserror = false;
		if($('#name').val().trim()==''){
			haserror = true;
			$('#nameError').html('Enter item title');
		} else {$('#nameError').html('');}
		
		/*if($('#description').val().trim()==''){
			haserror = true;
			$('#descriptionError').html('Enter item desc');
		} else {$('#nameError').html('');}
		*/
		if($('#category_id').val()==''){
			haserror = true;
			$('#categoryError').html('Select category for item');
		} else $('#categoryError').html('');
		
		if($('#company_id').val()==''){
			haserror = true;
			$('#companyError').html('Select company for item');	
		} else $('#companyError').html('');
		
		
		if(haserror==true){
			return false;	
		} else return true;
		 });
});

	
	
	function addNewModel(modelValue)
	{
		if(modelValue == 'other') { 
		$('#modelContener').append('<input type="text" name="newmodelname" id="newmodelname" class="text-box" placeholder="Other Model">');
		}
	}


<?php if(count($images)==0){?>
		var i = 0;
		<?php } else{ ?>
		var i = <?php echo count($images)+1; ?>;
		<?php } ?>


			function abc(){
                    $("#view").html('');
                    $("#view").html('<img src="./theme/img/loading.gif" />');
                    $(".ajaxform").ajaxForm(function(response){
                                   // alert('The File ' + file.name + " has been uploaded with response "+response+" --data status: "+data);
                 $('#brandImage').append("<img id='img_"+i+"' class='image_cls' src='<?php echo site_url('uploads/images/small/');?>/"+response+"' width='50' hieght='50' onClick=del_img('"+response+"','"+i+"')>");
				 $('#img_cant').append("<input type='hidden' id='hid_"+i+"' name='images[]' value='"+response+"'>");
                  i++;
                    }).submit();
                }

</script>
