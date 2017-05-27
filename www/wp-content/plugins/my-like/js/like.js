(function( $ ) {
	
	$(document).ready(function( $ ) {


		// Нажатие кнопки Лайк
		$( '#like-button' ).click(function() {
			null;

			// $( '#like-counter' ).text( 'Viva' );

			var tag_id = $( "#tag_id" ).val();

			console.log( 'TAG ID: ' + tag_id );

			// tag_id = $( '#like-counter' ).html();

			// console.log( 'like-counter: ' + tag_id );

			$.ajax({

				url : postlike.ajax_url,
				type : 'post',

				data : {
					action : 'add_like',
					tag_id : tag_id
				},

				success: function( response ) {
					// alert(response);
					if ( ( response === parseInt( response, 10 ) ) || ( response === undefined ) ) {
						null;
						console.log( 'Ответ от сервера или пустой или не целое число!' );
					} else {
						// $( '#like-counter' ).html( response );
						console.log( 'Ответ сервера: ' + response.substring(0, response.length - 1) );
					};
					// alert( 'Your home page has ' + $(response).find('div').length + ' div elements.');
				}

			});

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
