<?php include('header.php'); ?>

<?php // to start this moduls change "<" to ">" ?>
<div class="row">
	<div class="span12">
		<div class="page-header">
			<div class="button_set pull-right">
				<a class="btn" href="<?php echo site_url($this->config->item('admin_folder').'/settings/addEmailTemplate/');?>"><i class="icon-plus-sign"></i> <?php echo "Add Email Template";?></a>
			</div>
			<h3><?php echo "Email Template";?></h3>
		</div>
<?php // to start this moduls change "<" to ">" ?>
<?php if(count($canned_messages) > 0): ?>
<table class="table table-striped">
	<tbody>
	<?php foreach($canned_messages as $message): ?>
		<tr class="gc_row">
			<td><?php echo $message['name']; ?></td>
			<td>
				<span class="btn-group pull-right">
					<a class="btn" href="<?php echo site_url($this->config->item('admin_folder').'/settings/addEmailTemplate/'.$message['id']);?>"><i class="icon-pencil"></i> <?php echo lang('edit');?></a>
					<?php if($message['deletable'] == 1) : ?>	
						<a class="btn btn-danger" href="<?php echo site_url($this->config->item('admin_folder').'/settings/delete_message/'.$message['id']);?>" onclick="return areyousure();"><i class="icon-trash icon-white"></i> <?php echo lang('delete');?></a>
					<?php endif; ?>
				</span>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>		

<script type="text/javascript">
function areyousure()
{
	return confirm('<?php echo lang('confirm_are_you_sure');?>');
}
</script>
<?php include('footer.php');