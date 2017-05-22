<?php
/*
Plugin Name: my-like
Plugin URI: http://www.robotstxt.org/
Description: Плагин добавляет возможность ставить лайки на рубрики и теги
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
    // wp_enqueue_script( 'script-jquery', plugins_url( 'js/jquery-3.2.1.min.js', __FILE__ ) );
    
    // Ставит файл CSS стилей в очередь на загрузку.
    // wp_enqueue_style( 'style-my-ads', plugins_url( 'css/style.css', __FILE__ ) );
}



// Эта зацепка запускается после создания базовой структуры меню панели администратора.
add_action( 'admin_menu', 'my_like_create_submenu' );

function my_like_create_submenu() {

	// добавление подпункта меню в меню Настройки WordPress.
	add_options_page( 
		'Страница настроек плагина my-like', 
		'Меню my-like',
		'manage_options', 
		'my_like_menu',
		'my_like_page' 
	);

	// вызываем функцию для регистрации настроек
	add_action( 'admin_init', 'my_like_register_settings' );
};



function my_like_register_settings() {
	
	// регистрируем настройки
	// Используя функцию API настроек register_setting(), вы определяете параметр,
	// который собираетесь предложить на странице параметров плагина. У страницы
	// настроек три параметра, но они будут храниться в едином массиве параметров,
	// поэтому здесь нужно зарегистрировать только одну настройку. 
	// Первый параметр — имя группы параметров. 
	// Это обязательное поле должно быть групповым именем, идентифицирующим все параметры в наборе. 
	// Второй параметр — действительное имя параметра, которое должно быть уникальным.
	// Третий параметр — функция обратного вызова для очистки значений параметров.
	register_setting( 'my_like_group', 'my_like_options' );

};



function my_like_page() {
?>
	<div class="wrap">
	<h2>Насстройка плагина my-like</h2>
	<form method="post" action="options.php">

		<!-- Внутри формы необходимо определить группу настроек, которую мы задали как 
		my_signature-settings-group при регистрации настроек. 
		Это установит связь между параметрами и их значениями. -->
		<?php settings_fields( 'my_like_group' ); ?>
		<?php $my_like_options = get_option( 'my_like_options' ); ?>
		
		<table class="form-table">
			<tr valign="top">
			<th scope="row">Для каких рубрик можно ставить лайки?</th>
			<td>
				<?php
					$categories = get_categories();
					foreach ($categories as $category) {

						if ($my_signature_options[ 'option_cat_' . $category->cat_ID ] == 'on') {
							$db_class = 'class="db_class" ';
							$db_checked = 'checked ';
						} else {
							$db_class = '';
							$db_checked = '';
						};

						$checkbox_cat = '';
						$checkbox_cat .= '<label><input type="checkbox" ';
						$checkbox_cat .= $db_class;
						$checkbox_cat .= 'name="my_signature_options[option_cat_' . $category->cat_ID . ']" ';
						$checkbox_cat .= $db_checked;
						$checkbox_cat .= '/>' . $category->cat_name . '</label><br> ';
						
						echo $checkbox_cat;
					};
				?>				
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