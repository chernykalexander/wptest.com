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
	register_setting( 'my_ads_group', 'my_ads_options', 'my_ads_sanitize_options' );
	
};



// функции очистки перед сохранением в базу данных
function my_ads_sanitize_options( $input ) {
	
	// sanitize_text_field() - удаляет все недействительные символы UTF-8, конвертирует
	// единичные угловые скобки < в объекты HTML и удаляет все теги, разрывы строки
	// и дополнительные пробелы.
	// $input[ 'option_test' ] = sanitize_text_field( $input[ 'option_test' ] );
	
	return $input;
};



// Поскольку мы цепляемся к хуку pre_get_search_form который встечается дважды на странице
// Првый раз в шапке, второй в подвале. Чтобы не выводить рекламный код дважды
// используем статическую переменную класса в качестве флага
class TAdsOne {

    public static $flag_top = false;

 };

add_action( 'pre_get_search_form', 'ads_display_top' );

// Выводим рекламу под шапкой сайта на всех страницах
function ads_display_top() {
	
	// Проверяем галочку (Показывать верхний рекламный блок)
	$check_top = get_option( 'my_ads_options' );
	if ( $check_top[ 'option_top_visible' ] != 'on' ) {
		return;
	};

	// Если верхняя реклама уже выводилась
	// то второй раз выводить ее не нужно
	if ( ! TAdsOne::$flag_top ) {
		echo '<div align="center">';
		echo get_option( 'my_ads_top' );
		echo '</div>';
	};

	TAdsOne::$flag_top = true;
};



function my_ads_top_page() {
?>
	<div class="wrap">
	<h2>Управление верхним блоком рекламы my_ads</h2>
	<form method="post" action="options.php">

		<?php wp_nonce_field( 'update-options' ); ?>

		<table class="form-table">
			
			<tr valign="top">
			<th scope="row">Код верхнего рекламного блока:</th>
			<td>
				<textarea rows="15" cols="80" name="my_ads_top">
					<?php echo get_option( 'my_ads_top' ); ?>
				</textarea>
				<p class="pre-description">Верхний рекламный блок пронизывает все страницы сайта. Распологается между шапкой и контентом.</p>				
			</td>
			<td align="left" valign="left" width="55%">
				<img src="<?php echo plugins_url( 'images/ads_top.png', __FILE__ ) ?>" >
			</td>
			</tr>
			
		</table>
		
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="my_ads_top" />

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

		<?php wp_nonce_field( 'update-options' ); ?>
		
		<table class="form-table">
			
			<tr valign="top">
			<th scope="row">Код среднего рекламного блока:</th>
			<td>
				<textarea rows="15" cols="80" name="my_ads_middle">
					<?php echo get_option( 'my_ads_middle' ); ?>
				</textarea>
				<p class="pre-description">Средний рекламный блок размещается на всех страницах сайта. Распологается в сайдбаре.</p>
			</td>
			<td align="left" valign="left" width="55%">
				<img src="<?php echo plugins_url( 'images/ads_middle.png', __FILE__ ) ?>" >
			</td>
			</tr>
			
		</table>

		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="my_ads_middle" />

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

		<?php wp_nonce_field( 'update-options' ); ?>
		
		<table class="form-table">
			
			<tr valign="top">
			<th scope="row">Код нижнего рекламного блока:</th>
			<td>
				<textarea rows="15" cols="80" name="my_ads_bottom">
					<?php echo get_option( 'my_ads_bottom' ); ?>
				</textarea>
				<p class="pre-description">Нижний рекламный блок добавляется только на страницах где выводится одна запись. Распологается сразу под контентом.</p>
			</td>
			<td align="left" valign="left" width="55%">
				<img src="<?php echo plugins_url( 'images/ads_bottom.png', __FILE__ ) ?>" >
			</td>
			</tr>
			
		</table>

		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="my_ads_bottom" />

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
							value="on" 
							<?php if ( $my_ads_options[ 'option_top_visible' ] == 'on' ) echo 'checked'; ?> 
					/>
					Показать <strong>верхний</strong> рекламный блок
				</label>
				</p>

				<p>
				<label>
					<input  type="checkbox" 
							name="my_ads_options[option_middle_visible]"
							value="on"
							<?php if ( $my_ads_options[ 'option_middle_visible' ] == 'on' ) echo 'checked'; ?> 
					/>
					Показать <strong>средний</strong> рекламный блок
				</label>
				</p>

				<p>				
				<label>
					<input  type="checkbox" 
							name="my_ads_options[option_bottom_visible]"
							value="on"
							<?php if ( $my_ads_options[ 'option_bottom_visible' ] == 'on' ) echo 'checked'; ?> 
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



// ---------------------------------------------------------------------------------------------------------
// Виджеты
// ---------------------------------------------------------------------------------------------------------



// class my_ads_widget extends WP_Widget {
	
// 	function my_ads_widget() {
// 		// код виджета
// 	};
	
// 	function form($instance) {
// 		// форма виджета в Консоли
// 	};

// 	function update($new_instance, $old_instance) {
// 		// сохраняем значения виджета
// 	};
	
// 	function widget($args, $instance) {
// 		// отображаем виджет
// 	};

// };


// Первый шаг в создании виджета — использование подходящей зацепки для его
// инициализации. Такая зацепка называется widgets_init и активируется сразу после
// регистрации предустановленных виджетов WordPress:
add_action( 'widgets_init', 'my_ads_register_widgets' );

function my_ads_register_widgets() {

	// Перечесляем список виджетов которые нужно зарегистрировать
	register_widget( 'my_ads_widget' );

};




// ----------------------------------------------------------------------------------------------

// Для начала необходимо расширить предустановленный
// класс WP_Widget, создав новый класс с уникальным именем:
class my_ads_widget extends WP_Widget {


	// Теперь добавим первую функцию, имя которой должно совпадать с уникальным
	// именем класса. Функция такого типа называется конструктор (constructor):
	function my_ads_widget() {
		
		$widget_ops = array (
			// Имя класса — это класс CSS, который будет добавлен к тегу HTML, включающему в себя виджет
			// при его отображении. В зависимости от темы класс CSS может оказаться в <div>,
			// <aside>, <li> или каком-либо еще HTML-теге.
			'classname' => 'my_ads_widget_class',

			// Описание виджета отображается на консоли виджета ниже имени виджета.
			'description' => 'Средний рекламный блок' 
		);

		// Затем данные параметры передаются WP_Widget. 
		$this->WP_Widget( 'my_ads_widget', 'Bio Widget', $widget_ops );
	}


	// создаем форму настроек виджета
	// Теперь создадим функцию для встраивания формы настроек виджета. Настройки
	// располагаются на администраторской странице виджета, раскрываясь для каждого
	// виджета, перечисленного на боковой панели. Класс виджета делает процесс крайне
	// простым, как показывает код ниже:
	function form( $instance ) {
		
		// Первое, что нужно сделать, — это определить значения виджета по умолчанию.
		$defaults = array(
			'ads_title' => 'Реклама',
			'ads_flag' => 'on',
			'ads_code' => '' );
		
		// Теперь задействуем значения объекта, то есть настройки виджета. 
		// Если виджет был только что добавлен на боковую панель, 
		// никаких настроек еще не сохранено, поэтому значения будут пустыми. 		
		$instance = wp_parse_args( (array) $instance, $defaults );
		$ads_title = $instance[ 'ads_title' ];
		$ads_flag = $instance[ 'ads_flag' ];
		$ads_code = $instance[ 'ads_code' ];
				
		// Наконец, отображаем три поля формы для настроек виджета:
		// Вам не нужны теги <form> или кнопка подтверждения: это сделает для вас класс виджета.
		?>

		<p>Заголовок:
			<input class="widefat"
			name="<?php echo $this->get_field_name( 'ads_title' ); ?>"
			type="text" value="<?php echo esc_attr( $ads_title ); ?>" /></p>

		<p>Показывать рекламу:
			<input class="widefat"
			name="<?php echo $this->get_field_name( 'ads_flag' ); ?>"			
			type="text" value="<?php echo esc_attr( $ads_flag ); ?>" /></p>
		
		<p>Код:
			<textarea class="widefat"
			name="<?php echo $this->get_field_name( 'ads_code' ); ?>" >
			<?php echo esc_textarea( $ads_code ); ?></textareax></p>
		
		<?php
	
	}
		

	// Затем нужно сохранить настройки виджета, используя
	// функцию класса виджета update():
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance[ 'ads_title' ] = sanitize_text_field( $new_instance[ 'ads_title' ] );
		$instance[ 'ads_flag' ] = sanitize_text_field( $new_instance[ 'ads_flag' ] );
		// $instance[ 'ads_code' ] = sanitize_text_field( $new_instance[ 'ads_code' ] );
		$instance[ 'ads_code' ] = $new_instance[ 'ads_code' ];
		
		return $instance;

	}


	// Последняя функция в классе занимается отображением виджета:
	function widget( $args, $instance ) {

		// Первое, что мы делаем, — извлекаем параметр $args. Эта переменная хранит некоторые
		// глобальные значения темы, такие как $before_widget и $after_widget.
		// Эти переменные могут использоваться разработчиками темы для определения
		// того, какой код будет обрамлять виджет, например произвольный тег <div>.	
		extract( $args );

		// После извлечения параметра $args отображаем переменную $before_widget.	
		// $before_title и $after_title также задаются в этой переменной. Это полезно
		// для передачи произвольных тегов HTML для включения между ними названия
		// виджета.
		echo $before_widget;
		
		$ads_title = apply_filters( 'widget_title', $instance[ 'ads_title' ] );
		$ads_flag = ( empty( $instance[ 'ads_flag' ] ) ) ? '&nbsp;' : $instance[ 'ads_flag' ];
		$ads_code = ( empty( $instance[ 'ads_code' ] ) ) ? '&nbsp;' : $instance[ 'ads_code' ];

		// Теперь отобразим значения виджета. Название показывается первым и помещается
		// между $before_title и $after_title. Затем покажем имя и биографию. Не забывайте
		// применять исключение для значений виджета по соображениям безопасности.		
		if ( !empty( $ads_title ) ) { 
			echo $before_title . esc_html( $ads_title ) . $after_title; 
		};
		
		echo '<p>ads_name: ' . esc_html( $ads_name ) . '</p>';
		echo '<p>ads_code: ' . esc_html( $ads_code ) . '</p>';
		
		// Наконец, отобразим значение $after_widget.
		echo $after_widget;
		
	}


	// Готово! Вы только что создали пользовательский виджет для плагина, используя
	// класс виджета в WordPress. Не забывайте, что, используя новый класс виджета,
};
	
	


	
	