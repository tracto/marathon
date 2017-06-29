$(document).ready(function(){
      var playlistBody=$('.playlist-body');
      var i=1;
      var musicTitlesArray = [];
      var titleCurrent=0;


      var is_medium = false;
      if( $('#device').css('display')=='none') {
          is_medium= true;       
      }



      if(is_medium){
        var refHeight=$("[data-id='col-gauche']").height()-$("[data-id='playlist-header']").height()-$("[data-id='playlist-footer']").height();
        $(".jp-playlist-inner").css({"max-height":refHeight+"px"}); 

        $('body').on('mouseover',"[data-action='theme-nav-infos-hover']",function(){
            $(this).find("[data-action='show']").addClass("active");
        });

        $('body').on('mouseout',"[data-action='theme-nav-infos-hover']",function(){
            $(this).find("[data-action='show']").removeClass("active");
        });

      }




      playlistBody.each(function(){
          var $btnPlaylistPrecedent=$(".playlist-nav-prec");
          var idPlaylistRedirect=$btnPlaylistPrecedent.data('id');

          var musicTitlesArray = [];
          var autoplay=$("[data-autoplay]").data('autoplay');

          $(this).find('.musicTitleItem').each(function(index){
              var musicTitleIndex=index+1;
              var musicTitleTitre=$(this).data('titre');
              var musicTitleArtiste=$(this).data('artiste');
              var musicTitlePath=$(this).data('path');
              var musicTitleThumb=$(this).data('thumb');
              var musicTitleVignetteid=$(this).data('vignetteid');
      
              var musicTitleItem={index:musicTitleIndex, title:musicTitleTitre, artist:musicTitleArtiste, mp3:musicTitlePath, thumb:musicTitleThumb, vignetteid:musicTitleVignetteid};
              musicTitlesArray.push(musicTitleItem);
          });

          $(this).find('.jp-jplayer').attr('id','jp-jplayer_'+i);
          $(this).find('.jp-container').attr('id','jp-container_'+i);

          var playlistType=$('.playlist-wrapper').data('type');


          createTitle=function(media){
              var self = this;

              var listItem = "<div class='playlist-title align-center'>";
              listItem +="<span class='jp-title'>" + media.title + "</span>";
              listItem +="<span class='jp-artist'> ( " + media.artist  + " ) </span>";        
              listItem += "</div>";

              
              return listItem;
          };

          createListItem=function(media){
                var self = this;
                var listItem = "<li class='playlist-item-wrapper pa2'>";
                  listItem += "<div class='w-100 flex flex-auto no-underline near-black'>";

                    listItem += "<span class='jp-thumb thumbnail--m flex-item pointer' data-action='show-vignette' data-type='"+playlistType+"' data-vignetteid='"+media.vignetteid+"'>";
                      listItem += "<img class='db w-100' src='"+media.thumb+"'/>";
                    listItem += "</span>";

                    listItem += "<a href='db w-100' class='flex flex-auto no-underline " + myPlaylist.options.playlistOptions.itemClass + "' data-tabindex='"+media.index+"'>";
                        listItem += "<div class='jp-title-artist flex-item pl2'>";
                            listItem += "<span class='jp-title db w-100'>" + media.title + "</span>";
                            listItem +=(media.artist ?" <span class='jp-artist db w-100 i dark-gray '>" + media.artist + "</span>" : "");
                        listItem += "</div>";
                        
                        listItem += "<span class='jp-numero flex-item f2 v-mid pr1 ms-bleu-bright'>" + media.index + "</span>";
                      listItem += "</a>";
                      listItem += "<div>";
                    listItem += "</li>";             
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
                autoplay: false,
                // autoPlay: autoplay,
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
                $("[data-tabindex='1']").parent().parent().addClass("jp-playlist-current");

                $('[data-infostitle]').empty();
                title=createTitle(musicTitlesArray[0]);
                $('[data-infostitle]').append(title);

             },

             play : function(){
                titleCurrent=myPlaylist.current;
                $('[data-infostitle]').empty();
                title=createTitle(musicTitlesArray[titleCurrent]);
                $('[data-infostitle]').append(title);
             },

             ended: function() {
                
             },
            
             errorAlerts: false,
             warningAlerts: false,

          });


          i++;
      });
// });



  if(is_medium){
    $('body').on('mouseover',"[data-action='show-vignette']",function(e){ 
        
      var vignetteBox = $('#playlist-vignette-box');
      vignetteBox.html("");

      var vignetteid=$(this).data("vignetteid");

      var posY = $(this).offset().top;
      var posX = $(this).offset().left-140;

      var vignetteContent=$(".vignette-box-content[data-vignetteid='"+vignetteid+"']").html();
      console.log(vignetteid);
      var type=$(this).data("type");
      
      vignetteBox.show();
      vignetteBox.css({"top":posY+"px","left":posX+"px"});
      vignetteBox.html(vignetteContent);

    });


    $('body').on('mouseover',"#playlist-vignette-box",function(e){
      var vignetteBox = $('#playlist-vignette-box');
      vignetteBox.show();
    });

    $('body').on('mouseout',"[data-action='show-vignette']",function(e){ 
      var vignetteBox = $('#playlist-vignette-box');
      vignetteBox.hide();

    });

    $('body').on('mouseout',"#playlist-vignette-box",function(e){ 
      var vignetteBox = $('#playlist-vignette-box');
      vignetteBox.hide();
    });
  }

});

