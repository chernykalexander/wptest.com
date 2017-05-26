<?php
	echo "My viva";

	include 'taxonomy.php';

	$term_id  = 88;
	$meta_key = 'test_meta_field';

	add_term_meta( $term_id, $meta_key, 'Привет мир', true );

	// Выводим
	echo get_term_meta( $term_id, $meta_key, true ); // выведет 'Привет мир'

	// пробуем добавить еще одно поле с тем же ключом
	$done = add_term_meta( $term_id, $meta_key, 'Привет мир 2', true );
	var_dump( $done ); // bool(false)