<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title><?php echo (!empty($seo_title)) ? $seo_title .' - ' : ''; echo $this->config->item('company_name'); ?></title>
    <?php if(isset($meta)):?>
	<?php echo $meta;?>
    <?php else:?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php endif; ?>

    <?php echo theme_css('bootstrap.css', true);?>


<?php echo theme_css('bootstrap-responsive.css', true);?>
<?php echo theme_css('style.css', true);?>

<?php echo theme_js('jquery.js', true);?>
<?php echo theme_js('bootstrap.js', true);?>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->



  </head>
  <body>
<div class="container">
	<div class="row">
		<div class="span5">
			<div class="logo"><?php echo theme_img('siteimg/logo.png', 'swap moto');?></div>
		</div>	
		<div class="span6">
			<div class="account_overview clearfix">
				<a href="<?php echo base_url()?>secure/my_account"><span class="icon-pencil">&nbsp;</span>MY ACCOUNT</a>
				<a href="#"><span class="icon-star">&nbsp;</span>WISHLIST</a>		
                 <a href="<?php echo base_url()?>secure/sale"><span class="icon-pencil">&nbsp;</span>Sale</a>		
                <!-- custom menu by Garun-->
                <?php if(!$this->Customer_model->is_logged_in(false, false)):?>
                <a href="<?php echo base_url()?>secure/login"><span class="icon-pencil">&nbsp;</span>Login</a>
                <?php else : ?>
                <a href="<?php echo base_url()?>secure/logout"><span class="icon-pencil">&nbsp;</span>Logout</a>            
                <?php endif;?>
                <!-- end custom menu !-->
                </div>
	    <!-- .account_overview -->
			<div class="checkout_buttons clearfix">
				<a href="#" class="btnn red"><span class="icon-shopping-cart icon-white">&nbsp;</span> VIEW CART</a>
				<span href="#" class="btnn black-white-gradient space-left-right">0 Items $0.00 <a href="#" class="small-btn white-button">Checkout</a></span>
			</div>
		</div>
	</div>
	
    

</div>
