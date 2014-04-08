<?php include('header.php'); ?>
<div class="container mar-top">
		<div class="row">
			<?php include('left.php'); ?>
			<!-- span4 -->
            <?php  //include('catalog_mid.php') ?>
            <?php $this->load->view($file); ?>
			
			<!-- .span8 -->

                </div>
    <div style="text-align: center"><?php //echo $this->pagination->create_links();?></div>
		<!-- row -->
		<?php include('feature.php'); ?>
		<!-- .row -->
    </div> <!-- /container -->
    
    <script type="text/javascript">
	window.onload = function(){
		$('.product').equalHeights();
	}
</script>
    
<?php include('footer.php'); ?>
