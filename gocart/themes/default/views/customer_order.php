<div class="span9 sidebar" >
	<div class="panel panel-default">
<table class="table">

  <thead>

    <th>Order N.</th>

    <th>product name</th>

    <th>date</th>

    <th>status</th>

  <thead>
<tbody>
  <tr>

    <div class="row">

	<div class="span9">

		<div class="page-header">

			<h2><?php echo lang('order_history');?></h2>

		</div>

		<?php if($orders):

			echo $orders_pagination;

		?>

		<table class="table table-bordered table-striped">

			<thead>

				<tr>

					<th><?php echo lang('order_date');?></th>

					<th><?php echo lang('order_number');?></th>

					<th><?php echo lang('order_status');?></th>

				</tr>

			</thead>



			<tbody>

			<?php

			foreach($orders as $order): ?>

				<tr>

					<td>

						<?php $d = format_date($order->ordered_on); 

				

						$d = explode(' ', $d);

						echo $d[0].' '.$d[1].', '.$d[3];

				

						?>

					</td>

					<td><?php echo $order->order_number; ?></td>

					<td><?php echo $order->status;?></td>

				</tr>

		

			<?php endforeach;?>

			</tbody>

		</table>

		<?php else: ?>

			<?php echo lang('no_order_history');?>

		<?php endif;?>

	</div>

</div>

  </tr>
</tbody>
</table></div>
</div>
</div>