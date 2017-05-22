<?php


// Первый шаг в создании виджета — использование подходящей зацепки для его
// инициализации. Такая зацепка называется widgets_init и активируется сразу после
// регистрации предустановленных виджетов WordPress:
add_action( 'widgets_init', 'register_my_widgets' );


// регистрируем виджет
function register_my_widgets() {

	// Перечесляем список виджетов которые нужно зарегистрировать
	register_widget( 'ads_widget' );

}


// Для начала необходимо расширить предустановленный
// класс WP_Widget, создав новый класс с уникальным именем:
class ads_widget extends WP_Widget {


	// код виджета
	// Теперь добавим первую функцию, имя которой должно совпадать с уникальным
	// именем класса. Функция такого типа называется конструктор (constructor):
	function ads_widget() {
		
		$widget_ops = array (
			// Имя класса — это класс CSS, который будет добавлен к тегу HTML, включающему в себя виджет
			// при его отображении. В зависимости от темы класс CSS может оказаться в <div>,
			// <aside>, <li> или каком-либо еще HTML-теге.
			'classname' => 'ads_widget_class',
			
			// Описание виджета отображается на консоли виджета ниже имени виджета.
			'description' => 'Средний рекламный блок' 
		);
		
		// Затем данные параметры передаются WP_Widget. 
		$this->WP_Widget( 'ads_widget', 'Ads Widget',	$widget_ops );
	}


	// создаем форму настроек виджета
	// Теперь создадим функцию для встраивания формы настроек виджета. Настройки
	// располагаются на администраторской странице виджета, раскрываясь для каждого
	// виджета, перечисленного на боковой панели. Класс виджета делает процесс крайне
	// простым, как показывает код ниже:
	function form( $instance ) {
		
		// Извлекаем массив опций виджета
		// Что прописано в опциях плагина то будет поумолчанию в виджете
		$my_ads_options = get_option( 'my_ads_options' );

		// Первое, что нужно сделать, — это определить значения виджета по умолчанию.
		$defaults = array (
			'title_ads' => 'My Bio',
			'flag_ads' => $my_ads_options[ 'option_middle_visible' ],
			'code_ads' => '' 
		);

		// Теперь задействуем значения объекта, то есть настройки виджета. 
		// Если виджет был только что добавлен на боковую панель, 
		// никаких настроек еще не сохранено, поэтому значения будут пустыми.
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title_ads = $instance[ 'title_ads' ];
		$flag_ads = $instance[ 'flag_ads' ];
		$code_ads = $instance[ 'code_ads' ];
		
		// Наконец, отображаем три поля формы для настроек виджета:
		// Вам не нужны теги <form> или кнопка подтверждения: это сделает для вас класс виджета.
		?>
		<p>Заголовок:
			<input  class="widefat" 
					name="<?php echo $this->get_field_name( 'title_ads' ); ?>" 
					type="text"
					value="<?php echo esc_attr( $title_ads ); ?>" /></p>
		
		<p>Показать/Скрыть:
			<input  class="widefat" 
					name="<?php echo $this->get_field_name( 'flag_ads' ); ?>"
					type="checkbox"
					value="on" <?php if ( $flag_ads == 'on' ) echo 'checked'; ?> /> </p>
		
		<p>Код рекламы:
			<textarea   class="widefat"
						name="<?php echo $this->get_field_name( 'code_ads' ); ?>">
						<?php echo esc_textarea( $code_ads ); ?></textarea></p>
		<?php
	}



	// сохраняем настройки виджета
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
		$instance[ 'title_ads' ] = sanitize_text_field( $new_instance[ 'title_ads' ] );
		
		// Записываем флажок не только в опции виджета но и плагина
		$instance[ 'flag_ads' ] = sanitize_text_field( $new_instance[ 'flag_ads' ] );
		
		$instance[ 'code_ads' ] = sanitize_text_field( $new_instance[ 'code_ads' ] );
		
		return $instance;

	}



	// отображаем виджет
	function widget( $args, $instance ) {
		
		// Извлекаем параметр $args. Эта переменная хранит некоторые
		// глобальные значения темы, такие как $before_widget и $after_widget.
		// Эти переменные могут использоваться разработчиками темы для определения
		// того, какой код будет обрамлять виджет, например произвольный тег <div>.	
		extract( $args );

		// После извлечения параметра $args отображаем переменную $before_widget.	
		// $before_title и $after_title также задаются в этой переменной. Это полезно
		// для передачи произвольных тегов HTML для включения между ними названия
		// виджета.
		echo $before_widget;
		
		$title_ads = apply_filters( 'widget_title', $instance[ 'title_ads' ] );
		// $flag_ads = ( empty( $instance[ 'flag_ads' ] ) ) ? '&nbsp;' : $instance[ 'flag_ads' ];
		$flag_ads = ( empty( $instance[ 'flag_ads' ] ) ) ? false : true;
		$code_ads = ( empty( $instance[ 'code_ads' ] ) ) ? '&nbsp;' : $instance[ 'code_ads' ];
		
		// Теперь отобразим значения виджета. Название показывается первым и помещается
		// между $before_title и $after_title. Затем покажем имя и биографию. Не забывайте
		// применять исключение для значений виджета по соображениям безопасности.
		if ( !empty( $title_ads ) ) { 
			echo $before_title . esc_html( $title_ads ) . $after_title; 
		};
		
		// echo '<p>Показать/Скрыть: ' . esc_html( $flag_ads ) . '</p>';
		if ( $flag_ads ) {
			echo '<p>Код рекламы: ' . esc_html( $code_ads ) . '</p>';
		};
		
		// Наконец, отобразим значение $after_widget.
		echo $after_widget;
	}

	// Готово! Вы только что создали пользовательский виджет для плагина, используя
	// класс виджета в WordPress. Не забывайте, что, используя новый класс виджета,
} 

