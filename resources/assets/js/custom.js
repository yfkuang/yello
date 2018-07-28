// JavaScript Document
$(document).ready(function(){
	//$('.custom-dropdown').dropdown();
	
	$('.custom-dropdown').on('click', function(e) {
		e.stopPropagation();
	});
	
	$('#modalCarousel').carousel('pause');
	
	$('.nav-addNumber').on('click', function(){
		$('#modalCarousel').carousel(0);
		$('#modalCarousel').carousel('pause');
	});
});