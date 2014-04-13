<div class="span9">
<?php echo $this->common_model->getMessage(); ?>




<div class="content">

<div class="page-header"> <h1><?php echo $message->title;  ?></h1></div>

<table class="table">

	<tr>

    	<th>User</th>

        <th>Message</th>

		<th>Date</th>

    </tr>

<?php

foreach($convertion as $cvalue){

?>

	<tr>

    	<td class="author center">

		<a href="profile.php?id=<?php echo $cvalue->userid; ?>"><?php echo $cvalue->username; ?></a></td>

    	<td class="left">

    	<?php echo $cvalue->message; ?></td>

		<td><div class="date">Sent: <?php echo date('m/d/Y H:i:s' ,$cvalue->timestamp); ?></div></td>

    </tr>

<?php

}

//We display the reply form

?>

</table>
<div class="page-header"> 
<h2>Reply</h2>
</div>
<div class="center">

    <form action="<?php echo base_url('/myaccount/view_message/'.$id); ?>" method="post">

    	<label for="message" class="center">Message</label><br />

        <textarea class="textarea span_textarea" cols="40" rows="5" name="message" id="message"></textarea><br />

        <input type="submit" value="Send" class="pull-right btnn red" />

    </form>

</div>



</div> 

 

    </div>
</div>


