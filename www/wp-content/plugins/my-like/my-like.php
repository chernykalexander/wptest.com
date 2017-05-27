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
register_activation_hook( __FILE__ , 'my_like_install' );

function my_like_install() {
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
register_deactivation_hook( __FILE__ , 'my_like_deactivate' );

function my_like_deactivate() {
	// делаем то, что нужно
	// удаляем параметр из таблицы параметров
	delete_option( 'my_like_options' );
};



// Первая зацепка которая срабатывает во время загрузки скриптов/стилей на страницу админ-панели
add_action( 'admin_enqueue_scripts', 'load_like_script_style' );

function load_like_script_style( $hook ) {
    
    // Ставит JavaScript файлы в очередь на загрузку.
    wp_enqueue_script( 'script-jquery', plugins_url( 'js/jquery-3.2.1.min.js', __FILE__ ) );
    
    // Подключаем скрипт с кнопками: Выделить все, Отменить, Инвертировать, Восстановить
    wp_enqueue_script( 'script-select', plugins_url( 'js/select.js', __FILE__ ) );
    
    // Ставит файл CSS стилей в очередь на загрузку.
    // wp_enqueue_style( 'style-my-like', plugins_url( 'css/style.css', __FILE__ ) );
};



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



// Зацепка подключает скрипты и стили во фронт-энде
add_action( 'wp_enqueue_scripts', 'push_like' );

function push_like() {
	
	// Ставит JQuery файлы в очередь на загрузку.
	wp_enqueue_script( 'script-jquery', plugins_url( 'js/jquery-3.2.1.min.js', __FILE__ ) );

	// Подключаем основной файл стилей для фронт-енда.
	wp_enqueue_style( 'style.css', plugins_url( 'css/style.css', __FILE__ ) );

	null;
	// Подключает скрипт если он небыл подключен ранее
	
	// $handle (string) (required) 
	// Название скрипта (рабочее название). Строка в нижнем регистре.
	
	// $src (string) (optional) 
	// Путь до скрипта от корневой директории WordPress. Например: "/wp-includes/js/scriptaculous/scriptaculous.js". 
	// Этот параметр необходим только в случае, если WordPress еще не знает об этом скрипте.
	wp_enqueue_script( 'like.js', plugins_url( 'js/like.js', __FILE__ ) );

	// Добавляет дополнительные данные перед указанным скриптом, который должен быть в очереди на вывод.
	wp_localize_script( 'like.js', 'postlike', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
};



// wp_ajax_(myname) на этот крючок можно вешать собственные обработчики AJAX-запросов
// wp_ajax_nopriv_(myname) то же самое только для не авторизованных пользователей
add_action( 'wp_ajax_nopriv_add_like', 'add_like' );
add_action( 'wp_ajax_add_like', 'add_like' );

function add_like() {
	// $love = get_post_meta( $_POST['post_id'], 'post_love', true );
	// $love++;
	// if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
	// 	update_post_meta( $_POST['post_id'], 'post_love', $love );
	// 	echo $love;
	// }
	// die();


	// $love = get_post_meta( $_POST['post_id'], 'post_love', true );
	// $love++;
	// if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
	// 	update_post_meta( $_POST['post_id'], 'post_love', $love );
	// 	echo $love;
	// }
	// die();


	// Получает значение указанного мета поля элемента таксономии (рубрики, метки, и т.д.)
	// $term_id элемента таксономии.
	// $key ключ мета поля.
	// $single в каком виде возвращать значение.
	// false - массив значений
	// true - единственное значение (первое из массива, если значений несколько)
	// $like = get_term_meta( $_POST[ 'tag_id' ], 'key_like', true );
	// $like++;

	// Обновляет мета значение term. Если его не существует то создаст.
	// update_term_meta( $_POST[ 'tag_id' ], 'key_like', $like );

	// Отправляем результат вызвашей аякс-функции
	// $like = '';
	$like = $_POST[ 'tag_id' ];
	// $like = substr( $like, 0, 1 );
	echo $like;

	// echo get_term_meta( 88, 'test_meta_field', true );

};



add_action( 'loop_start', 'display_like' );

function display_like() {
	
	if ( is_tag() ) {
		// echo "<b>Print loop</b>";
		// echo "<br>My data: <br>";
		// echo get_queried_object()->term_id;
		// echo "<br>End<br>";

		// Получаем id текущего тега
		$tag_id = get_queried_object()->term_id;

		// Скрытое поле с id тега
		echo "<input type=\"hidden\" id=\"tag_id\" value=\"$tag_id\">";

		// Пути к картинкам
		$path_like = plugins_url( 'images/like.jpg', __FILE__ );
		$path_dislike = plugins_url( 'images/dislike.jpg', __FILE__ );

		echo "<div class=\"wrap\">";
		
		// Кнопка лайк
		echo "<button id=\"like-button\">";
		echo "<img src=$path_like alt=\"Лайк\" >";
		echo "</button>";
		echo "<div id=\"like-counter\" class=\"inline-element\"> ";
		echo "0";
		echo "</div>";
		echo "лайков ";
		
		// Разделитель
		echo "<div class=\"inline-element\"> ";
		// echo "&nbsp;";
		echo "</div>";

		// Кнопка дизлайк
		echo "<button id=\"dislike-button\">";
		echo "<img src=$path_dislike alt=\"Дизлайк\" >";
		echo "</button>";
		echo "<div id=\"dislike-counter\" class=\"inline-element\"> ";
		echo "0";
		echo "</div>";
		echo "дизлайков ";
		
		echo "</div>";
		// echo plugins_url( 'images/dislike.jpg', __FILE__ );

	};

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
				$list_cat = '';
				$db_class = '';
				$db_checked = '';
				
				foreach ($categories as $category) {

					if ( $my_like_options[ 'cat_' . $category->cat_ID ] == 'on' ) {
						$db_class = 'class="db_cat" ';
						$db_checked = 'checked ';
					} else {
						$db_class = '';
						$db_checked = '';
					};

					$checkbox_cat = '';
					$checkbox_cat .= '<label><input type="checkbox" ';
					$checkbox_cat .= $db_class;
					$checkbox_cat .= 'name="my_like_options[cat_' . $category->cat_ID . ']" ';
					$checkbox_cat .= $db_checked;
					$checkbox_cat .= '/>' . $category->cat_name . '</label><br> ';
					
					$list_cat .= $checkbox_cat;
				};
					
				echo $list_cat;
				?>

			</td>
			</tr>

			<tr><th></th>
			<td>
				<input type="button" id="cat_selectall" value="Выделить все" />
				<input type="button" id="cat_cancel" value="Отменить выделение" />
				<input type="button" id="cat_invert" value="Инвертировать" />
				<input type="button" id="cat_restore" value="Восстановить" />
			</td>
			</tr>

			<hr>

			<tr valign="top">
			<th scope="row">Каким тегам нужен лайк?</th>
			<td>
				<?php
					
				$tags = get_tags();
				$list_tag = '';
				$db_class = '';
				$db_checked = '';
				
				foreach ($tags as $tag) {
					
					if ( $my_like_options[ 'tag_' . $tag->term_id ] == 'on' ) {
						$db_class = 'class="db_tag" ';
						$db_checked = 'checked ';
					} else {
						$db_class = '';
						$db_checked = '';
					};

					$checkbox_tag = '';
					$checkbox_tag .= '<label><input type="checkbox" ';
					$checkbox_tag .= $db_class;
					$checkbox_tag .= 'name="my_like_options[tag_' . $tag->term_id . ']" ';
					$checkbox_tag .= $db_checked;
					$checkbox_tag .= '/>' . $tag->name . '</label><br> ';
					
					$list_tag .= $checkbox_tag;
				};
					
				echo $list_tag;
				?>				
			</td>
			</tr>

			<tr><th></th>
			<td>
				<input type="button" id="tag_selectall" value="Выделить все" />
				<input type="button" id="tag_cancel" value="Отменить выделение" />
				<input type="button" id="tag_invert" value="Инвертировать" />
				<input type="button" id="tag_restore" value="Восстановить" />
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