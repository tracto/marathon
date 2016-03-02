$('.fos_comment_comment_btn_view_replies').on('click',function(){
	$(this).parent().parent().find('.fos_comment_comment_replies').show();
	return false;
});