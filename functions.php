<?php
/**
 * Mine functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Mine
 */

if ( ! function_exists( 'mine_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function mine_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Mine, use a find and replace
	 * to change 'mine' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'mine', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'mine' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );


	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'mine_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'mine_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mine_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mine_content_width', 640 );
}
add_action( 'after_setup_theme', 'mine_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mine_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'mine' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'mine_widgets_init' );


function create_widget( $name, $id, $description ) {

	register_sidebar(array(
		'name' =>  $name ,
		'id' => $id,
		'description' =>  $description ,
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));

}

create_widget( __('Header', 'mine'), 'header',  __('Displays in the Header', 'mine') );
create_widget( __('Footer Left', 'mine'), 'footer-left',  __('Displays on the left of the Footer', 'mine') );
create_widget( __('Footer Center', 'mine'), 'footer-center', __('Displays in the center of the Footer', 'mine') );
create_widget( __('Footer Right', 'mine'), 'footer-right', __('Displays on the right of the Footer', 'mine') );


/**
 * Enqueue scripts and styles.
 */
function mine_scripts() {

	wp_enqueue_style('mine_bootstrap_css', get_template_directory_uri() . '/css/bootstrap.css' );

	wp_enqueue_style('mine_font_awesome_css', get_template_directory_uri() . '/font-awesome/css/font-awesome.css' );

	wp_enqueue_style('bpl_googlefont_css', '//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,800|Roboto+Slab:400,700');

	wp_enqueue_style( 'mine-style', get_stylesheet_uri() );

	wp_enqueue_script( 'mine_bootstrap_js', get_template_directory_uri() . '/js/bootstrap.js', array('jquery'), '', true);

	wp_enqueue_script( 'mine_theme-js', get_template_directory_uri() . '/js/theme.js', array('jquery', 'mine_bootstrap_js'), '', true);

	wp_enqueue_script( 'mine-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'mine_scripts' );

// Register Custom Navigation Walker
require_once('wp_bootstrap_navwalker.php');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


function add_search_to_wp_menu ( $items, $args ) {
	if( 'primary' === $args -> theme_location ) {
	$items .= '<li class="menu-item menu-item-search">';
	$items .= '<form method="get" class="navbar-form navbar-left" role="search" action="' . home_url( '/' ) . '">' .
						'<div class="form-group">' .
						'<label class="screen-reader-text" for="s">' . __( 'Search for:', 'mine' ) . '</label>' .
						'<input class="form-control" type="text" value="' .  get_search_query() . '" name="s" id="s" />' .
						'</div>' .
						'<button type="submit" class="my-wp-search btn btn-default" id="searchsubmit" ><span class="fa fa-search"></span></button>' .
						'</form>';
	$items .= '</li>';
		}
	return $items;
	}
add_filter('wp_nav_menu_items','add_search_to_wp_menu',10,2);

function add_comment_author_to_reply_link($link, $args, $comment){
    $reply_link_text = $args['reply_text'];
    $link = str_replace($reply_link_text, '<span class="fa fa-reply"></span>' . ' ' . __( 'Reply', 'mine' ) , $link);
    return $link;
}
add_filter('comment_reply_link', 'add_comment_author_to_reply_link', 10, 3);


add_filter('get_avatar','change_avatar_css');

function change_avatar_css($class) {
$class = str_replace("class='avatar", "class='avatar media-object ", $class) ;
return $class;
}

function mine_add_editor_styles() {
    add_editor_style( 'editor-style.css' );
}
add_action( 'admin_init', 'mine_add_editor_styles' );
