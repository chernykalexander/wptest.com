<?php
/*
Plugin Name: my-counter-visit
Plugin URI: http://www.robotstxt.org/
Description: This is my plugin counter-visit
Version: 1.0
Author: Alexander Chernykh
Author URI: http://htaccess.net.ru/
License: GPLv2
*/


// Эта функция выполняется при активации плагина во вкладке Плагины WordPress.
// Она принимает два параметра: 
// 1. путь к основному файлу плагина 
// 2. функцию для выполнения после его активации.
register_activation_hook( __FILE__ , 'my_counter_visit_install' );


function my_counter_visit_install() {
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
register_deactivation_hook( FILE , 'my_counter_visit_deactivate()' );


function my_counter_visit_deactivate() {
	// делаем то, что нужно
};


// add_filter( 'the_conten' );

// function counter_visit_
