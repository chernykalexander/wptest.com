(function( $ ) {
	
	$(document).ready(function( $ ) {


		// Нажатие кнопки Лайк
		$( '#like-button' ).click(function() {

			// Получаем текущий id тега
			var tag_id = $( "#tag_id" ).val();
			console.log( 'TAG ID: ' + tag_id );

			$.ajax({

				url : postlike.ajax_url,
				type : 'post',

				// Эти данные отправим на сервер
				// action - имя зацепки
				// tag_id - тег который меняем
				data : {
					action : 'add_like',
					tag_id : tag_id
				},

				success: function( response ) {

					// Параметр response в конце имеет лишний нолик (непонятна причина)
					// Поэтому удаляем последний символ
					var trim_response = response.substring( 0, response.length - 1 );

					if ( ( response === parseInt( response, 10 ) ) || ( response === undefined ) ) {
						console.log( 'Ответ от сервера или пустой или не целое число!' );
					} else {
						$( '#like-counter' ).html( trim_response );
						console.log( 'Ответ сервера: ' + trim_response );
					};

				} // end success: function 

			}); // end $.ajax

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
