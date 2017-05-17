<?php
/*
Plugin Name: my-counter-visit
Plugin URI: http://www.robotstxt.org/
Description: Плагин добавляет код отслеживание на все страницы сайта
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



// Первая зацепка которая срабатывает во время загрузки скриптов/стилей на страницу админ-панели
add_action( 'admin_enqueue_scripts', 'load_counter_visit_wp_admin_style' );

function load_counter_visit_wp_admin_style( $hook ) {
    
    // Ставит JavaScript файлы в очередь на загрузку.
    // wp_enqueue_script( 'script-jquery', plugins_url( 'js/jquery-3.2.1.min.js', __FILE__ ) );
    
    // Ставит файл CSS стилей в очередь на загрузку.
    wp_enqueue_style( 'style-my-counter-visit', plugins_url( 'css/style.css', __FILE__ ) );

};



// Эта зацепка запускается после создания базовой структуры меню панели администратора.
add_action( 'admin_menu', 'my_counter_visit_create_settings_submenu' );

function my_counter_visit_create_settings_submenu() {

	// добавление подпункта меню в меню Настройки WordPress.
	add_options_page( 
		'Страница настроек плагина my-counter-visit', 
		'Меню my-counter-visit',
		'manage_options', 
		'my_counter_visit_settings_menu',
		'my_counter_visit_settings_page' 
	);

	// вызываем функцию для регистрации настроек
	add_action( 'admin_init', 'my_counter_visit_register_settings' );
};



function my_counter_visit_register_settings() {
	
	// регистрируем настройки
	// Используя функцию API настроек register_setting(), вы определяете параметр,
	// который собираетесь предложить на странице параметров плагина. У страницы
	// настроек три параметра, но они будут храниться в едином массиве параметров,
	// поэтому здесь нужно зарегистрировать только одну настройку. Первый параметр —
	// имя группы параметров. Это обязательное поле должно быть групповым именем,
	// идентифицирующим все параметры в наборе. Второй параметр — действительное
	// имя параметра, которое должно быть уникальным. Третий параметр — функция
	// обратного вызова для очистки значений параметров.
	register_setting( 'my_counter_visit-settings-group', 'my_counter_visit_options','my_counter_visit_sanitize_options' );

};



function my_counter_visit_sanitize_options( $input ) {

	// функции очистки перед сохранением в базу данных

	// sanitize_text_field() - удаляет все недействительные символы UTF-8, конвертирует
	// единичные угловые скобки < в объекты HTML и удаляет все теги, разрывы строки
	// и дополнительные пробелы.
	
	// Закомментировал потомучто js-код удаляется
	// $input[ 'option_google' ] = sanitize_text_field( $input[ 'option_google' ] );
	// $input[ 'option_yandex' ] = sanitize_text_field( $input[ 'option_yandex' ] );
	
	return $input;

};



add_action( 'init', 'top_or_bottom' );

function top_or_bottom() {
	
	// Получаем настройки плагина
	$my_counter_visit_options = get_option( 'my_counter_visit_options' );
	
	// Если не выбрана галочка (выводить вверху) то выходим
	if ( $my_counter_visit_options[ 'topbottom' ] == 'option_top' ) {
		add_action('wp_head', 'my_counter_visit_write');
	};

	// Если не выбрана галочка (выводить вверху) то выходим
	if ( $my_counter_visit_options[ 'topbottom' ] == 'option_bottom' ) {
		add_action('wp_footer', 'my_counter_visit_write');
	};

};



function my_counter_visit_write() {

	// Для кодов счетчиков
	$code_counters = '';

	// Получаем настройки плагина
	$my_counter_visit_options = get_option( 'my_counter_visit_options' );
	
	// Если переменная счетчика гугл не пустая и включена
	if ( !empty( $my_counter_visit_options[ 'option_google' ] ) && 
		( $my_counter_visit_options[ 'option_check_google' ] == 'on' ) ) {
		// добавим код счетчика гугл
		$code_counters = $my_counter_visit_options[ 'option_google' ] . ' ';
	};

	// Если переменная счетчика яндекс не пустая и включена
	if ( !empty( $my_counter_visit_options[ 'option_yandex' ] ) && 
		( $my_counter_visit_options[ 'option_check_yandex' ] == 'on' ) ) {
		// добавим код счетчика яндекс
		$code_counters .= $my_counter_visit_options[ 'option_yandex' ];
	};

	echo $code_counters;
}



function my_counter_visit_settings_page() {
?>
	<div class="wrap">
	<h2>Насстройка плагина my-counter-visit</h2>
	<form method="post" action="options.php">

		<!-- Внутри формы необходимо определить группу настроек, которую мы задали как 
		my_counter_visit-settings-group при регистрации настроек. 
		Это установит связь между параметрами и их значениями. -->
		<?php settings_fields( 'my_counter_visit-settings-group' ); ?>

		<?php $my_counter_visit_options = get_option( 'my_counter_visit_options' ); ?>
		
		<table class="form-table">


			<tr valign="top">
			<th scope="row">Код аналитики Google Analytics:</th>
			<td>
				<textarea rows="12" cols="80" name="my_counter_visit_options[option_google]"><?php echo esc_html( $my_counter_visit_options[ 'option_google' ] ); ?></textarea>
				<label><input type="checkbox" name="my_counter_visit_options[option_check_google]" <?php if ($my_counter_visit_options[ 'option_check_google' ] == 'on') echo "checked"; ?> />Использовать</label>
				<p class="pre-description">Добавте сюда код аналитики от Google. Который вы получили при регистрации.</p>
			</td>
			</tr>

			<tr valign="top">
			<th scope="row">Код счетчика Яндекс.Метрика:</th>
			<td>
				<textarea rows="12" cols="80" name="my_counter_visit_options[option_yandex]"><?php echo esc_html( $my_counter_visit_options[ 'option_yandex' ] ); ?></textarea>
				<label><input type="checkbox" name="my_counter_visit_options[option_check_yandex]" <?php if ($my_counter_visit_options[ 'option_check_yandex' ] == 'on') echo "checked"; ?> />Использовать</label>
				<p class="pre-description">Сначало зарегестрируйтесь на Яндекс Метрики. Создайте там счтчик и добавьте его сюда.</p>
			</td>
			</tr>

			<tr valign="top">
			<th scope="row">Где разместить коды счетчиков?</th>
			<td>
				<label>
				<input type="radio" name="my_counter_visit_options[topbottom]" value="option_top" <?php if ($my_counter_visit_options[ 'topbottom' ] == 'option_top') echo "checked"; ?> />
				Вверху
				</label>
				<label>
				<input type="radio" name="my_counter_visit_options[topbottom]" value="option_bottom" <?php if ($my_counter_visit_options[ 'topbottom' ] == 'option_bottom') echo "checked"; ?> />
				Внизу
				</label>
				<p class="pre-description">Укажите где выходите разместить код отслеживания. Если в верху то коды отслеживания будут добавлены в тег &lt;head&gt; Так статистика будет более точной. А если внизу то коды отслеживания буду добавлены в конец тега &lt;body&gt; В данном случае загрузка страницы будет выполняться быстрее.</p>
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