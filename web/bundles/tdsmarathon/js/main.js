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
  $("[data-container-becky]").hide();
});





// JE VEUX JOGGER
$('body').on('click',"[data-action='jeveuxjogger']",function(){  
  $.ajax({
    type: "GET",
    url: Routing.generate('tds_jeveuxjogger'),
    data:'',
    cache: false,
    success: function(data){
       $("[data-jeveuxjogger]").html(data);
       $("[data-jeveuxjogger]").show();
       $("html, body").animate({ scrollTop: $(document).height() }, 500); 


       $("#participate-form").submit(function(e){
          var formData = new FormData(this);
          $.ajax({
              url: Routing.generate('tds_jeveuxjogger'),
              type: 'POST',
              data:  formData,
              mimeType:"multipart/form-data",
              contentType: false,
              cache: false,
              processData:false,
              success: function(data, textStatus, jqXHR)
              {
                var webPath='../../web/bundles/tdsmarathon/images/groupeProfs.jpg';
                var reponse = "<div class='pa3 pb5 center tc bg--pierre f7'>";
                      reponse += "<h2 class='metal-mania f1'>Je veux Jogger</h2>";
                      reponse += "<img class='w-60' src='"+webPath+"'/>";
    
                      reponse += "<div class='pv3'>";
                      reponse += "<h3>Demande Envoyée!</h3>";
                      reponse += "Elle sera examinée par un groupe de 26 profs d'EPS.<br/>";
                      reponse += "Si jamais ta demande est acceptée, tu recevras un mail de confirmation d'inscription</div>";
                      reponse += "<div class='tc'>";
                        reponse += "<a class='button button-action' href=''>";
                          reponse += "<svg class='icon icon--s icon-checkmark'><use xlink:href='#icon-checkmark'></use></svg>";  
                        reponse += "<span>ok</span></a>";
                      reponse += "</div>";
                reponse += "</div>";
                $("[data-jeveuxjogger]").html(reponse);
              },
              error: function(jqXHR, textStatus, errorThrown)
              {
              }
          });
          e.preventDefault(); //Prevent Default action.
          e.unbind();
        });   
    },
  });
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

