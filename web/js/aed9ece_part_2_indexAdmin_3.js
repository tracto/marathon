$("[data-action='show-chronique']").on('click',function(){
	if($(this).hasClass("active")){
		$(this).siblings(".chroniques-list").hide();
		$(this).removeClass('active');
	}else{
		console.log("yo chronique");
		$(this).siblings(".chroniques-list").show();
		$(this).addClass('active');
	}
	
});



$("[data-action='switch-show-brief']").on('click',function(){ 
	    $.ajax({
	        type: "GET",
	        url: Routing.generate('tds_marathon_theme_switch'),
	        data:'',
	        cache: false,
	        success: function(data){
	           $('#closeTheme-brief').html(data);
	           $("[data-action='switch-cancel']").on('click',function(){
					$('#closeTheme-brief').html("");
				});
	        }
	    });    
	    return false;
});



$("[data-action='active-show-brief']").on('click',function(){
	$(".activeTheme-brief").show();
	return false; 
});


$("[data-action='show-website-form']").on('click',function(){
	$(".form-website").show();
	return false;
});










