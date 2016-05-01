(function(jQuery)
{
	var $ = jQuery;
	Scrapper = {
		'base_uri' : document.location.protocol+'//'+document.location.host+$('meta[name="x-base-uri"]').attr('value')
	};

	Scrapper.getLog = function(src,options){
		options.col_order = options.col_order || 'id';
		options.order_type = options.order_type || 'DESC';
		options.page = options.page || 1;

		var self = this;
		$.ajax({
			url : self.base_uri+"/logs",
			method : "get",
			dataType : "json",
			data : options
		}).success(function(response){
			var $target = $('table#table-logs tbody');
			$target.html('');
			var items = response.items;

			for(var i in items)
			{
				var html = '<tr>';
				html += '<td>'+items[i].id+'</td>';
				html += '<td>'+items[i].created_at+'</td>';
				html += '<td>'+items[i].city+'</td>';
				html += '<td>'+items[i].keyword+'</td>';
				html += '<td class="text-center">'+items[i].limit+'</td>';
				html += '<td class="text-center">'+items[i].records+'</td>';

				var status = '<div class="label label-default">UNKNOWN</div>';
				switch(items[i].status)
				{
					case 0: 
						status = '<div class="label label-warning">PENDING</div>';
						break
					case 1: 
						status = '<div class="label label-success">PROCCESSING</div>';
						break
					case 2: 
						status = '<div class="label label-primary">DONE</div>';
						break
				}

				html += '<td class="text-center">'+
					status
				'</td>';

				html += '</tr>';
				$target.append(html);
				$('div#pagination').html(response.paginationHtml);
			}
		});
	};

	Scrapper.parseQuery = function (qstr) {
		var query = {};
		var a = qstr.substr(1).split('&');
		
		for (var i = 0; i < a.length; i++) {
			var b = a[i].split('=');

			query['page'] = decodeURIComponent(b[1] || '');
		}

		return query['page'];
	}

	Scrapper.postJob = function(){
		var self = this;
		var postData = {
			city : $('#city').val(),
			keyword : $('#keyword').val(),
			limit : $('#limit').val(),
			category : $('#category').val()
		};

		var src = $('#source').val();

		$.ajax({
			url : self.base_uri+"/jobs",
			method : "post",
			dataType : "json",
			data : postData
		}).success(function(response){
			console.log(response);
		});
	};

	$('form#scrap-form').submit(function(){
		Scrapper.postJob();

		return false;
	});

	$(document).on('click','ul.pagination > li a',function(evt){
		var target = evt.target;

		Scrapper.getLog('olx',{
			page : Scrapper.parseQuery($(target).attr('href'))
		});

		return false;
	});

	Scrapper.getLog('olx',{});

	setInterval(function(){
		Scrapper.getLog('olx',{});
	},2000);

	return Scrapper;
})(jQuery);