function ajaxFilter(data){
	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  	},
	   	type:'POST',
	   	url:'/ajaxRequest',
	   	data:data,
	   	success:function(){
			console.log('success');
	   	}
	});
}
$(document).ready(function(){
	$(".ajax-button").click(function(){
		var data = this.dataset.ajax;
		ajaxFilter(data);
	});
});