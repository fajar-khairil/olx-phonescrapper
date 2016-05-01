<?php $__env->startSection('content'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Srapper 
		<small>Mengambil data dari <?php echo e($source); ?></small>
	</h1>
</section>

<section class="content">
	<!-- Default box -->
	<div class="box">
		<div style="min-height: 250px;" class="box-body">

			<form id="scrap-form" method="POST" class="form-inline" style="border-bottom: 2px solid #ddd;padding-bottom: 10px;">
				<input type="hidden" name="source" id="source" value="<?php echo e($source); ?>">

				<div class="form-group" style="margin-right: 10px;">
					<label>Kota : </label>
					<select id="city" style="width: 150px;" class="form-control" name="city">
						<option value="0">Semua Kota</option>
						<?php foreach( $cities as $city ): ?>
							<?php if($source == 'olx'): ?>
							<option value="<?php echo e($city->slug); ?>"><?php echo e($city->name); ?></option>
							<?php else: ?>
							<option value="<?php echo e($city->id); ?>"><?php echo e($city->name); ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>		

				<div class="form-group" style="margin-right: 10px;">
					<label>Category : </label>
					<?php echo $categoriesHtml; ?>

				</div>	

				<div class="form-group" style="margin-right: 10px;">
					<label>Keyword : </label>
					<input id="keyword" style="width: 150px;" type="text" class="form-control" name="keyword" placeholder="Kata Pencarian..">
				</div>

				<div class="form-group" style="margin-right: 10px;">
					<label>Page Limit : </label>
					<input style="width:80px;" id="limit" type="number" class="form-control" name="limit" placeholder="Limit halaman..">
				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-primary"><i class="fa fa-gears">&nbsp;</i>SCRAP</button>
				</div>
			</form>
		
			<h4>Job Lists</h4>
			<table id="table-logs" class="table table-stripped" style="margin-top: 10px;">
				<thead>
					<th>#</th>
					<th>Date Started</th>
					<th>City</th>
					<th>Keyword</th>
					<th class="text-center">Limit</th>
					<th class="text-center">Fetched Records</th>
					<th class="text-center">Status</th>
				</thead>
				<tbody>
					
				</tbody>
			</table>

			<div class="text-right" id="pagination">
				
			</div>

		</div><!-- /.box-body -->
	</div><!-- /.box -->
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('default::layout.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>