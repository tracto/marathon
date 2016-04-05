$(document).ready(function(){
      var playlistBody=$('.playlist-body');
      var i=1;
      var musicTitlesArray = [];
      var titleCurrent=0;


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
              console.log(musicTitlesArray);
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

             play : function(){
                titleCurrent=myPlaylist.current;
             },

             ended: function() {
                if(typeof idPlaylistRedirect !== 'undefined' && idPlaylistRedirect != 0){
                  if(titleCurrent == (Object.keys(myPlaylist.playlist).length)-1){
                      window.open(Routing.generate('tds_marathon_'+playlistType+'_view_js', { 'id': idPlaylistRedirect }), "_self");
                  }
                }
             },
            
             errorAlerts: false,
             warningAlerts: false,

          });


          i++;
      });
});