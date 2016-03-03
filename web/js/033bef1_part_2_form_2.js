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

