$(function() {
	$("[data-action='playlist-scores-more']").on('click',function(){ 
	  var idjoggeur=$(this).data('id');
	  $(this).addClass('active');

	  $(document).ajaxStart(function(){
          $('#loading').show();
       }).ajaxStop(function(){
          $('#loading').hide();
       });

	  $.ajax({
	    type: "GET",
	    url: Routing.generate('tds_marathon_joggeur_morescores_js', { id: idjoggeur }),
	    data:'',
	    cache: false,
	    success: function(data){
	       $("#playlist-scores-more").html(data);

	          
	    },
	  });

	});
});