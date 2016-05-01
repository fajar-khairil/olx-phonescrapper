<?php $__env->startSection('content'); ?>
<section class="content-header">
	<h1>
		Iklan Olx
	</h1>
</section>

<?php
	$order_type = ($order_type == 'ASC') ? 'DESC' : 'ASC';
?>

<section class="content">
	<!-- Default box -->
	<div class="box">
		<div class="box-body">
			<h3 style="border-bottom:1px solid #ddd;">Lists of Scrapped Ads</h3>
			<table class="table table-stripped">
				<thead>
					<tr>
						<th style="width:15%;">url</th>
						<th>phone</th>
						<th>city</th>
						<th style="width:15%;">Ad content</th>
						<th>
							<a href="<?php echo e($uri); ?>?page=<?php echo e($page); ?>&col_order=<?php echo e($col_order); ?>&order_type=<?php echo e($order_type); ?>">
								scrapped at&nbsp; 
								<?php if( $order_type == 'ASC' ): ?>
								<i class="fa fa-chevron-up"></i>
								<?php else: ?>
								<i class="fa fa-chevron-down"></i>
								<?php endif; ?>
							</a>
						</th>
						<th class="text-center">verified</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($rows as $row): ?>
					<tr>
						<td><a href="<?php echo e($row->url); ?>" target="_blank"><?php echo e($row->url); ?></a></td>
						<td><?php echo e($row->phone); ?></td>
						<td><?php echo e($row->city); ?></td>
						<td><?php echo str_limit($row->content,120); ?></td>
						<td><?php echo e($row->created_at); ?></td>
						<td class="text-center">
							<input type="checkbox" name="verified" id="ckVerified" <?php echo ((int)$row->verified === 1) ? "checked": '';?>>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<div class="text-right">
				<?php echo $rows->links(); ?>

			</div>
		</div>
	</div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('default::layout.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>