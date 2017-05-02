$(document).ready(function(){
	var playlistIndexBody=$('.playlist-index-body');
    var musicTitlesArray = [];
    var titleCurrent=0;
    var list="";

    playlistIndexBody.find('.musicTitleItem').each(function(){
              var musicTitleTitre=$(this).data('titre'); 

              var musicTitleTheme=$(this).data('theme');
              var musicTitleThumbTheme=$(this).data('thumbtheme');

              var musicTitleArtiste=$(this).data('artiste');
              var musicTitleThumbArtiste=$(this).data('thumbartiste');

              var musicTitlePath=$(this).data('path');

              var musicTitleItem={title:musicTitleTitre, theme:musicTitleTheme, thumbtheme:musicTitleThumbTheme, artist:musicTitleArtiste, thumbartiste:musicTitleThumbArtiste, mp3:musicTitlePath};
              musicTitlesArray.push(musicTitleItem);
              // console.log(musicTitlesArray);
    });

    createTitleItem=function(media){
                var self = this;

                var listItem = "<div class='playlist-item'>";
                    listItem += "<div href='javascript:;' class='flex flex-auto flex-wrap" + playlistIndex.options.playlistOptions.itemClass + "' tabindex='1'>";
                
                        listItem +="<div class='w-70 flex'>";
                            listItem +="<div>";                          
                                listItem +="<div class='jp-title i f6'>" + media.title + "</div>";
                                listItem +="<div class='jp-theme'><span class='f8'> Theme : </span>" + media.theme + "</div>";
                            listItem +="</div>";
                        listItem +="</div>";

                        
                
                        listItem +="<div class='flex flex-auto tr justify-end'>";
				            listItem +="<div class='jp-artist pr2'>";
                                listItem +="<span class='db f8'>Joggeur</span>";
                                listItem +="<span>" + media.artist + "</span>";
                            listItem +="</div>";
				            listItem +="<div class='jp-thumb jp-thumb-artiste relative thumbnail--m flex-0 overflow-hidden'>";
                                listItem +="<img class='aspect-ratio--object' src='"+media.thumbartiste+"'/>";
                            listItem +="</div>";
				        listItem +="</div>";

				    listItem += "</div>";                
                listItem += "</div>";

                playlistIndexBody.find('.jp-thumb-theme').html("<div class='relative thumbnail--ml aspect-ratio aspect-ratio--1x1'><img class='aspect-ratio--object' src='"+media.thumbtheme+"'/></div>");
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
});