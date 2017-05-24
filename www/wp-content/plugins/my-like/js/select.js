/**
 * Adds a button to select all / de-select all hierarchy terms under taxonomy hierarchy metabox
 */
 

(function( $ ) {
	
	$(document).ready(function() {


		/*
		*---- Для категорий -------------------------------------------------------------------
		*/

		// Выделить все
		$( '#cat_selectall' ).click( function() {
			
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

				if ( $( this ).hasClass( 'db_cat' ) ) {
					$( this ).prop( "checked", true );
				} else {
					$( this ).prop( "checked", false );
				};

			} );

		} );
		

		/*
		*---- Для тегов -------------------------------------------------------------------
		*/

		// Выделить все
		$( '#tag_selectall' ).click( function() {
			
			$( '[name^="my_like_options[tag_"]' ).prop( "checked", true );		

		});


		// Отменить выделение
		$( '#tag_cancel' ).click( function() {
			
			$( '[name^="my_like_options[tag_"]' ).prop( "checked", false );

		});


		// Инвертировать
		$( '#tag_invert' ).click( function() {
			
			$( '[name^="my_like_options[tag_"]' ).each( function() {
				
				if ( this.checked ) {
					$( this ).prop( "checked", false );
				} else {
					$( this ).prop( "checked", true );
				};

			} );

		});


		// Восстановить
		$( '#tag_restore' ).click( function() {
			
			$( '[name^="my_like_options[tag_"]' ).each( function() {

				if ( $( this ).hasClass( 'db_tag' ) ) {
					$( this ).prop( "checked", true );
				} else {
					$( this ).prop( "checked", false );
				};

			} );

		} );

		
	
	}); // document ready end
 
}(jQuery));
