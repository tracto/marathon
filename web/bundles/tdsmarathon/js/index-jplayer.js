$(document).ready(function(){
	var playlistIndexBody=$('.playlist-index-body');
    var musicTitlesArray = [];
    var titleCurrent=0;
    var list="";

    var is_medium = false;
    if( $('#device').css('display')=='none') {
      is_medium= true;       
    }

    playlistIndexBody.find('.musicTitleItem').each(function(){
              var musicTitleTitre=$(this).data('titre'); 

              var musicTitleTheme=$(this).data('theme');
              var musicTitleThumbTheme=$(this).data('thumbtheme');

              var musicTitleArtiste=$(this).data('artiste');
              var musicTitleThumbArtiste=$(this).data('thumbartiste');

              var musicTitlePath=$(this).data('path');
              var musicTitleVignetteJoggeurid=$(this).data('vignettejoggeurid');
              var musicTitleVignetteThemeid=$(this).data('vignettethemeid');

              var musicTitleItem={title:musicTitleTitre, theme:musicTitleTheme, thumbtheme:musicTitleThumbTheme, artist:musicTitleArtiste, thumbartiste:musicTitleThumbArtiste, vignettejoggeurid:musicTitleVignetteJoggeurid, vignettethemeid:musicTitleVignetteThemeid, mp3:musicTitlePath};
              musicTitlesArray.push(musicTitleItem);
              // console.log(musicTitlesArray);
    });

    createTitleItem=function(media){
                var self = this;

                var listItem = "<div class='playlist-item'>";
                    listItem += "<div href='javascript:;' class='flex flex-auto " + playlistIndex.options.playlistOptions.itemClass + "' tabindex='1'>";
                
                        listItem +="<div class='w-65 flex pr2 pr0-ns'>";
                            listItem +="<div>";                          
                                listItem +="<div class='jp-title i f7 f6-ns break-all'>" + media.title + "</div>";
                                listItem +="<div class='jp-theme break-all'><span class='f8'> Theme : </span>" + media.theme + "</div>";
                            listItem +="</div>";
                        listItem +="</div>";

                        
                
                        listItem +="<div class='flex flex-auto tr justify-end w-35'>";
				            listItem +="<div class='jp-artist pr1 pr2-ns'>";
                                listItem +="<span class='db f8'>Joggeur</span>";
                                listItem +="<span>" + media.artist + "</span>";
                            listItem +="</div>";
				            listItem +="<div class='jp-thumb jp-thumb-artiste relative thumbnail--m flex-0 overflow-hidden' data-action='show-vignette-playlistindex' data-type='joggeur' data-vignetteid='"+media.vignettejoggeurid+"'>";
                                listItem +="<img class='aspect-ratio--object' src='"+media.thumbartiste+"'/>";
                            listItem +="</div>";
				        listItem +="</div>";

				    listItem += "</div>";                
                listItem += "</div>";

                playlistIndexBody.find('.jp-thumb-theme').html("<div class='relative thumbnail--ml aspect-ratio aspect-ratio--1x1' data-action='show-vignette-playlistindex' data-type='theme' data-vignetteid='"+media.vignettethemeid+"'><img class='aspect-ratio--object' src='"+media.thumbtheme+"'/></div>");
                return listItem;
    };


    var playlistIndex=new jPlayerPlaylist({
            jPlayer: '#jp-jplayer_index',
            cssSelectorAncestor: '#jp-container_index',
            
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
                // autoPlay: autoplay,
             },
             ready: function(){
                var self = this;
                $('[data-infostitle]').empty();
                list=createTitleItem(musicTitlesArray[0]);
                $('[data-infostitle]').append(list);

             },

             play : function(){                
                $('[data-infostitle]').empty();
                titleCurrent=playlistIndex.current;
                list=createTitleItem(musicTitlesArray[titleCurrent]);
                $('[data-infostitle]').append(list);
             },

             ended: function() {
             },
            
             errorAlerts: false,
             warningAlerts: false,

          });

    if(is_medium){
        $('body').on('mouseover',"[data-action='show-vignette-playlistindex']",function(e){ 
            
          var vignetteBox = $('#playlist-vignette-box');
          vignetteBox.html("");

          var vignetteid=$(this).data("vignetteid");
          var vignettetype=$(this).data("type");
          

          var posY = $(this).offset().top;
          var posX = $(this).offset().left-140;

          if(vignettetype=="joggeur"){
            var vignetteContent=$(".vignette-box-content_joggeur[data-vignetteid='"+vignetteid+"']").html();
            console.log(vignetteid);
          }else{
            var vignetteContent=$(".vignette-box-content_theme[data-vignetteid='"+vignetteid+"']").html();
          }
         
          
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