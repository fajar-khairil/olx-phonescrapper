@extends('default::layout.default')

@section('content')
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
							<a href="{{ $uri }}?page={{ $page }}&col_order={{ $col_order }}&order_type={{ $order_type }}">
								scrapped at&nbsp; 
								@if( $order_type == 'ASC' )
								<i class="fa fa-chevron-up"></i>
								@else
								<i class="fa fa-chevron-down"></i>
								@endif
							</a>
						</th>
						<th class="text-center">verified</th>
					</tr>
				</thead>
				<tbody>
					@foreach($rows as $row)
					<tr>
						<td><a href="{{ $row->url }}" target="_blank">{{ $row->url }}</a></td>
						<td>{{ $row->phone }}</td>
						<td>{{ $row->city }}</td>
						<td>{!! str_limit($row->content,120) !!}</td>
						<td>{{ $row->created_at }}</td>
						<td class="text-center">
							<input type="checkbox" name="verified" id="ckVerified" <?php echo ((int)$row->verified === 1) ? "checked": '';?>>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>

			<div class="text-right">
				{!! $rows->links() !!}
			</div>
		</div>
	</div>
</section>
@endsection