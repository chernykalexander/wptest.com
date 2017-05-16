
$( document ).ready( function() {



	// Кнопка (инвертировать) чекбоксы
	$( '#checkbox-invert' ).click( function() {

		

		console.log( 'checkbox-invert' );
	} );



	// Кнопка (Выделить все) чекбоксы
	$( '#checkbox-selectall' ).click( function() {
		
		// [name ^= value] элементы, у которых значение атрибута name начинается с value
		$( "input[name^='my_signature_options[option_cat_'] " ).prop( 'checked', true );

		// console.log( 'checkbox-selectall' );

	} );



	// Кнопка (Отменить выделение) чекбоксы
	$( '#checkbox-cancel-selectall' ).click( function() {

		$( "input[name^='my_signature_options[option_cat_'] " ).prop( 'checked', false );

		// console.log( 'checkbox-cancel-selectall' );
	} );



	// Кнопка (Восстановить выделение) чекбоксы
	$( '#checkbox-restore-selectall' ).click( function() {
		console.log( 'checkbox-restore-selectall' );
	} );


	
} );