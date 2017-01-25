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

                var listItem = "<div class='playlist-item clearfix'>";
                listItem += "<a href='javascript:;' class='mbs row " + playlistIndex.options.playlistOptions.itemClass + "' tabindex='1'>";
                
                listItem +="<div class='col col-sm-8 pull-left'>";
                listItem +="<div class='jp-title'>" + media.title + "</div>";
                listItem +="<div class='jp-theme'>Theme :" + media.theme + "</div>";
                listItem +="</div>";

                
                
                listItem +="<div class='col col-md-4 pull-right align-right'>";
				listItem +="<span class='jp-artist prs'><h5 class='pan man'>Joggeur</h5>" + media.artist + "</span>";
				listItem +="<span class='jp-thumb jp-thumb-artiste'><img src='"+media.thumbartiste+"'/></span>";
				listItem +="</div>";

				listItem += "</a>";
                
                listItem += "</div>";

                playlistIndexBody.find('.jp-thumb-theme').html("<img src='"+media.thumbtheme+"'/>");
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