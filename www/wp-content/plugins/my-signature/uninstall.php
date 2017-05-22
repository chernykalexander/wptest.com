<?php
	// если функция uninstall/delete вызвана не из WordPress, выходим
	if( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
		exit();

	// удаляем параметр из таблицы параметров
	
	// удаляем опции плагина
	delete_option( 'my_signature_options' );
	
	// удаляем опции виджета
	delete_option( 'widget_ads_widget' );
	
	// удаляем все другие параметры, произвольные таблицы и файлы