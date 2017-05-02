

var remainingHearts=parseInt($('.joggeurDonneur-pointsInit').html());
var remainingHearts=6;
var givenHearts=0;



$(".heartPoints-icons").on('click','.heartPoints-icon', function() {
	

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
		$("#popup").show();		
	}

	$('.joggeurDonneur-points').html(remainingHearts);
	$('#task_remainingPoints').val(remainingHearts);
	
});


