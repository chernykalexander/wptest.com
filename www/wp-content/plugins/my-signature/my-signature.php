<?php
/*
Plugin Name: my-signature
Plugin URI: http://www.robotstxt.org/
Description: Плагин добавляет подпись в конец статьи или в конец заголовка
Version: 1.0
Author: Alexander Chernykh
Author URI: http://htaccess.net.ru/
License: GPLv2
*/


// Эта функция выполняется при активации плагина во вкладке Плагины WordPress.
// Она принимает два параметра: 
// 1. путь к основному файлу плагина 
// 2. функцию для выполнения после его активации.
register_activation_hook( __FILE__ , 'my_signature_install' );


function my_signature_install() {
	// делаем то, что нужно
	
	// Функция использует глобальную переменную $wp_version,
	// которая хранит информацию об используемой в данный момент версии WordPress
	// и подтверждает, что она не ниже версии 3.5. Сравнение версий осуществляется
	// с помощью функции РНР version_compare().	
	global $wp_version;
	
	if ( version_compare( $wp_version, '3.5', '<' ) ) {
		wp_die( 'Для данного плагина WordPress нужна версия 3.5 или выше.' );
	};
};


// Функция, которая выполняется, когда плагин деактивирован.
register_deactivation_hook( FILE , 'my_signature_deactivate()' );


function my_signature_deactivate() {
	// делаем то, что нужно
};


// зацепка-фильтр $the_title применяется к названию записи или страницы до отображения.
add_filter( 'the_title', 'my_signature_title' );

function my_signature_title( $title ) {
	
	// условный тег is_single() для проверки того, что текст для подписки добавляется только на страницу одиночной записи
	if( is_single() ) {
		$title .= ' - By Example.com';	
	};
	
	return $title;
};


// зацепок-фильтро the_content — применяется к контенту записи или страницы до отображения.
add_filter ( 'the_content', 'my_signature_content' );

function my_signature_content( $content ) {
	
	// условный тег is_single() для проверки того, что текст для подписки добавляется только на страницу одиночной записи
	if( is_single() ) {

		// Переменная $content хранит все содержимое записи или страницы, поэтому,
		// добавляя текст для подписки, вы помещаете его в нижнюю часть контента записи.		
		$content .= '<p>Моя подпись</p>';

	};
	
	return $content;

};