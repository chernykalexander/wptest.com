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



/*
// Создаем произвольное меню для плагина
// Сначала вызываем зацепку-действие admin_menu. 
// Эта зацепка запускается после создания базовой структуры меню панели администратора.
add_action( 'admin_menu', 'my_signature_create_menu' );

function my_signature_create_menu() {

	// Аргументы для add_menu_page
	// pagetitle — текст, используемый для названия HTML (между тегами <title>).
	// menu_title — текст, используемый как имя пункта меню в консоли.
	// capability — минимальные права пользователя, позволяющие видеть меню.
	// menu_slug — уникальное слаг-имя меню.
	// function — отображает контент страницы настроек меню.
	// icon_url — путь к произвольной иконке меню (по умолчанию images/generic.png).
	// position — порядок отображения в меню. По умолчанию будет отображаться в конце структуры меню.

	// создаем новое меню верхнего уровня
	add_menu_page( 
		'Страница плагина my-signature', 
		'Плагин my-signature',
		'manage_options', 
		'my_signature_slug', 
		'my_signature_main_plugin_page',
		plugins_url( '/images/icon_my_signature.png', __FILE__ )
	 );

	
	// создаем подпункты меню: настройка и поддержка
	add_submenu_page( 
		'my_signature_slug', 
		'Страница настроек плагина my-signature',
		'Настройки', 
		'manage_options', 
		'my_signature_settings_slug',
		'my_signature_settings_page' 
	);

	add_submenu_page( 
		'my_signature_slug', 
		'Страница техподдержки плагина my-signature',
		'Техподдержка', 
		'manage_options', 
		'my_signature_support_slug', 
		'my_signature_support_page' 
	);
};

*/


// Эта зацепка запускается после создания базовой структуры меню панели администратора.
add_action( 'admin_menu', 'my_signature_create_settings_submenu' );

function my_signature_create_settings_submenu() {

	// добавление подпункта меню в меню Настройки WordPress.
	add_options_page( 
		'Страница настроек плагина my-signature', 
		'Меню my-signature',
		'manage_options', 
		'my_signature_settings_menu',
		'my_signature_settings_page' 
	);

	// вызываем функцию для регистрации настроек
	add_action( 'admin_init', 'my_signature_register_settings' );
};



function my_signature_register_settings() {
	
	// регистрируем настройки
	// Используя функцию API настроек register_setting(), вы определяете параметр,
	// который собираетесь предложить на странице параметров плагина. У страницы
	// настроек три параметра, но они будут храниться в едином массиве параметров,
	// поэтому здесь нужно зарегистрировать только одну настройку. Первый параметр —
	// имя группы параметров. Это обязательное поле должно быть групповым именем,
	// идентифицирующим все параметры в наборе. Второй параметр — действительное
	// имя параметра, которое должно быть уникальным. Третий параметр — функция
	// обратного вызова для очистки значений параметров.
	register_setting( 'my_signature-settings-group', 'my_signature_options','my_signature_sanitize_options' );

};



function my_signature_sanitize_options( $input ) {

	$input[ 'option_name' ] = sanitize_text_field( $input[ 'option_name' ] );
	$input[ 'option_email' ] = sanitize_email( $input[ 'option_email' ] );
	$input[ 'option_url' ] = esc_url( $input[ 'option_url' ] );
	return $input;

};



function my_signature_settings_page() {
?>
	<div class="wrap">
	<h2>Насстройка плагина my-signature</h2>
	<form method="post" action="options.php">

		<!-- Внутри формы необходимо определить группу настроек, которую мы задали как 
		my_signature-settings-group при регистрации настроек. 
		Это установит связь между параметрами и их значениями. -->
		<?php settings_fields( 'my_signature-settings-group' ); ?>
		<?php $my_signature_options = get_option( 'my_signature_options' ); ?>
		
		<table class="form-table">
			<tr valign="top">
			<th scope="row">Подпись для заголовка:</th>
			<td><input type="text" name="my_signature_options[option_name]" value="<?php echo esc_attr( $my_signature_options['option_name'] );?>" /></td>
			</tr>
			<tr valign="top">
			<th scope="row">Email</th>
			<td><input type="text" name="my_signature_options[option_email]" value="<?php echo esc_attr( $my_signature_options['option_email'] ); ?>" /></td>
			</tr>
			<tr valign="top">
			<th scope="row">URL</th>
			<td><input type="text" name="my_signature_options[option_url]" value="<?php echo esc_url( $my_signature_options['option_url'] ); ?>" />
			</td>
			</tr>
			</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="Сохранить изменения" />
		</p>
	</form>
	</div>
<?php
} 
?>