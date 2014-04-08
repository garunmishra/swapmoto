<?php echo $this->common_model->getMessage();?>

<div class="span9 pull-right">

		<div class="page-header" style="height:40px;">

			

				<h2 class="fl"><?php echo lang('address_manager');?></h2>

			

			<div class="span6">

				<a href="<?php echo base_url(); ?>myaccount/add_address" class="btn fr edit_address"><?php echo lang('add_address');?></a>

			</div>

		</div>

		<div class="row">

			<div class="span9 sidebar" id='address_list'>

			<?php if(count($addresses) > 0):?>

				<table class="table table-bordered table-striped">

			<?php

			$c = 1;

				foreach($addresses as $a):?>

					<tr id="address_<?php echo $a['id'];?>">

						<td>

							<?php

							$b	= $a['field_data'];

							echo format_address($b, true);

							?>

						</td>

						<td>

							<div class="row-fluid">

								<div class="span12">

									<div class="btn-group pull-right">

										<input type="button" class="btn edit_address" rel="<?php echo $a['id'];?>" value="<?php echo lang('form_edit');?>" />

										<input type="button" class="btn btn-danger delete_address" rel="<?php echo $a['id'];?>" value="<?php echo lang('form_delete');?>" />

									</div>

								</div>

							</div>

							<div class="row-fluid">

								<div class="span12">

									<div class="pull-right" style="padding-top:10px;">

										<input type="radio" name="bill_chk" onclick="set_default(<?php echo $a['id'] ?>, 'bill')" <?php if($customer['default_billing_address']==$a['id']) echo 'checked="checked"'?> /> <?php echo lang('default_billing');?>

										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="ship_chk" onclick="set_default(<?php echo $a['id'] ?>,'ship')" <?php if($customer['default_shipping_address']==$a['id']) echo 'checked="checked"'?>/> <?php echo lang('default_shipping');?>

									</div>

								</div>

							</div>

						</td>

					</tr>

				<?php endforeach;?>

				</table>

			<?php endif;?>

			</div>

		</div>

	</div>

    </div>

    

    

<script type="text/javascript">



$(document).ready(function(){



	$('.delete_address').click(function(){



		if($('.delete_address').length > 1)



		{



			if(confirm('<?php echo lang('delete_address_confirmation');?>'))



			{



				$.post("<?php echo site_url('myaccount/delete_address');?>", { id: $(this).attr('rel') },



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



