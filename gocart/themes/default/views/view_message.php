<div class="container mar-top">


<div class="content">
<h1><?php echo $message->title;  ?></h1>
<table class="">
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
		<br /><a href="profile.php?id=<?php echo $cvalue->userid; ?>"><?php echo $cvalue->username; ?></a></td>
    	<td class="left">
    	<?php echo $cvalue->message; ?></td>
		<td><div class="date">Sent: <?php echo date('m/d/Y H:i:s' ,$cvalue->timestamp); ?></div></td>
    </tr>
<?php
}
//We display the reply form
?>
</table><br />
<h2>Reply</h2>
<div class="center">
    <form action="<?php echo base_url('/myaccount/view_message/'.$id); ?>" method="post">
    	<label for="message" class="center">Message</label><br />
        <textarea cols="40" rows="5" name="message" id="message"></textarea><br />
        <input type="submit" value="Send" />
    </form>
</div>

</div> 
 
    </div>

