<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title><?php echo (!empty($seo_title)) ? $seo_title .' - ' : ''; echo $this->config->item('company_name'); ?></title>
    <?php if(isset($meta)):?>
	<?php //echo $meta;?>
    <?php else:?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php endif; ?>

	<?php echo theme_css('bootstrap.css', true);?>
    <?php echo theme_css('bootstrap-responsive.css', true);?>
    <?php echo theme_css('style.css', true);?>
    
    <?php echo theme_js('jquery.js', true);?>
    <?php echo theme_js('bootstrap.min.js', true);?>
    <?php echo theme_js('squard.js', true);?>
    <?php echo theme_js('equal_heights.js', true);?>
    
    <?php //echo theme_js('bootstrap.js', true);?>
    <?php //echo theme_js('jquery_1.7.1_js.js', true);?>
    <?php echo theme_js('common.js', true);?>
    <?php echo theme_js('jquery.form.js', true); ?>


    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->



  </head>
  <body>
<div class="container">

	<div class="row">

		<div class="span5">

			<div class="logo"><a href="<?php echo base_url();?>"><?php echo theme_img('siteimg/logo.png', 'swap moto');?></a></div>

		</div>	

		<div class="span5 sidebar fr">

			<div class="account_overview clearfix fr">
				<?php
				$cust = $this->go_cart->customer();
				if(!empty($cust['firstname'])) {
				?>
				<a><span class="icon-pencil">&nbsp;</span>Welcome, <?php echo $cust['firstname']; ?></a>
                <?php
				}
				?>
                
				<a href="<?php echo base_url()?>myaccount/"><span class="icon-pencil">&nbsp;</span>MY ACCOUNT</a>

				<a href="#"><span class="icon-search">&nbsp;</span>TRACK ORDER</a>

				<!--<a href="#"><span class="icon-star">&nbsp;</span>WISHLIST</a>-->

				

                <?php if(!$this->Customer_model->is_logged_in(false, false)):?>

                <a href="<?php echo base_url()?>secure/login"><span class="icon-pencil">&nbsp;</span>Login</a>

                <?php else : ?>

                <a href="<?php echo base_url()?>secure/logout"><span class="icon-pencil">&nbsp;</span>Logout</a>

                <?php endif;?>

                
              
             
                

                </div>

	    <!-- .account_overview -->

			<div class="checkout_buttons clearfix">

				<a href="<?php echo site_url('cart/view_cart');?>" class="btnn red"><span class="icon-shopping-cart icon-white">&nbsp;</span> VIEW CART</a>

				<span href="#" class="btnn black-white-gradient space-left-right">
                                <?php
                                    if($this->go_cart->total_items() > 1)
                                    {
                                        echo $this->go_cart->total_items().' items';
                                    }
                                    else
                                    {
                                        echo $this->go_cart->total_items().' item';
                                    }
                                ?>
                                    
                                    <a href="<?php echo base_url();?>checkout/" class="small-btn white-button">Checkout</a></span>

			</div>

		</div>

	</div>

	<!-- .row -->

	<div class="row dark-black-gradient">

		

		

			<div class="span4">
				<a href="<?php echo base_url()?>secure/add_item" class="btnn-big white-black-border pull-left">SELL YOUR PARTS AND GEAR</a>
			</div>

			<div class="span8">

				<div class="red_text">Recycle your parts or gear in $$</div>

				<ul class="list_inline">

					<li><a href="#">List your item for year</a></li>

                   <li><a href="#">Unlimited photos</a></li>

                    <li><a href="#">No images or listing fees</a></li>

					

					

				</ul>

			</div>

	</div>

	<!-- .row -->

    <div class="row navbar dark-black-gradient">

      <div class="navbar-inner">

        <div class="container">

        	<a href="#" class="brand">Select your choice</a>

          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

          </a>

          

          <div class="nav-collapse pagination-centered">
            <ul class="nav">
            <?php
			 foreach($this->categories as $cat_menu):?>
			<li <?php if($cat_menu['category']->slug==$this->uri->segment(1)){ ?> class="active" <?php } ?>><a href="<?php echo site_url($cat_menu['category']->slug);?>">  
           <?php echo get_img('uploads/images/thumbnails/'.$cat_menu['category']->image,true); ?>
            <span class="text"><?php echo $cat_menu['category']->name;?></span></a> </li>
								<?php endforeach;?>
              
            </ul>
          </div><!--/.nav-collapse -->

        </div>

      </div>

    </div>



</div>
