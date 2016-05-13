$('.form').submit(function(e){
	return false;
});

$('.tld-input').bind('input', function() {
	var tld = $('.tld-input').val();
	var url = "/tools/tld/" + tld;
	$.ajax({
		method: "GET",
		datatype: 'application/json',
		url: url,
		statusCode: {
			404: function(data) {
				console.log($.parseJSON(data))
			},
			500: function(data) {
				console.log('Logged a server error');
			}
		}
	}).success(function(data) {
		var json = $.parseJSON(data);
		var result = [];
		console.log(json);
		$('table').empty()	
		if(json) {
			var row_count = Object.keys(json).length;
		
		for(i = 0;i < row_count; ++i) {
			result[i] = Object.keys(json)[i];
		}	

		for(k = 0; k < row_count; ++k) {
			$('table').append('<tr><td>' + result[k] + '</td><td class="modify-table">' + '<input type="text" value="' + json[result[k]] + '" style="border:none; background-color: inherit" class="input-change"></td></tr>');
			
		}
		}	
	})
		
});
