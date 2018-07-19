/**
 * Convert 24 hour format to 12 hour format
 *
 */
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

/**
 * Display new data set based on button filter
 *
 */
function ajaxRequest(e, filter, value){
		e.preventDefault();
		var dataString = {
            //_token: $(this).data('token'),
			filter: filter,
			value: value
        };
		console.log(dataString);
		$.ajax({
			type: "POST",
			url: "/ajaxRequest",
			data: dataString,
			success: function(data){
				
				/**
				 *
				 *Change value in statistics table
				 *
				 */
				var statTotal = data.leads.length;
				var answered = data.leads.filter(lead => lead.status);
				var statAnswered = answered.length;
				var unanswered = data.leads.filter(lead => !lead.status);
				var statUnanswered = unanswered.length;
				var statAnswerRate = Math.round(statAnswered / statTotal * 100);
				
				
				$('#stat-total').text(statTotal);
				$('#stat-answered').text(statAnswered);
				$('#stat-unanswered').text(statUnanswered);
				$('#stat-answer-rate').text(statAnswerRate + "%");
				/**
				 *
				 *Change values in Call Log table
				 *
				 */
				$('.lead-row').remove();//Remove previous data set
				
				jQuery.each(data.leads, function(i, val){//Output rows based AJAX returned value
					
					var leadSource;
					if(val.description){
						leadSource = '<strong>' + val.description + '</strong><br>' + val.number;
					}
					else{
						leadSource = '<strong>Tracking Number Deleted</strong>';
					}

					var caller_name;
					if (!val.caller_name){
						caller_name = "<em>No Caller ID</em>";
					} else {
						caller_name = val.caller_name;
					}
					
					var caller_city = val.city.toLowerCase();
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
							'<strong>' + caller_name + ', <span style="text-transform: capitalize">' + caller_city + '</span></strong>' +
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
	}

$(document).ready(function(){
	$.ajaxSetup({//Setup X-CSRF-TOKEN verification
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	$(".ajax-button").click(function(e){
		ajaxRequest(e, $(this).data('filter-type'), $(this).data('filter-value'));
	});
	
	$(".ajax-text").keyup(function(e){
		ajaxRequest(e, $(this).data('filter-type'), $(this).val());
	});
});