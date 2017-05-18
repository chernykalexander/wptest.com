<?php
/*
Plugin Name: my-ads
Plugin URI: http://www.robotstxt.org/
Description: Плагин добавляет блоки рекламы
Version: 1.0
Author: Alexander Chernykh
Author URI: http://htaccess.net.ru/
License: GPLv2
*/
// Эта функция выполняется при активации плагина во вкладке Плагины WordPress.
// Она принимает два параметра: 
// 1. путь к основному файлу плагина 
// 2. функцию для выполнения после его активации.
register_activation_hook( __FILE__ , 'my_ads_install' );
function my_ads_install() {
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
register_deactivation_hook( FILE , 'my_ads_deactivate()' );
function my_ads_deactivate() {
	// делаем то, что нужно
};
// Первая зацепка которая срабатывает во время загрузки скриптов/стилей на страницу админ-панели
add_action( 'admin_enqueue_scripts', 'load_ads_script_style' );
function load_ads_script_style( $hook ) {
    
    // Ставит JavaScript файлы в очередь на загрузку.
    wp_enqueue_script( 'script-jquery', plugins_url( 'js/jquery-3.2.1.min.js', __FILE__ ) );
    
    // Ставит файл CSS стилей в очередь на загрузку.
    wp_enqueue_style( 'style-my-ads', plugins_url( 'css/style.css', __FILE__ ) );
}
// Создаем произвольное меню для плагина
// Сначала вызываем зацепку-действие admin_menu. 
// Эта зацепка запускается после создания базовой структуры меню панели администратора.
add_action( 'admin_menu', 'my_ads_create_menu' );
function my_ads_create_menu() {
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
		'Базовые настройки плагина my-ads', 
		'Плагин my-ads',
		'manage_options', 
		'my_ads_slug', 
		'my_ads_page',
		plugins_url( '/images/ingredient_16.png', __FILE__ )
	 );
	
	// создаем подпункты меню
	add_submenu_page( 
		'my_ads_slug', 
		'Управление верхним блоком рекламы',
		'Верхний блок', 
		'manage_options', 
		'my_ads_top_slug',
		'my_ads_top_page' 
	);
	add_submenu_page( 
		'my_ads_slug', 
		'Управление средним блоком рекламы',
		'Средний блок', 
		'manage_options', 
		'my_ads_middle_slug', 
		'my_ads_middle_page' 
	);
	add_submenu_page( 
		'my_ads_slug', 
		'Управление нижним блоком рекламы',
		'Нижний блок', 
		'manage_options', 
		'my_ads_bottom_slug', 
		'my_ads_bottom_page' 
	);
	// вызываем функцию для регистрации настроек
	add_action( 'admin_init', 'my_ads_register_settings' );
};
function my_ads_register_settings() {
	
	// регистрируем настройки
	// register_setting() регистрирует группу параметров
	// Первый параметр — имя группы параметров. Это обязательное поле должно быть групповым именем,
	// идентифицирующим все параметры в наборе.
	// Второй параметр — действительное имя параметра, которое должно быть уникальным. 
	// Третий параметр — функция обратного вызова для очистки значений параметров.
	register_setting( 'my_ads_group', 'my_ads_options','my_ads_sanitize_options' );
};
// функции очистки перед сохранением в базу данных
function my_ads_sanitize_options( $input ) {
	// sanitize_text_field() - удаляет все недействительные символы UTF-8, конвертирует
	// единичные угловые скобки < в объекты HTML и удаляет все теги, разрывы строки
	// и дополнительные пробелы.
	// $input[ 'option_test' ] = sanitize_text_field( $input[ 'option_test' ] );
	
	return $input;
};
function my_ads_top_page() {
?>
	<div class="wrap">
	<h2>Управление верхним блоком рекламы my_ads</h2>
	<form method="post" action="options.php">

		<?php 
			// Внутри формы необходимо определить группу настроек, которую мы задали как 
			// my_ads_group при регистрации настроек. 
			// Это установит связь между параметрами и их значениями.
			settings_fields( 'my_ads_group' );
			// Прочитаем опции плагина из таблицы wp_options
			$my_ads_options = get_option( 'my_ads_options' );
		?>
		
		<table class="form-table">
			
			<tr valign="top">

			<th scope="row">Код верхнего рекламного блока:</th>
			<td>
				<textarea rows="15" cols="80" name="my_ads_options[option_top]">
					<?php echo esc_attr( $my_ads_options[ 'option_top' ] );?>
				</textarea>
				<p class="pre-description">Верхний рекламный блок пронизывает все страницы сайта. Распологается между шапкой и контентом.</p>				
			</td>
			<td align="left" valign="left" width="55%">
				<img src="<?php echo plugins_url( 'images/ads_top.png', __FILE__ ) ?>" >
			</td>
			</tr>
			
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="Сохранить изменения" />
		</p>
	</form>
	</div>
<?php
};
function my_ads_middle_page() {
?>
	<div class="wrap">
	<h2>Управление средним блоком рекламы my_ads</h2>
	<form method="post" action="options.php">

		<?php 
			// Внутри формы необходимо определить группу настроек, которую мы задали как 
			// my_ads_group при регистрации настроек. 
			// Это установит связь между параметрами и их значениями.
			settings_fields( 'my_ads_group' );
			// Прочитаем опции плагина из таблицы wp_options
			$my_ads_options = get_option( 'my_ads_options' );
		?>
		
		<table class="form-table">
			
			<tr valign="top">
			<th scope="row">Код среднего рекламного блока:</th>
			<td>
				<textarea rows="15" cols="80" name="my_ads_options[option_middle]">
					<?php echo esc_attr( $my_ads_options[ 'option_middle' ] );?>
				</textarea>
				<p class="pre-description">Средний рекламный блок размещается на всех страницах сайта. Распологается в сайдбаре.</p>
			</td>
			<td align="left" valign="left" width="55%">
				<img src="<?php echo plugins_url( 'images/ads_middle.png', __FILE__ ) ?>" >
			</td>
			</tr>
			
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="Сохранить изменения" />
		</p>
	</form>
	</div>
<?php
};
function my_ads_bottom_page() {
?>
	<div class="wrap">
	<h2>Управление нижним блоком рекламы my_ads</h2>
	<form method="post" action="options.php">

		<?php 
			// Внутри формы необходимо определить группу настроек, которую мы задали как 
			// my_ads_group при регистрации настроек. 
			// Это установит связь между параметрами и их значениями.
			settings_fields( 'my_ads_group' );
			// Прочитаем опции плагина из таблицы wp_options
			$my_ads_options = get_option( 'my_ads_options' );
		?>
		
		<table class="form-table">
			
			<tr valign="top">
			<th scope="row">Код нижнего рекламного блока:</th>
			<td>
				<textarea rows="15" cols="80" name="my_ads_options[option_bottom]">
					<?php echo esc_attr( $my_ads_options[ 'option_bottom' ] );?>
				</textarea>
				<p class="pre-description">Нижний рекламный блок добавляется только на страницах где выводится одна запись. Распологается сразу под контентом.</p>
			</td>
			<td align="left" valign="left" width="55%">
				<img src="<?php echo plugins_url( 'images/ads_bottom.png', __FILE__ ) ?>" >
			</td>
			</tr>
			
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="Сохранить изменения" />
		</p>
	</form>
	</div>
<?php
};
function my_ads_page() {
?>
	<div class="wrap">
	<h2>Базовые настройки плагина my-ads</h2>
	<form method="post" action="options.php">

		<?php 
			// Внутри формы необходимо определить группу настроек, которую мы задали как 
			// my_ads_group при регистрации настроек. 
			// Это установит связь между параметрами и их значениями.
			settings_fields( 'my_ads_group' );
			// Прочитаем опции плагина из таблицы wp_options
			$my_ads_options = get_option( 'my_ads_options' );
		?>
		
		<table class="form-table">
			
			<tr valign="top">
			<th scope="row">Какие рекламные блоки отображать?</th>
			<td>
				<p>
				<label>
					<input  type="checkbox" 
							name="my_ads_options[option_top_visible]" 
							<?php if ( $my_ads_options[ 'option_top_visible' ] == 'on' ) echo "checked"; ?> 
					/>
					Показать <strong>верхний</strong> рекламный блок
				</label>
				</p>

				<p>
				<label>
					<input  type="checkbox" 
							name="my_ads_options[option_middle_visible]" 
							<?php if ( $my_ads_options[ 'option_middle_visible' ] == 'on' ) echo "checked"; ?> 
					/>
					Показать <strong>средний</strong> рекламный блок
				</label>
				</p>

				<p>				
				<label>
					<input  type="checkbox" 
							name="my_ads_options[option_bottom_visible]" 
							<?php if ( $my_ads_options[ 'option_bottom_visible' ] == 'on' ) echo "checked"; ?> 
					/>
					Показать <strong>нижний</strong> рекламный блок
				</label>
				</p>
			</td>
			</tr>
			
		</table>

		<p class="submit">
			<input type="submit" class="button-primary" value="Сохранить изменения" />
		</p>
		
	</form>
	</div>
<?php
};
?>