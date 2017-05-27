<?php
	// если функция uninstall/delete вызвана не из WordPress, выходим
	if( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
		exit();

	// удаляем параметр из таблицы параметров
	// delete_option( '' );
	
	// удаляем все другие параметры, произвольные таблицы и файлы