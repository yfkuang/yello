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
						leadSource = '<span style="color:#FD8099; font-weight: bold;">Tracking Number Deleted</span>';
					}

					var caller_name;
					if (!val.caller_name){
						caller_name = "<em>No Caller ID</em>";
					} else {
						caller_name = val.caller_name;
					}
					
					var caller_city;
					if(!val.city){
					    caller_city = 'Unknown';
					} else {
						caller_city = val.city.toLowerCase();	
					}
					
					var caller_number = val.caller_number;
					
					var status;
					if (!val.status){
						status = '<i class="fas fa-times" style="color: #FD8099; width: 16px;"></i>&nbsp;No Answer';
					} else {
						status = '<i class="fas fa-check" style="color: #26DAD2;"></i>&nbsp;<span style="text-transform: capitalize">' + val.status;
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
						'<td class="lead-row-date">' +
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

/**
 * Display new Available Numbers data set based on button filter
 *
 */
function ajaxNumbers(e, areaCode){
		e.preventDefault();
		var dataString = {
            //_token: $(this).data('token'),
			areaCode: areaCode,
        };
	
		console.log(dataString);
		$.ajax({
			type: "POST",
			url: "/ajaxNumbers",
			data: dataString,
			success: function(data){
				
				/**
				 *
				 *Change values in Available Numbers table
				 *
				 */
				$('.number-row').remove();//Remove previous data set
				

				$('#number-table tbody').append(data);
				
				//Replace Modal window title
				$('.modal-title').text('Available Numbers');
				
				//Create event handleer for clicking on "Purchase" button
				$('#number-table tbody').find('.btn').each(function(i){
					$(this).click(function(e){
						ajaxStore(e, $(this).val());
					});
				});
				//console.log(data);
			}
		});
	}

/**
 * Display new Available Numbers data set based on button filter
 *
 */
function ajaxStore(e, number){
		e.preventDefault();
		var dataString = {
            //_token: $(this).data('token'),
			number: number,
        };
	
		console.log(dataString);
		$.ajax({
			type: "POST",
			url: "/ajaxStore",
			data: dataString,
			success: function(data){
				
				/**
				 *
				 *Change values in Edit Nuber module
				 *
				 */
				
				console.log(data);
			}
		});
	}

/**
 * Replace Modal with edit options
 *
 */
function ajaxEdit(e, id){
		e.preventDefault();
		var dataString = {
            //_token: $(this).data('token'),
			id: id,
        };
	
		console.log(dataString);
		$.ajax({
			type: "POST",
			url: "/ajaxEdit",
			data: dataString,
			success: function(data){
				
				/**
				 *
				 *Change values in Edit Nuber module
				 *
				 */
				
				//Set Variables
				var id = data.id;
				var number = data.number;
				var description = data.description;
				var forwardingNumber = data.forwarding_number;
				
				//Replace Modal window title
				$('.modal-title').text('Edit Number');
				
				//Replace form with values requested from AJAX
				$('.edit-number').text('Number: ' + number);
				$('input[name=edit-description]').val(description);
				$('input[name=edit-forwarding-number]').val(forwardingNumber);
				$('.ajax-save').val(id);
				$('.ajax-delete').val(id);
				
				//console.log(data);
			}
		});
	}

function ajaxSave(e, id, description, forwarding_number){
	e.preventDefault();
	var dataString = {
		//_token: $(this).data('token'),
		id: id,
		description: description,
		forwarding_number: forwarding_number,
	};

	console.log(dataString);
	$.ajax({
		type: "POST",
		url: "/ajaxSave",
		data: dataString,
		success: function(data){

			/**
			 *
			 *Change values in Edit Nuber module
			 *
			 */
			$('#addNumber').modal('hide');//Close Modal Window
			ajaxLeadSources(e);
			console.log(data);
		}
	});
}

function ajaxDelete(e, id){
	e.preventDefault();
	var dataString = {
		//_token: $(this).data('token'),
		id: id,
	};

	console.log(dataString);
	$.ajax({
		type: "POST",
		url: "/ajaxDelete",
		data: dataString,
		success: function(data){

			/**
			 *
			 *Change values in Edit Nuber module
			 *
			 */
			$('#addNumber').modal('hide');//Close Modal Window
			ajaxLeadSources(e);
			console.log(data);
		}
	});
}

function ajaxLeadSources(e){
	e.preventDefault();
	var dataString = {
		//_token: $(this).data('token'),
	};

	console.log(dataString);
	$.ajax({
		type: "POST",
		url: "/ajaxLeadSources",
		data: dataString,
		success: function(data){

			/**
			 *
			 *Change values in Edit Nuber module
			 *
			 */
			$('.leadsources-row').remove();//Remove previous data set
			$('#leadsources-table tbody').append(data);
			
			$('#leadsources-table tbody').find('.btn').each(function(i){
				$(this).click(function(e){
					ajaxEdit(e, $(this).data('id'));
					$('#modalCarousel').carousel(2);
					$('#modalCarousel').carousel('pause');
				});
			});
			//console.log(data);
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
	
	$('.nav-addNumber').click(function(e){
		e.preventDefault();
		$('.modal-title').text('Add Number');
	});
	
	$(".ajax-numbers").click(function(e){
		ajaxNumbers(e, $('#areaCode').val());
	});
	
	$(".ajax-edit-number").click(function(e){
		ajaxEdit(e, $(this).data('id'));
	});
	
	$(".ajax-save").click(function(e){
		ajaxSave(e, $(this).val(), $('input[name=edit-description]').val(), $('input[name=edit-forwarding-number]').val());
	});
	
	$(".ajax-delete").click(function(e){
		ajaxDelete(e, $(this).val());
	});
});