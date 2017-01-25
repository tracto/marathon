
$("[data-action='show-more-articles']").on('click',function(){ 
		 var offset=$(this).attr("data-offset");
		 if(!offset){
		 	offset=parseInt(4);
		 }
	     $.ajax({
	          type: "GET",
	    	  url: Routing.generate('tds_marathon_article_more', { offset: offset }),
	    	  data:'',
	          cache: false,
	          success: function(data){
	   			$('.articles-liste').append(data);
	   			offset = parseInt(offset) + 4;
	   			$("[data-action='show-more-articles']").attr("data-offset",offset);
	         }
	    });
	  	  
});