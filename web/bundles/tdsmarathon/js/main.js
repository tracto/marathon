$(function() {

  // var url = window.location.pathname;
  // var urlTheme=Routing.generate('tds_marathon_theme_view');
  // var urlJoggeur=Routing.generate('tds_marathon_joggeur_view');

  // var urlSep = url.split('/');

  // if(urlSep.length >= 6){
  //   url = url.substr(0,url.lastIndexOf('/'));
  //   if(url==urlTheme){
  //     url=Routing.generate('tds_marathon_theme_home');
  //   }else if(url==urlJoggeur){
  //     url=Routing.generate('tds_marathon_joggeur_home');
  //   }
  // }

  // $('nav.navbar-header a[href="'+ url +'"]').parent().addClass('active');

$(".nav-toggler__label").on('click',function(){
  if($("#mobile-nav").is(':checked')){
    $(".main-wrapper").css({'overflow-y':"auto"});
  }else{
    $(".main-wrapper").css({'overflow-y':"hidden"});
  }
});

$(".navbar-second").on('click',function(){
    $(this).addClass("active");  
});

//   .nav-toggler:checked ~ div nav.navbar-header ul{
//     display:block;
// }


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
});






// BECKY
$('body').on('click', '[data-action="becky-who"]', function(){
  $('#becky-wrapper').show();
  $('#becky-wrapper').find(".becky-text").html("Salut, je suis Becky, une meuf tout droit sortie d'un algorithme, comme dans Code Lisa mais en moins bien détourée. Je suis là pour vous aider à vous y retrouver dans ce site, en gros je suis la trombi du Marathon de la Semaine, quoi.");
});



$('body').on('click','[data-btn-becky-main]',function(){
      if($(this).data("random")=="1"){
        $('#becky-wrapper').show();
        $.ajax({
          type: "GET",
          url: Routing.generate('tds_becky_infos'),
          data:'',
          cache: false,
          success: function(data){
             $('#becky-wrapper').find(".becky-text").html(data);     
          },
        });
      }
});



$('body').on('click','[data-btn-becky]',function(){
  $('#becky-wrapper').show();
  $('#becky-wrapper').find(".becky-text").html($(this).data('info'));
});



$('body').on("click","[data-close-becky]",function(){
  $("#becky-wrapper").hide();
});








// CREDITS / MENTIONS LEGALES
$('body').on('click',"[data-action='credits']",function(){  
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






// FERMETURE POPUP
$('body').on('click', '[data-action="close-popup"]', function(){
    $("#popup").hide();
});



var audio = document.getElementById('audio');
// OUVERTURE / FERMETURE POPUP LOIS
$('body').on('click', '[data-action="button-lois"]', function(){
  $("#lois-wrapper").show();
  $("body").addClass('overflow-hidden');
  audio.play(); 
});


$('body').on('click', "#lois-wrapper", function(){
  $("#lois-wrapper").hide();
  $("body").removeClass('overflow-hidden');
});

