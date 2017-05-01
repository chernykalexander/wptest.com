<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'wptest');

/** Имя пользователя MySQL */
define('DB_USER', 'wptest');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', 'wptest');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'rt7DCK|v1^;HR@v.N%(wwK(j;c}Xyz_{#B$kEEETcf(6stQr7(fWM}e$D(yydR;C');
define('SECURE_AUTH_KEY',  'mLSwSR;iAn3X$4#>R+]Ix#[$E&!rF43$RTaP#)It98;~y|@]d hz;&%=9Y&%w6Wm');
define('LOGGED_IN_KEY',    'u0XI0q=N&GN}laIb+jZCx@aFt.C)wmaX~cdDb[v(Cy.^y&27 2~}TzIgRN-Y}$ho');
define('NONCE_KEY',        '&}$vezA/8ue9hP_]`!N>Wvev<H+TCz^:A-$!h7RIaqILwQ7]#`dTf</$):Mms|hI');
define('AUTH_SALT',        'LD&ia(nOE`.(O#mDhgIb%-qCO&>i.~^d.6u~/50~,`=7@}z30gp_lP/FnXPNdNN~');
define('SECURE_AUTH_SALT', 'HR t}N} nLUNIFlNDGi{ykKk<Or(IoKA2hfP>3wu2G!63M$a4!dt7=~{JRpdCLjN');
define('LOGGED_IN_SALT',   'hy[jp>$Kcp4kDT{WhapW^V9c_Z;Ku;E{t+rWhC]Y1_d@Th~ON&1LY43Nvi|@5k=Y');
define('NONCE_SALT',       'TEK<FPsj`K6/r]d!q>@uO|<:W|FPlk(J G<vI8L:}<v{}cOX*o=[x04zwTuEIuUa');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 * 
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');

/* define( 'WP_POST_REVISIONS', 0 ); */

function my_revisions_to_keep( $revisions ) {
    return 0;
}
add_filter( 'wp_revisions_to_keep', 'my_revisions_to_keep' );