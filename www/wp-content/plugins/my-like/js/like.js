(function( $ ) {
	
	$(document).ready(function( $ ) {


		// Нажатие кнопки Лайк
		$( '#like-button' ).click(function() {
			null;

			// $( '#like-counter' ).text( 'Viva' );

			$.ajax({
				url: "http://wptest.com",
				success: function( data ) {
					alert( 'Your home page has ' + $(data).find('div').length + ' div elements.');
				}
			})

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
