<?php if(validation_errors()):?>



<div class="alert allert-error">



	<a class="close" data-dismiss="alert">×</a>



	<?php echo validation_errors();?>



</div>



<?php endif;?>



<script type="text/javascript">



$(document).ready(function(){



	$('.delete_address').click(function(){



		if($('.delete_address').length > 1)



		{



			if(confirm('<?php echo lang('delete_address_confirmation');?>'))



			{



				$.post("<?php echo site_url('secure/delete_address');?>", { id: $(this).attr('rel') },



					function(data){



						$('#address_'+data).remove();



						$('#address_list .my_account_address').removeClass('address_bg');



						$('#address_list .my_account_address:even').addClass('address_bg');



					});



			}



		}



		else



		{



			alert('<?php echo lang('error_must_have_address');?>');



		}	



	});



	



	$('.edit_address').click(function(){



		$.post('<?php echo site_url('secure/address_form'); ?>/'+$(this).attr('rel'),



			function(data){



				$('#address-form-container').html(data).modal('show');



			}



		);



//		$.fn.colorbox({	href: '<?php echo site_url('secure/address_form'); ?>/'+$(this).attr('rel')});



	});



	



	if ($.browser.webkit) {



	    $('input:password').attr('autocomplete', 'off');



	}



});











function set_default(address_id, type)



{



	$.post('<?php echo site_url('secure/set_default_address') ?>/',{id:address_id, type:type});



}











</script>











<?php



$company	= array('id'=>'company', 'class'=>'span6', 'name'=>'company', 'value'=> set_value('company', $customer['company']));



$first		= array('id'=>'firstname', 'class'=>'span4', 'name'=>'firstname', 'value'=> set_value('firstname', $customer['firstname']));



$last		= array('id'=>'lastname', 'class'=>'span4', 'name'=>'lastname', 'value'=> set_value('lastname', $customer['lastname']));



$email		= array('id'=>'email', 'class'=>'span4', 'name'=>'email', 'value'=> set_value('email', $customer['email']));



$phone		= array('id'=>'phone', 'class'=>'span4', 'name'=>'phone', 'value'=> set_value('phone', $customer['phone']));







//$password	= array('id'=>'password', 'class'=>'span2', 'name'=>'password', 'value'=>'');



//$confirm	= array('id'=>'confirm', 'class'=>'span2', 'name'=>'confirm', 'value'=>'');



?>	



	





<?php echo $this->common_model->getMessage(); ?>



<div class="span9">



		<div class="my-account-box">



		<?php echo form_open('myaccount/index'); ?>



			<fieldset>



				<div class="page-header margin-topn"><h2><?php echo lang('account_information');?></h2></div>



				



				<div class="row mar-bot">



					<div class="span6 sidebar">



						<label for="company"><?php echo lang('account_company');?></label>



						<?php echo form_input($company);?>



					</div>



				</div>



				<div class="row mar-bot">	



					<div class="span4 sidebar">



						<label for="account_firstname"><?php echo lang('account_firstname');?></label>



						<?php echo form_input($first);?>



					</div>



				



					<div class="span4">



						<label for="account_lastname"><?php echo lang('account_lastname');?></label>



						<?php echo form_input($last);?>



					</div>



				</div>



			



				<div class="row mar-bot">



					<div class="span4 sidebar">



						<label for="account_email"><?php echo lang('account_email');?></label>



						<?php echo form_input($email);?>



					</div>



				



					<div class="span4">



						<label for="account_phone"><?php echo lang('account_phone');?></label>



						<?php echo form_input($phone);?>



					</div>



				</div>



				<div class="row mar-bot">



					<div class="span7 sidebar">



						<label class="checkbox">



							<input type="checkbox" name="email_subscribe" value="1" <?php if((bool)$customer['email_subscribe']) { ?> checked="checked" <?php } ?>/> <?php echo lang('account_newsletter_subscribe');?>



						</label>



					</div>



				</div>



			



				



				



			



				<input type="submit" value="<?php echo lang('form_submit');?>" class="btn btn-primary" />







			</fieldset>



		</form>



		</div>



  </div>


<div id="address-form-container" class="hide">



</div></div>