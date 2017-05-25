(function( $ ) {
	
	$(document).ready(function() {


		// Нажатие кнопки Лайк
		$( '#like-button' ).click(function() {
			null;

			$( '#like-counter' ).text( 'Viva' );

			console.log( 'like' );
		});


		
		// Нажатие кнопки Дизлайк
		$( '#dislike-button' ).click(function() {
			null;

			$( '#dislike-counter' ).text( 'Viva' );
			
			console.log( 'dislike' );
		});
	
	}); // document ready end
 
}(jQuery));
