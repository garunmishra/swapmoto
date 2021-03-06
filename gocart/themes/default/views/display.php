<div class="span9 content_body">
   <div class="page-header margin-topn">
      <h1><?php echo 'All Product' ?></h1>
   </div>
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
   <?php if(!empty($products)):?>
   <div class="row">
      <?php foreach($products as $product):?>
      <?php $product->images =  json_decode($product->images);
         $defult_img = '';
         
         if(isset($product->images[0]) && !empty($product->images[0]))
         
         {
         
         $photo	= ''; //theme_img('no_picture.png', lang('no_image_available'));
         
         $product->images	= array_values($product->images);
         
         	
         
         
         
         	//$photo	= '<img width= height="104" src="'.base_url('uploads/images/thumbnails/'.$primary->filename).'" alt="'.$product->seo_title.'"/>';
         
         } else{
         
         
         
          $defult_img	= '<img  height="104" src="'.get_img('gocart/themes/default/assets/img/siteimg/no_picture.png').'" alt="'.$product->seo_title.'"/>' ;//theme_img('no_picture.png', lang('no_image_available'));
         
         
         
         }
         
         if($defult_img==''){
         
          $photo	= '<img  height="104" src="'.base_url('uploads/images/thumbnails/'.$product->images[0]).'" alt="'.$product->seo_title.'"/>';
         
         }
         
         else { 
         
         $photo = $defult_img;
         
         }
         
         
         
         
         
         ?>
      <div class="span17" style="height:315px;margin-bottom: 10px">
         <div class="shop_box">
            <div class="title">RECENTLY LISTED</div>
            <?php //echo theme_img('siteimg/jacket.png', true );?>
            <a class="thumbnail" href="<?php echo base_url($product->slug); // site_url(implode('/', $base_url).'/'.$product->slug); ?>">
            <?php echo $photo; ?>
            </a>
            <?php if($product->excerpt != ''): ?>
            <div class="sub-title"><?php echo $product->name; ?></div>
            <?php endif; ?>
            <div class="info">
               <span class="condition">Condition: 
               <?php 
                  if($product->condition==0){ echo "Not define"; }
                  
                   if($product->condition==1){ echo "Old"; }
                  
                   elseif($product->condition==2){ echo "Good";}
                  
                   elseif($product->condition==3){echo "Excellent";} ?></span>
               <!--<span class="discount">64% off of Retail</span> !-->
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
   </div>
   <div class="pull-right"><?php echo $this->pagination->create_links_query_string(); ?></div>
   <?php endif;?>
</div>

