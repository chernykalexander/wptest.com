/**
 * Adds a button to select all / de-select all hierarchy terms under taxonomy hierarchy metabox
 */
 

(function( $ ) {
	
	$(document).ready(function() {
		
		// Выделить все
		$( '#cat_selectall' ).click( function() {
			
			// проверяет, соответствие хотя бы одного div-элемента заданному селектору
			// if (  $( "input" ).is( '[name^="my_like_options[cat_"]' ) ) {
			// 	console.log( 'Test' );
			// };

			$( '[name^="my_like_options[cat_"]' ).prop( "checked", true );		

		});


		// Отменить выделение
		$( '#cat_cancel' ).click( function() {
			
			$( '[name^="my_like_options[cat_"]' ).prop( "checked", false );

		});


		// Инвертировать
		$( '#cat_invert' ).click( function() {
			
			$( '[name^="my_like_options[cat_"]' ).each( function() {
				
				if ( this.checked ) {
					$( this ).prop( "checked", false );
				} else {
					$( this ).prop( "checked", true );
				};

			} );

		});


		// Восстановить
		$( '#cat_restore' ).click( function() {
			
			$( '[name^="my_like_options[cat_"]' ).each( function() {
				// 
			};
			
		} );

		
	
	}); // document ready end
 
}(jQuery));
