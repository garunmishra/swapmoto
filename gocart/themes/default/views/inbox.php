<div class="span9">
<?php echo $this->common_model->getMessage(); ?>
<div class="page-header">

  <h3>Unread Messages(<?php echo count($inbox['unread_result']); ?>):</h3>
  	
       

        	<table class="table">

	<tr>
    	<th class="title_cell">Title</th>
        <th>Nb. Replies</th>
        <th>Participant</th>
        <th>Date of creation</th>
    </tr>
	   <?php
	   foreach($inbox['unread_result'] as $unread_inbox){
	   ?>
	   <tr>
    	<td class="left"><a href="<?php echo base_url('/myaccount/view_message/'.$unread_inbox->id); ?>"><?php echo htmlentities($unread_inbox->title, ENT_QUOTES, 'UTF-8'); ?></a></td>
    	<td><?php echo $unread_inbox->reps-1; ?></td>
    	<td><a style="cursor:default"><?php echo htmlentities($unread_inbox->firstname, ENT_QUOTES, 'UTF-8'); ?></a></td>
    	<td><?php echo date('Y/m/d H:i:s' ,$unread_inbox->timestamp); ?></td>
    </tr>
	   
	   <?php } ?>
	   
     <?php   if(intval($inbox['unread_result'])==0) {
?>
	<tr>
    	<td colspan="4" class="center">You have no unread message.</td>
    </tr>
<?php
}
?>
 </table>
  <div class="page-header"> <h3>Read Messages(<?php echo count($inbox['read_result']); ?>):</h3>
</div>
 <table class="table">
	<tr>
    	<th class="title_cell">Title</th>
        <th>Nb. Replies</th>
        <th>Participant</th>
        <th>Date of creation</th>
    </tr>
	   <?php 
	   foreach($inbox['read_result'] as $read_inbox){

	   ?>
	   <tr>
    	<td class="left"><a href="<?php echo base_url('/myaccount/view_message/'.$read_inbox->id); ?>"><?php echo htmlentities($read_inbox->title, ENT_QUOTES, 'UTF-8'); ?></a></td>
    	<td><?php echo $read_inbox->reps-1; ?></td>
    	<td><?php echo htmlentities($read_inbox->firstname, ENT_QUOTES, 'UTF-8'); ?></td>
    	<td><?php echo date('Y/m/d H:i:s' ,$read_inbox->timestamp); ?></td>
    </tr>
	   
	   <?php } ?>
	   
     <?php   if(intval($inbox['read_result'])==0) {
?>
	<tr>
    	<td colspan="4" class="center">You have no read message.</td>
    </tr>
<?php
}
?>
 </table>
        
    

    </div></div></div>



	

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
</script>
