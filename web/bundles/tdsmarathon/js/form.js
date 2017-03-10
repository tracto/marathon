$(function() {

	var $popupDelete=$("[data-popupdelete]");
	$("[data-delete]").on('click',function(){
	    $popupDelete.show();
	    $popupDelete.find('p').html("<span>Es-tu sûr de vouloir supprimer <strong>"+$(this).data('pseudo')+"</strong>?</span>");
	    $popupDelete.find("#btn-oui").attr("href",$(this).data('href'));
	    return false;
	});

	$("#btn-non").on('click',function(){
	    $popupDelete.hide();
	    return false;
	});

	
	$("[data-action='show-hotfresh-form']").on('click',function(){ 
		 $("#form-hotfresh").show(); 
		 $("#hotfresh-inner").hide(); 
	});
});
 
