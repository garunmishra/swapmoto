<h1><?php echo 'All Product' ?></h1>
<?php if(!empty($category->description)): ?>
	<!--<div class="row">
		<div class="span12"><?php echo $category->description; ?></div>
	</div>-->
	<?php endif; ?>

<?php if((!isset($subcategories) || count($subcategories)==0) && (!isset($products) || count($products) == 0)):?>
		<div class="alert alert-info">
			<a class="close" data-dismiss="alert">×</a>
			<?php echo lang('no_products');?>
		</div>
	<?php endif;?>


<?php if(isset($subcategories) && count($subcategories) > 0): ?>
		<div class="span3">
			<ul class="nav nav-list well">
				<li class="nav-header">
				Subcategories
				</li>
				
				<?php foreach($subcategories as $subcategory):?>
					<li><a href="<?php echo site_url(implode('/', $base_url).'/'.$subcategory->slug); ?>"><?php echo $subcategory->name;?></a></li>
				<?php endforeach;?>
			</ul>
		</div>
<?php endif;?>

                    <div class="span9 content_body">
				<div class="row">
                                    <?php if(!empty($products)):?>
                                    <?php foreach($products as $product):?>
                                    <?php
						$photo	= theme_img('no_picture.png', lang('no_image_available'));
						$product->images	= array_values($product->images);
				
						if(!empty($product->images[0]))
						{
							$primary	= $product->images[0];
							foreach($product->images as $photo)
							{
								if(isset($photo->primary))
								{
									$primary	= $photo;
								}
							}

							//$photo	= '<img width= height="104" src="'.base_url('uploads/images/thumbnails/'.$primary->filename).'" alt="'.$product->seo_title.'"/>';
						}
                                                $photo	= '<img width= height="104" src="'.base_url('uploads/images/thumbnails/'.$primary->filename).'" alt="'.$product->seo_title.'"/>';
						
						?>
					<div class="span2" style="height:305px;margin-bottom: 10px">
						<div class="shop_box">
							<div class="title">RECENTLY LISTED</div>
                           <?php //echo theme_img('siteimg/jacket.png', true );?>
                                                        <a class="thumbnail" href="<?php echo site_url(implode('/', $base_url).'/'.$product->slug); ?>">
							<?php echo $photo; ?>
						</a>
							<?php if($product->excerpt != ''): ?>
                                                        <div class="sub-title"><?php echo $product->excerpt; ?></div>
                                                        <?php endif; ?>
							<div class="info">
                                                            <span class="condition">Condition: Excellent</span>
								<span class="discount">64% off of Retail</span>
							</div>
                                                        <div class="price">
                                                        <?php if($product->saleprice > 0):?>
								<?php echo format_currency($product->price); ?>
								<?php echo format_currency($product->saleprice); ?>
							<?php else: ?>
								 <?php echo format_currency($product->price); ?>
							<?php endif; ?>                                                                
							</div>
                                                        <?php if((bool)$product->track_stock && $product->quantity < 1) { ?>
								<div class="stock_msg"><?php echo lang('out_of_stock');?></div>
							<?php } ?>

						</div>
					</div>
					<!-- span2 -->
                                        <?php endforeach?>
			<?php endif;?>
			

				</div>
                        
			</div>
        