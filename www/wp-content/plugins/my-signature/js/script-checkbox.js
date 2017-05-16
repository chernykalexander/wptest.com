
$( document ).ready( function() {



	// Кнопка (инвертировать) чекбоксы
	$( '#checkbox-invert' ).click( function() {

		var checked = $( "input[name^='my_signature_options[option_cat_']" ).filter( ':checked' );
		var unchecked = $( "input[name^='my_signature_options[option_cat_']" ).filter( ':not(:checked)' );
		
		checked.each( 
			function() { 
				$( this ).prop( 'checked', false ); 
			} 
		);
		
		unchecked.each( 
			function() { 
				$( this ).prop( 'checked', true ); 
			} 
		);


		// https://www.abeautifulsite.net/jquery-checkboxes-select-all-select-none-and-invert-selection
		// var checked = $('.createrightcheckbox').filter(":checked");
		// var unchecked = $('.createrightcheckbox').filter(":not(:checked)");
		// checked.each(function(){$(this).prop('checked',false);});
		// unchecked.each(function(){$(this).prop('checked',true);});

		// $( "input[name^='my_signature_options[option_cat_'] " ).each( 
		// 	function() {
		//     	$(this).attr('checked', !$(this).attr('checked'));
		// 	}
		// );

		// console.log( 'checkbox-invert' );
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

		
		$( "input[name^='my_signature_options[option_cat_'] " ).each( 
			function() {

				if ( $( this ).hasClass( 'db_class' ) ) {
					$( this ).prop( 'checked', true );
				} else {
					$( this ).prop( 'checked', false );
				};

				// $( this ).attr( 'checked', $( this ).attr( '.db_class' ) );
			}
		);


		// $( "input[name^='my_signature_options[option_cat_'] " ).each( 
		// 	function() {
		//     	$( this ).attr( 'checked', !$( this ).attr( 'checked' ) );
		// 	}
		// );
		
		// $( "input[name^='my_signature_options[option_cat_'] " ).prop( 'checked', false );
		// $( "input[name^='my_signature_options[option_cat_'] " ).prop( 'checked', false );
		
		console.log( 'checkbox-restore-selectall' );
	} );


	
} );