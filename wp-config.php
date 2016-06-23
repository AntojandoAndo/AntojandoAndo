<?php
# Database Configuration
define( 'DB_NAME', 'wp_aa2016new' );
define( 'DB_USER', 'aa2016new' );
define( 'DB_PASSWORD', 'UEW6 mI 6Nvehb8 ipNG' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         'KVm-tXgf14Y~6x^!%0gxk[GDkt%Ni(JU nc.@rrD7,:jjSc-pE-?*u;yy/#Z}4>S');
define('SECURE_AUTH_KEY',  '~F4d+]$K&5Sg?@PEs9g*#:@[HHp9=Oc,Y2$2oDkx0J2*P8uC-Y8HiY>NhNffM+?I');
define('LOGGED_IN_KEY',    '4-k-3fn4%nd&j5$7v154Sz8x3-GH+2LBvP|y(uqq|s]9-l*}+!>12|Yak%*~;iO/');
define('NONCE_KEY',        '36PzHqNqR|MYF~GIi&vdu+e|ff~E6Fi~}+C11>$Wl3.CkP_)i~H8NSqaqSg]q#{i');
define('AUTH_SALT',        '?iO#;OS~jkz@#>5lk*X?+rKXDh:Eaz@`R|%UMy9D2Lg{kB(u@~a`]QrCuVkZMHnB');
define('SECURE_AUTH_SALT', '{e:I0E2 wE3YF0EO`GCo?pzq`iFaV#G6sbbz+w00v|f,UHc <I-u^=eaj%P.0P_f');
define('LOGGED_IN_SALT',   '=)_QY%sj<jv%aLUL}--iN.kk%afV@[VP!zX Q5%?_Y(Kw#nYQ>XDF9T|I+bKrphT');
define('NONCE_SALT',       'aW_],F]QF-XDjv$T|1%Q^F3;)r72`qAp=we:;XU3WP$NSaaqK#~[q|Qvn|o2/W<I');


# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'aa2016new' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', '155dcdfd1e91020e442ce77b18780a0cb106a834' );

define( 'WPE_FOOTER_HTML', "" );

define( 'WPE_CLUSTER_ID', '41268' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '45.79.168.43' );

define( 'WPE_CDN_DISABLE_ALLOWED', true );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'aa2016new.wpengine.com', 1 => 'antojandoando.com', 2 => 'www.antojandoando.com', );

$wpe_varnish_servers=array ( 0 => 'pod-41268', );

$wpe_special_ips=array ( 0 => '45.79.168.43', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( 'default' =>  array ( 0 => 'unix:///tmp/memcached.sock', ), );

#define( 'WP_SITEURL', 'http://aa2016new.wpengine.com' );

#define( 'WP_HOME', 'http://aa2016new.wpengine.com' );
define('WPLANG', 'es_ES');

# WP Engine ID


# WP Engine Settings







define('WPE_CACHE_TYPE', 'generational');
define('WP_MEMORY_LIMIT', '512M');

# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}
