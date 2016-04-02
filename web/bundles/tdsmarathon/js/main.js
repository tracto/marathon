$(function() {

  var url = window.location.pathname;
  var urlTheme=Routing.generate('tds_marathon_theme_view');
  var urlJoggeur=Routing.generate('tds_marathon_joggeur_view');

  var urlSep = url.split('/');

  if(urlSep.length >= 6){
    url = url.substr(0,url.lastIndexOf('/'));
    if(url==urlTheme){
      url=Routing.generate('tds_marathon_theme_home');
    }else if(url==urlJoggeur){
      url=Routing.generate('tds_marathon_joggeur_home');
    }
  }

  $('nav.navbar-header a[href="'+ url +'"]').parent().addClass('active');




	$.ajax({
        type: "GET",
        url: Routing.generate('tds_marathon_kilometrage'),
        data:'',
        cache: false,
        success: function(data){
           $('#kilometrage-wrapper').html(data);
           $('.audio-list').hide();
        },
        complete: function () {
        	
        }
    }); 

    $(".btn-becky").each(function(){
      if($(this).data("random")=="1"){
        $('#becky-content').show();
        $.ajax({
          type: "GET",
          url: Routing.generate('tds_becky_infos'),
          data:'',
          cache: false,
          success: function(data){
             $('#becky-content').find(".becky-text").html(data);     
          },
        });
      }
    });

    $("[data-close-becky]").on("click",function(){
        $("#becky-content").hide();
    });

    			
});


$(".btn-becky").on('click',function(){
  $('#becky-content').show();
  $('#becky-content').find(".becky-text").html($(this).data('info'));
});


$("[data-action='credits']").on('click',function(){ 
  
  $.ajax({
    type: "GET",
    url: Routing.generate('tds_credits'),
    data:'',
    cache: false,
    success: function(data){
       $("[data-credits]").html(data);
       $("[data-credits]").show();
       $("html, body").animate({ scrollTop: $(document).height() }, 500);    
    },
  });

});

