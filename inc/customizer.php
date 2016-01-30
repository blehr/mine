<?php
/**
 * Mine Theme Customizer.
 *
 * @package Mine
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mine_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->add_section(
		'navbar' ,
		array(
			'title'  => __('Navbar brand text','mine'),
	 )
	);

	$wp_customize->add_setting(
			'navbar_brand',
			array(
				'default'       	=> '',
				'transport' 			=> 'postMessage',
				'sanitize_callback' => 'sanitize_text_field'

		)
	);
	$wp_customize->add_control(
				new WP_Customize_Control(
						$wp_customize,
						'navbar_brand',
						array(
								'label'          => __( 'Text to be displayed on left side of main navigation', 'mine' ),
								'section'				=> 'navbar',
								'settings'       => 'navbar_brand',
								'type'           => 'text'
						)
		)
	);


	// Add footer copyright
    $wp_customize->add_section(
    	'copyright' ,
    	array(
    		'title'  => __('Footer Copyright','mine'),
		 )
    );
    $wp_customize->add_setting(
        'bpl_copyright',
        array(
      		'default'       	=> '',
	        'sanitize_callback' => 'sanitize_text_field'

      )
	);
	$wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'bpl_copyright',
            array(
                'label'          => __( 'Add Name', 'mine' ),
                'section'        => 'copyright',
                'settings'       => 'bpl_copyright',
                'type'           => 'text'
            )
		)
	);



}
add_action( 'customize_register', 'mine_customize_register' );






/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function mine_customize_preview_js() {
	wp_enqueue_script( 'mine_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'mine_customize_preview_js' );
