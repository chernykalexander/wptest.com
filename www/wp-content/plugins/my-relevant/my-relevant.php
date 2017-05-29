<?php
/*
Plugin Name: my-relevant
Plugin URI: http://www.robotstxt.org/
Description: Плагин выводит список релевантных записей в конце одиночной записи
Version: 1.0
Author: Alexander Chernykh
Author URI: http://htaccess.net.ru/
License: GPLv2
*/



// Эта функция выполняется при активации плагина во вкладке Плагины WordPress.
// Она принимает два параметра: 
// 1. путь к основному файлу плагина 
// 2. функцию для выполнения после его активации.
register_activation_hook( __FILE__ , 'my_relevant_install' );

function my_relevant_install() {
	// Функция использует глобальную переменную $wp_version,
	// которая хранит информацию об используемой в данный момент версии WordPress
	// и подтверждает, что она не ниже версии 3.5. Сравнение версий осуществляется
	// с помощью функции РНР version_compare().	
	global $wp_version;
	
	if ( version_compare( $wp_version, '3.5', '<' ) ) {
		wp_die( 'Для данного плагина WordPress нужна версия 3.5 или выше.' );
	};

	// Если таких опций не существует то функция их создаст иначе обновит
	update_option( 'my_relevant_options', 'random' );
	update_option( 'my_relevant_count_options', '5' );
};



// Функция, которая выполняется, когда плагин деактивирован.
register_deactivation_hook( __FILE__ , 'my_relevant_deactivate' );

function my_relevant_deactivate() {
	// делаем то, что нужно
	
	// Удаляем опции релевантного контента
	delete_option( 'my_relevant_options' );
	delete_option( 'my_relevant_count_options' );
};



add_action( 'comment_form_before', 'my_relevant_display', 9 );

// Выводим рекламу под шапкой сайта на всех страницах
function my_relevant_display() {
	
	echo '<hr>';
	
	// Читаем параметры плагина из таблицы wp_options
	$r_options = get_option( 'my_relevant_options' ); 
	$r_count = get_option( 'my_relevant_count_options' );

	// Если количество записей 0 то выходим
	if ( empty( $r_count ) || ($r_count == 0 ) ) {
		return;
	};

	// Определяемся с настройками
	
	// Заголовок/Поясняющая фраза плагина
	$r_head = '';
	
	// Тип контента: берем данные похожие по категории или по автору
	$r_type = '';
	
	// Переменная будет хранить произвольный цикл WordPress
	$r_query = '';

	// Настраиваем вывод случайных записей
	if ( $r_options == 'random' ) {

		$r_head = 'Пять случайных записей из первой рубрики данной записи:';		
		$r_type = get_the_category();
		$r_query = new WP_Query( "category_name=" . $r_type[0]->name . "&showposts=" . $r_count . "&orderby=rand" );
	
	// Настраиваем вывод записей того же автора
	} elseif ( $r_options == 'author' ) {
		
		$r_head = 'Отображение дополнительных записей того же автора:';
		$r_type = get_the_author_meta( 'id' );
		$r_query = new WP_Query( "author=" . $r_type . "&showposts=" . $r_count . "&orderby=rand" );
	
	// Если параметров нет то выходим из функции
	} else {
		//
		return;
	};



	// Формируем релевантный блок
	?>

	<div class="entry-content">
		
		<h2><?php echo $r_head; ?></h2>
		<ul id="related">
			<?php
									
				while ( $r_query->have_posts() ) : $r_query->the_post();
					echo '<li><a href="'. get_permalink($post->ID) . '"> ' . get_the_title() .'</a></li>';
				endwhile;

			?>
		</ul>

	</div>

	<?php

	echo '<hr>';
};




// Эта зацепка запускается после создания базовой структуры меню панели администратора.
add_action( 'admin_menu', 'my_relevant_create_submenu' );

function my_relevant_create_submenu() {

	// добавление подпункта меню в меню Настройки WordPress.
	add_options_page( 
		// название страницы
		'Страница настроек плагина my-relevant',
		// отображаемое название подпункта
		'Меню my-relevant',
		// уровень доступа, чтобы меню могли видеть только администраторы
		'manage_options',
		// задается уникальный идентификатор подпункта
		'my_relevant_menu',
		// вызывается произвольная функция для построения страницы параметров
		'my_relevant_page' 
	);

	// вызываем функцию для регистрации настроек
	add_action( 'admin_init', 'my_relevant_register_settings' );
};



function my_relevant_register_settings() {
	
	// регистрируем настройки
	// Используя функцию register_setting(), вы определяете параметр,
	// который собираетесь предложить на странице параметров плагина. У страницы
	// настроек много параметров, но они будут храниться в едином массиве параметров,
	// поэтому здесь нужно зарегистрировать только одну настройку. 
	// Первый параметр — имя группы параметров. 
	// Это обязательное поле должно быть групповым именем, идентифицирующим все параметры в наборе. 
	// Второй параметр — действительное имя параметра, которое должно быть уникальным.
	// Третий параметр — функция обратного вызова для очистки значений параметров.
	register_setting( 'my_relevant_group', 'my_relevant_options' );
	register_setting( 'my_relevant_group', 'my_relevant_count_options' );

};



function my_relevant_page() {
?>
	<div class="wrap">
	<h2>Настройка плагина my-relevant</h2>
	<form method="post" action="options.php">

		<?php 

			settings_fields( 'my_relevant_group' );
			do_settings_sections( 'my_relevant_group' );

			$my_relevant_options = get_option( 'my_relevant_options' ); 
			$my_relevant_count_options = get_option( 'my_relevant_count_options' ); 

		?>

		<table class="form-table">
			
			<tr valign="top">
			<th scope="row">Тип релевантного контента:</th>
			<td>
				<p>
				<input type="radio" name="my_relevant_options" value="random" <?php if ($my_relevant_options == 'random') echo "checked"; ?> >
				Случайные записи из первой рубрики данной записи
				</p>
				<p>
				<input type="radio" name="my_relevant_options" value="author" <?php if ($my_relevant_options == 'author') echo "checked"; ?> >
				Отображение дополнительных записей того же автора
				</p>
			</td>
			</tr>

			<tr valign="top">
			<th scope="row">Количество релевантного контента:</th>
			<td>
				<p>
				<input type="number" size="5" name="my_relevant_count_options" min="0" max="30" value="<?php echo $my_relevant_count_options; ?>">
				Введите число от 0 до 30.
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
}