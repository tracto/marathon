$("[data-btnbecky]").on('click',function(){
	$(".form-becky").show();
});


var remainingHearts=parseInt($('.joggeurDonneur-pointsInit').html());
var remainingHearts=6;
var givenHearts=0;



$(".heartPoints-icons").on('click','.heartPoints-icon', function() {
	
	console.log("yo heart click");

	var remainingHearts=parseInt($('.joggeurDonneur-pointsInit').html());
	var givenHearts=0;

	$(this).addClass('is-active').siblings().removeClass('is-active');

	
	$('[data-idJoggeur]').each(function(){		
		if($(this).find('.is-active').length) {
			var joggeurId;
			joggeurId=$(this).attr('data-idJoggeur');
			
			joggeurHearts=parseInt($(this).find('.is-active').data('heart'));

			givenHearts += joggeurHearts;
			var joggeurReceveur=$(this).find('.joggeurReceveur-points');
			joggeurReceveur.html($(this).find('.is-active').data('heart'));			
			$('.tag-idJoggeur[value="'+parseInt(joggeurId)+'"]').siblings().val(joggeurHearts);
		}	
	});

	$('#popup').attr("data-id",$(this).parent().attr('data-idJoggeur'));

	if(givenHearts<=remainingHearts){		
		remainingHearts = remainingHearts - givenHearts;
		
	}else{
		joggeurId=$("#popup").attr('data-id');
		$(this).removeClass('is-active');
		remainingHearts = remainingHearts - (givenHearts - $('.tag-idJoggeur[value="'+joggeurId+'"]').siblings().val());		
		$('.tag-idJoggeur[value="'+joggeurId+'"]').siblings().val(0);				
		$("#popup").find('.popup-wrapper').show();		
	}

	$('.joggeurDonneur-points').html(remainingHearts);
	$('#task_remainingPoints').val(remainingHearts);
	
});


$("#popup").find('.popup-close').on('click',function(){
	$("#popup").find('.popup-wrapper').hide();
})
var $popupDelete=$("#popup-delete-user");
        $(".btn-user-delete").on('click',function(){
            $popupDelete.show();
            $popupDelete.find('p').html("<span>Es-tu sûr de vouloir supprimer <strong>"+$(this).data('pseudo')+"</strong>?</span>");
            $popupDelete.find(".btn-delete-oui").attr("href",$(this).data('href'));
        });

        $(".btn-delete-non").on('click',function(){
            $popupDelete.hide();
            return false;
});


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
            complete: function () {
            }
        });
       }
    });

    $("[data-close-becky]").on("click",function(){
        $("#becky-content").hide();
    });

    			
});


$(".btn-becky").on('click',function(){
  console.log($(this).data('id'));
  $('#becky-content').show();
  $('#becky-content').find(".becky-text").html($(this).data('info'));
});


$(document).ready(function(){
      var playlistBody=$('.playlist-body');
      var i=1;
      var musicTitlesArray = [];


      playlistBody.each(function(){
          var $btnPlaylistPrecedent=$(".playlist-nav-prec");
          var idPlaylistRedirect=$btnPlaylistPrecedent.data('id');

          var musicTitlesArray = [];
          var autoplay=$("[data-autoplay]").data('autoplay');

          $(this).find('.musicTitleItem').each(function(){
              var musicTitleTitre=$(this).data('titre');
              var musicTitleArtiste=$(this).data('artiste');
              var musicTitlePath=$(this).data('path');
              var musicTitleThumb=$(this).data('thumb');
              var musicTitleItem={title:musicTitleTitre, artist:musicTitleArtiste, mp3:musicTitlePath, thumb:musicTitleThumb};
              musicTitlesArray.push(musicTitleItem);
              
          });

          $(this).find('.jp-jplayer').attr('id','jp-jplayer_'+i);
          $(this).find('.jp-container').attr('id','jp-container_'+i);

          var playlistType=$('.playlist-wrapper').data('type');
          createListItem=function(media){
                var self = this;

                // Wrap the <li> contents in a <div>
                var listItem = "<li class='playlist-item'><span>"
                // Create remove control
                listItem += "<a href='javascript:;' class='" + myPlaylist.options.playlistOptions.itemClass + "' tabindex='1'><span class='jp-thumb'><img src='"+media.thumb+"'/></span><span class='jp-title-artist'><span class='jp-title'>" + media.title + "</span>" + (media.artist ?
                " <span class='jp-artist'>" + media.artist + "</span></span>" : "") + "</a>";
                listItem += "</span></li>";
                return listItem;
          };

          
          var myPlaylist=new jPlayerPlaylist({
            jPlayer: '#jp-jplayer_'+i,
            cssSelectorAncestor: '#jp-container_'+i,
            
          }, musicTitlesArray, 
          {
             swfPath: '../dist/jplayer',
             solution: 'html, flash',
             // supplied: 'mp3',
             preload: 'metadata',

             volume: 0.8,
             muted: false,
             backgroundColor: '#000000',
             cssSelector: {
                play: '.jp-play',
                pause: '.jp-pause',
                stop: '.jp-stop',
                seekBar: '.jp-seek-bar',
                playBar: '.jp-play-bar',
                mute: '.jp-mute',
                unmute: '.jp-unmute',
                volumeBar: '.jp-volume-bar',
                volumeBarValue: '.jp-volume-bar-value',
                volumeMax: '.jp-volume-max',
                playbackRateBar: '.jp-playback-rate-bar',
                playbackRateBarValue: '.jp-playback-rate-bar-value',
                currentTime: '.jp-current-time',
                duration: '.jp-duration',
                title: '.jp-title',
                fullScreen: '.jp-full-screen',
                restoreScreen: '.jp-restore-screen',
                repeat: '.jp-repeat',
                repeatOff: '.jp-repeat-off',
                gui: '.jp-gui',
                noSolution: '.jp-no-solution'
             },
             playlistOptions: {
                autoPlay: autoplay,
             },
             ready: function(){
                var self = this;
                $(".jp-playlist-inner ul").empty();
                var listItem="";

                $.each(musicTitlesArray, function (i,musicTitle){
                  var list=createListItem(musicTitle);
                  listItem += list;
                });                

                $(".jp-playlist-inner ul").append(listItem);

             },

             ended: function() {
               if(typeof idPlaylistRedirect !== 'undefined' && idPlaylistRedirect != 0){
                    window.open(Routing.generate('tds_marathon_'+playlistType+'_view_js', { 'id': idPlaylistRedirect }), "_self");
                }
             },
            
             errorAlerts: false,
             warningAlerts: false,

          });


          i++;
      });
});

$('.fos_comment_comment_btn_view_replies').on('click',function(){
	$(this).parent().parent().find('.fos_comment_comment_replies').show();
	return false;
});