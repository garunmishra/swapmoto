<div class="span3 sidebar">
				<div class="widget_box">
					<div class="widget_title">Select a Motercycle</div>
					<div class="widget_body light_blue">
						<select>
							<option selected="selected" value="Select a year">Select a year</option>
							<option value="Select a year">1990</option>
						</select>
						<select>
							<option selected="selected" value="Select a year">Select Make</option>
							<option value="Select a year">1990</option>
						</select>
						<select>
							<option selected="selected" value="Select a year">Select Model</option>
							<option value="Select a year">1990</option>
						</select>
						<input value="Search By Radius" type="text">
						<div class="button_wrap">
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