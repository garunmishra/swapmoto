<?php if(validation_errors()):?>



<div class="alert allert-error">



	<a class="close" data-dismiss="alert">×</a>



	<?php echo validation_errors();?>



</div>



<?php endif;?>







<?php



$password	= array('id'=>'password', 'class'=>'span3', 'name'=>'password', 'value'=>'');



$confirm	= array('id'=>'confirm', 'class'=>'span3', 'name'=>'confirm', 'value'=>'');



?>	







<?php echo $this->common_model->getMessage();?>



<div class="row">



<div class="span9">



		<div class="my-account-box">



		<?php echo form_open('myaccount/change_password'); ?>



			<fieldset>



				<div class="page-header margin-topn"><h2><?php echo "Change your Password";?></h2></div>



				

				<div class="row mar-bot">	



					<div class="span3 sidebar">

						<label for="account_password"><?php echo lang('current_password');?></label>

						<?php echo form_password(array('name'=>'current_password', 'id' => 'current_password', 'class' => 'span3'));?>

					</div>



					<div class="span3 ">



						<label for="account_password"><?php echo lang('account_password');?></label>



						<?php echo form_password($password);?>



					</div>







					<div class="span3 ">



						<label for="account_confirm"><?php echo lang('account_confirm');?></label>



						<?php echo form_password($confirm);?>



					</div>



				</div>



			



				<input name="update" type="submit" value="update" class="btn btn-primary" />







			</fieldset>



		</form>



		</div>



	</div>



</div>



</div>