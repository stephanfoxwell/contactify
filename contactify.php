<?php
/*
   Plugin Name: Contactify
   Plugin URI:
   Description:
   Version: 1.0
   Author: Stephan Smith
   Author URI: http://stephan-smith.com
   License: GPL2
   */

class Contactify {

	/**
	 * Holds the values to be used in the fields callbacks
	 */
	public $options;
	

	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}
	
	
	public function get_contactify() {
		return get_option( 'contactify' );
	}


	public $contactify_sections = array(
		array(
			'id'=>'contactify_general',
			'label' => 'General',
			'fields' => array(
				array(
					'name'=>'name',
					'label'=>'Name'
				),
				array(
					'name'=>'phone',
					'label'=>'Phone'
				),
				array(
					'name'=>'fax',
					'label'=>'Fax'
				),
				array(
					'name'=>'email',
					'label'=>'Email'
				)
			)
		),
		array(
			'id'=>'contactify_address',
			'label' => 'Location',
			'fields' => array(
				array(
					'name'=>'street_address',
					'label'=>'Street Address'
				),
				array(
					'name'=>'city',
					'label'=>'City'
				),
				array(
					'name'=>'state',
					'label'=>'State'
				),
				array(
					'name'=>'postal_code',
					'label'=>'Postal Code'
				),
				array(
					'name'=>'map',
					'label'=>'Map URL'
				)
			)
		),
		array(
			'id'=>'contactify_social',
			'label' => 'Social',
			'fields' => array(
				array(
					'name'=>'twitter',
					'label'=>'Twitter'
				),
				array(
					'name'=>'facebook',
					'label'=>'Facebook'
				),
				array(
					'name'=>'dribbble',
					'label'=>'Dribbble'
				),
				array(
					'name'=>'instagram',
					'label'=>'Instagram'
				),
				array(
					'name'=>'linkedin',
					'label'=>'LinkedIn'
				),
				array(
					'name'=>'googleplus',
					'label'=>'Google Plus'
				)
			)
		)
	);

	public function add_plugin_page() {
		add_menu_page(
			'Contactify',
			'Contactify',
			'edit_theme_options',
			'contactify',
			array( $this, 'create_admin_page' ),
			'dashicons-location',
			'70.010101010101'
		);
	}


	public function create_admin_page() {
		if ( !current_user_can( 'edit_theme_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		// Set class property
		$this->options = get_option( 'contactify' );
		?>
		<div class="wrap">
			<h2>Contactify</h2>
			<form method="post" action="options.php">
				<?php
					// This prints out all hidden setting fields
					settings_fields( 'contactify_group' );
					do_settings_sections( 'contactify' );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}

	public function page_init() {
		register_setting(
			'contactify_group', // Option group
			'contactify' // Option name
			//array( $this, 'sanitize' ) // Sanitize
		);

		foreach ( $this->contactify_sections as $section ) {
			add_settings_section(
				$section['id'], // ID
				$section['label'], // Title
				array( $this, 'print_section_info' ), // Callback
				'contactify' // Page
			);
		}
	}

	public function print_section_info( $arg ) {

		foreach ( $this->contactify_sections as $contactify_section ) {

			if ( $contactify_section['id'] == $arg['id'] ) {

				foreach ( $contactify_section['fields'] as $field ) {
					add_settings_field(
						$field['name'],
						$field['label'],
						array( $this, 'print_field' ),
						'contactify',
						$contactify_section['id'],
						$field
					);
				}
			}

		}
		return;
	}

	public function print_field( $args ) {

		$options = $this->options;

		printf(
			'<input id="' . $args['name'] . '" class="regular-text" type="text" name="contactify[' . $args['name'] . ']" value="%s" />',
			isset( $options[ $args['name'] ] ) ? esc_attr( $options[ $args['name'] ]) : ''
		);

		return;
	}


}

if( is_admin() )
	$Contactify = new Contactify();
