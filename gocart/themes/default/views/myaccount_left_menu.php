<div class="container mar-top">

<div class="span3 sidebar">
	<div class="widget_box">
    <div class="widget_title">My Account</div>
		<div class="widget_body">
	       	 <?php $uri = $_SERVER['REQUEST_URI']; 
			  $seg = $this->uri->segment(2);
			 ?>
             <ul class="styled">
    			<li><a <?php if (strpos($uri,'/myaccount/') !== false && $seg == '')  echo 'class="act"';?> href="<?php echo base_url().'myaccount/'; ?>">Edit Accounnt Information</a></li>
                <li><a <?php if (strpos($uri,'/myaccount/inbox') !== false)  echo 'class="act"';?> href="<?php echo base_url().'myaccount/inbox'; ?>">Inbox</a></li>
				<li><a  <?php if (strpos($uri,'/myaccount/order') !== false)  echo 'class="act"';?> href="<?php echo base_url().'myaccount/order/'; ?>">Order History</a></li>
                
                <li><a <?php if (strpos($uri,'/myaccount/list_item') !== false)  echo 'class="act"';?> href="<?php echo base_url().'myaccount/list_item'; ?>">My listed Itemes</a></li>                
                 <li><a <?php if (strpos($uri,'/myaccount/address') !== false)  echo 'class="act"';?> href="<?php echo base_url().'myaccount/address/'; ?>">Address</a></li>
                 <li><a <?php if (strpos($uri,'/myaccount/change_password') !== false)  echo 'class="act"';?> href="<?php echo base_url().'myaccount/change_password'; ?>">Change Password</a></li>
                <li><a href="<?php echo base_url()?>secure/logout">Logout</a></li>
             
             </ul>
 		</div>
	</div>
</div>