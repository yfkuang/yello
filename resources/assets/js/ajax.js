function hours_am_pm(time) {
	var hours = time[0] + time[1];
	var min = time[3] + time[4];
	if (hours <= 12) {
		return hours + ':' + min + ' AM';
	} else {
		hours=hours - 12;
		hours=(hours.length < 10) ? '0'+hours:hours;
		return hours+ ':' + min + ' PM';
	}
}

$(document).ready(function(){
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	$(".ajax-button").click(function(e){
		e.preventDefault();
		var dataString = {
            //_token: $(this).data('token'),
			filter: $(this).data('filter-type'),
			value: $(this).data('filter-value')
        };
		
		$.ajax({
			type: "POST",
			url: "/ajaxRequest",
			/*beforeSend: function(xhr){
				var token = $('meta[name="csrf-token"]').attr('content');

				if (token) {
					  return xhr.setRequestHeader('X-CSRF-TOKEN', token);
				}
			},*/
			data: dataString,
			success: function(data){
				$('.lead-row').remove();
				
				jQuery.each(data.leads, function(i, val){
					
					var leadSource;
					jQuery.each(data.leadSources, function(i, source_val){
						if(val.lead_source_id === source_val.id){
							leadSource = '<strong>' + source_val.description + '</strong><br>' + source_val.number;
						}
						else if(val.lead_source_id !== source_val.id){}
						else if(!val.lead_source_id){
							leadSource = '<strong>Tracking Number Deleted</strong>';
						}
					});
				
					var caller_name;
					if (!val.caller_name){
						caller_name = "<em>No Caller ID</em>";
					} else {
						caller_name = val.caller_name;
					}
					
					var caller_city = val.city;
					var caller_number = val.caller_number;
					
					var status;
					if (!val.status){
						status = "No Answer";
					} else {
						status = val.status;
					}
					
					var duration = val.duration;
					
					var convertDate = new Date(val.created_at) + ' ';
					var arrayDate = convertDate.split(" ");
					var time = hours_am_pm(arrayDate[4]);
					var date = '<strong>' + time + '</strong>' + '<br>' + arrayDate[0] + ', ' + arrayDate[1] + ' ' + arrayDate[2] + ', ' + arrayDate[3];
					
					var string = 
					'<tr class="lead-row">' +
						'<td class="lead-row-source">' +
							leadSource +
						'</td>' +
						'<td class="lead-row-caller">' +
							'<strong>' + caller_name + ', ' + caller_city + '</strong>' +
							'<br>' + caller_number +
						'</td>' +
						'<td class="lead-row-status">' +
							'<span style="text-transform: capitalize">' + status + '</span>' +
						'</td>' +
						'<td class="lead-row-duration">' +
							duration + 's' +
						'</td>' +
						'<td class="date">' +
							date +
						'</td>' +
					'</tr>';
					
					$('#lead-table tbody').append(string);
				});
					
				console.log(data);
				//console.log(dataString);
			}
		});
	});
});