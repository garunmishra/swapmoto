<?php echo plugins_js('lightbox/js/jquery-1.11.0.min.js');?>



<?php echo plugins_js('lightbox/js/lightbox.js');?>



<?php // echo plugins_css('lightbox/css/screen.css');?>



<?php echo plugins_css('lightbox/css/lightbox.css');?>







<?php 



//set "code" for searches



if(!$code)



{



	$code = '';



}



else



{



	$code = '/'.$code;



}



function sort_url($lang, $by, $sort, $sorder, $code, $admin_folder)



{



	if ($sort == $by)



	{



		if ($sorder == 'asc')



		{



			$sort	= 'desc';



			$icon	= ' <i class="icon-chevron-up"></i>';



		}



		else



		{



			$sort	= 'asc';



			$icon	= ' <i class="icon-chevron-down"></i>';



		}



	}



	else



	{



		$sort	= 'asc';



		$icon	= '';



	}



		







	$return = site_url($admin_folder.'/products/index/'.$by.'/'.$sort.'/'.$code);



	



	echo '<a href="'.$return.'">'.lang($lang).$icon.'</a>';







} ?>







<script type="text/javascript">



function areyousure()



{



	return confirm('<?php echo lang('confirm_delete_product');?>');



}



</script>



<style type="text/css">



	.pagination {



		margin:0px;



		margin-top:-3px;



	}



</style>

<?php echo $this->common_model->getMessage(); ?>

<div class="row">



	<div class="span9" style="border-bottom:1px solid #f5f5f5;">



		<div class="row">



			<div class="span4">



				<?php //echo $this->pagination->create_links();?>	&nbsp;



			</div>

            <div style="float:right"> <a href="<?php echo site_url('secure/add_item');?>">List an item</a> </div>



           

	<table class="table table-striped">



		<thead>



			<tr>



				



				<th><?php echo lang('name'); ?></th>



				<th>Category <?php //echo lang('price'); ?></th>



				<th>Subcategory<?php //echo lang('saleprice');?></th>



				<th> <?php echo lang('quantity');?></th>

				<th> status </th>               



              <th> Admin Approve</th>



                



				<th>

					Action

				</th>



			</tr>



		</thead>



		<tbody>



		<?php echo (count($products) < 1)?'<tr><td style="text-align:center;" colspan="7">'.lang('no_products').'</td></tr>':''?>



	<?php foreach ($products as $product):?>



			<tr>



				



				<td><?php echo $product->name ;?></td>



				<td><?php echo $product->categoriname;?></td>



				<td><?php echo $product->subcategoriname;?></td>



				<td><?php echo $product->quantity;?></td>



				<td>

				<?php

					echo ($product->enabled==0?theme_img('siteimg/cross.png',true):theme_img('siteimg/tick.png',true));

				?>

				</td>

				<td>

				<?php echo ($product->admin_approve==0?theme_img('siteimg/cross.png',true):theme_img('siteimg/tick.png',true));?>

				</td>

					<td>



					<span class="btn-group pull-right">



						<a class="btn" href="<?php echo  base_url().'secure/add_item/' .$product->id;?>">

						<?php //echo theme_img('siteimg/c_edit.png',true);?>  <?php echo'edit';?></a>



						



						<a class="btn btn-danger" href="<?php echo  base_url().'myaccount/remove_item/' .$product->id; ?>" onclick="return areyousure();"><i class="icon-trash icon-white"></i> <?php echo 'delete';?></a>



					</span>



				</td>



			</tr>



	<?php endforeach; ?>



		</tbody>



	</table>





		</div>



	</div>



</div>



















</div>