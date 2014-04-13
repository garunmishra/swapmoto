<div class="span3 sidebar">
				<div class="widget_box">
					<div class="widget_title">Select a Motercycle</div>
					<div class="widget_body light_blue">
					<form method="get" action="<?php echo base_url(); ?>"	>
					<?php 
					$company_list = $this->Product_model->get_company_list();
					?>					
						<select name="company_id" id="company_id" class="text-box">
                            <option value=''>Select Brand</option>
                            <?php 
							foreach($company_list as $company_val):
							?>
                            <option value="<?php echo  $company_val->company_id; ?>"
							 <?php if(isset($company_id) && $company_id==$company_val->company_id){ ?>selected="selected" <?php } ?>><?php echo( $company_val->company_name); ?></option>
                            <?php
							endforeach;
							?>
                            </select>
							<div id="modelContener">
							<select name="modelid" id="modelid">
							<option selected="selected" value="">Select Model</option>
						<?php
						if(isset($subcatlist)):
							foreach($subcatlist as $subcatval):
							?>
					<option value="<?php echo $subcatval->model_id; ?>"
					 <?php if(isset($model_id) && $subcatval->model_id==$model_id){ ?> selected="selected" <?php } ?>>
					 <?php echo $subcatval->model_name; ?>
					 </option>";
					<?php endforeach; ?>
					<?php
					endif;
					?>
							</select>
							</div>
						
						<input value="Search By Radius" type="text">
						<div class="button_wrap">
						<input type="submit" name="search" value="search" />
							<a href="#" class="red sm-bn">Shop this Motercycle</a> <a href="#" class="blue_underline">All models</a>
						</div>

					</div>
				</div>
				<!-- widget_box -->
				<div class="widget_box">
                                    <?php if(!empty($category)){ ?>
					<div class="widget_title"><?php echo $category->name;?></div>
					<div class="widget_body">
						<ul class="styled">
                                                    <?php if(isset($subcategories) && count($subcategories) > 0): ?>
                                                    <?php foreach($subcategories as $subcategory) { ?>
							<li><a href="<?php echo site_url(implode('/', $base_url).'/'.$subcategory->slug); ?>"><?php echo $subcategory->name;?></a></li>
							<?php
                                                        } 
                                                       endif;?>
						    
						</ul>

					</div>
                                        <?php } ?>
				</div>
				<!-- widget_box -->
				<div class="widget_box">
					<div class="widget_title">We Accept</div>
					<div class="widget_body pagination-centered padding-offset">
					
						<a href="#"><?php echo theme_img('siteimg/visa.png','visa');?>	</a>
						<a href="#"><?php echo theme_img('siteimg/american-express.png','american-express');?></a>
						<a href="#"><?php echo theme_img('siteimg/discover.png','discover');?>	</a>
						<a href="#"><?php echo theme_img('siteimg/master-card.png','master-card');?></a>
						<a href="#"><?php echo theme_img('siteimg/paypal.png','paypal');?></a>
					</div>
				</div>
				<!-- widget_box -->		
				<div class="widget_box black">
					<div class="widget_title">We Accept</div>
					<div class="widget_body pagination-centered padding-offset">
						<a href="#"><?php echo theme_img('siteimg/facebook.png','facebook');?></a>
						<a href="#"><?php echo theme_img('siteimg/twitter.png','twitter');?></a>
						<a href="#"><?php echo theme_img('siteimg/pinterest.png','twitter');?></a>
						<a href="#"><?php echo theme_img('siteimg/googleplus.png','googleplus');?></a>
						<a href="#"><?php echo theme_img('siteimg/linkedin.png','linkedin');?></a>
					</div>
				</div>
				<!-- widget_box -->							
				
			</div>
			<script language="javascript">
			
	$('#company_id').change( function(){
	var catval =  $('#company_id').val();
	if(catval!=''){	//else{
	// show model
	$.post("<?php echo site_url('secure/get_model_list');?>", { id: catval},
	function(data) { 
	
	$('#modelContener').html(data).show();
	}
	});
	// 										
	//}
	});
			</script>