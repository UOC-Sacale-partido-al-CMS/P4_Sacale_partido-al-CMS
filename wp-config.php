<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'id7932190_cms');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'id7932190_jdacms');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'jda1234_');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'b$A7D9=HsazFET^BhJ^M7IR&k~!%o2y_3}3PW5gQmPdB/<IvDp8E~aBI*`8IOKLV');
define('SECURE_AUTH_KEY', 'VKIVddDvX?QI@Rnf@GN=w$avQU<v;g:zgtI!jzTJRKDRc~F(%(iWp%bg|YDF<X^Q');
define('LOGGED_IN_KEY', '0mv)Bhf!,UGo~-WtTZw8oCCt^Dn7`Cz[QZp17QHY_4|=<1kjQz-g;?+8##>X$&de');
define('NONCE_KEY', '`N-XG@yE1qpdW=RQK_t..0%CAgaHc@xm<#X;$ cZs>Ak)Z=UL|P~~wg>bes.8ZSn');
define('AUTH_SALT', 'j3:7MHJ;YV=ZbXIq[@~CwJq(~/M.rfCAZhv[h-KA5=i3yrSC0w/JkI&O^pXnX`cO');
define('SECURE_AUTH_SALT', ';tc$Q~~FXSS1%c[w@Xj}EJ2B@YjL.7jKOPrQXhbi<k+zZ4{EQ,6/kfu1c-mu?n1&');
define('LOGGED_IN_SALT', '(hT#4-p4D rmrPl2HRMKT-s,16x=oIy`P<!Rd!9Gz(@TRmxT>$Jv`EYo8Q=#!c9i');
define('NONCE_SALT', '$}Q.<hH4?u1-ML`$>lJm+.L3U6Lm#Il,Thom=dbT#qj Gl+nC%hN km>c@ QII,l');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix = 'wpstg0_';//  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

