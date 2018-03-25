$(function() {

	var $popup=$("#popup");
	$("[data-delete]").on('click',function(){
	    $popup.show();
	    $popup.find('[data-message]').html("<span>Es-tu sûr de vouloir supprimer <strong>"+$(this).data('pseudo')+"</strong>?</span><div class='pa3 center tc w-auto mw5'><p class='ms-fushia mt0'></p><a id='btn-oui' href='' class='button mr2'>Oui</a><a id='btn-non' href='' class='button'>Non</a></div>");
	    $popup.find("#btn-oui").attr("href",$(this).data('href'));
	    return false;
	});

	$("#btn-non").on('click',function(){
	    $popup.hide();
	    return false;
	});

	
	$("[data-action='show-hotfresh-form']").on('click',function(){ 
		 $("#form-hotfresh").show(); 
		 $("#hotfresh-inner").hide(); 
	});

	$("[data-action='show-website-form']").on('click',function(){ 
		 $("#form-addliens").show(); 
	});


	$('#form-addmusictitle').submit(function(){
	  $('#loading-file').find("span").html("veuillez patienter, le fichier est en train de s'uploader");
	  $('#loading-file').addClass("bg-ms-fushia pa2 i");
	  $('#loading').show();
	  $("#tds_marathonbundle_musictitle_valider").prop("disabled",true);
	});
});
 
