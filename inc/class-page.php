<?php
/**
 * Class OptionsPage
 *
 * Creates an options page that allows the user to enter their sharpsprings secret and other info
 */

namespace RelativeMarketing\Options;

class Page {

	protected $sections;

	protected $page_arguments;
	/**
	 * constructor.
	 */
	public function __construct(array $page_arguments, array $sections) {
		$this->page_arguments = $page_arguments;
		$this->sections = $sections;
	}

	public function render() {	
		add_action( 'admin_menu', [ $this, 'add_admin_menu_item' ] );
		add_action( 'admin_init', [ $this, 'register_all_option_page_sections' ] );
	}

	/**
	 * Adds menu page 
	 */
	public function add_admin_menu_item() {
		add_submenu_page( $this->get_parent_page(), $this->get_page_title(), $this->get_menu_title(), $this->get_required_capability(), $this->get_menu_slug(), [ $this, 'option_page_callback' ] );
	}

	/**
	 * The main option page content
	 */
	public function option_page_callback() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page' ) );
		}
		?>
		<div class="wrap">
			<div class="page-information">
				<h1><?php echo $this->get_page_title(); ?></h1>
				<p><?php echo $this->get_page_description(); ?></p>
			</div>
			<form action='options.php' method='post'>
				<?php
				// foreach ($this->sections as $id => $section) {
					// var_dump($id); die();
					settings_fields( $this->get_menu_slug() . '-options' );
				// }
				/**
				 * Do all the sections for the current page
				 */
				do_settings_sections( $this->get_menu_slug() );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Sets up the sections and adds the correct fields to the right sections
	 */
	public function register_all_option_page_sections() {
		// Used quite a lot in the method stored for perf and convenience
		$page_id = $this->get_menu_slug();

		/**
		 * Run through the provided sections and set them up
		 */
		foreach ($this->sections as $section_id => $section) {
			/**
			 * Add the section
			 */
			add_settings_section( $section_id, $section['title'], null, $page_id );

			foreach ( $section['fields'] as $option_id => $field ) {
				/**
				 * Add a new field specifying the options id, it's heading, how it should be output on the page, the
				 * section it belongs to.
				 */
				add_settings_field( $option_id, $field['heading'], [ $this, 'add_option' ], $page_id, $section_id, [$option_id, $field['type'], $section_id] );

				/**
				 * Then actually add that setting to the current section
				 */
				register_setting( $page_id . '-options', $option_id );
			}
		}
	}

	public function add_option( array $options ) {
		//This is a callback so wordpress passes the arguments as an array
		$option_id  = $options[0];
		$field_type = $options[1];
		$section    = $options[2];
		// Used later to output associated info
		$option_data = $this->sections[$section]['fields'][ $option_id ];

		// Generate the output for the field
		echo Form\Generate::field( $option_id, $field_type, get_option( $option_id ) );

		// Check to see if a description has been provided and if it has output that description
		if ( array_key_exists( 'desc', $option_data ) ) {
			echo '<br/><span>' . sanitize_text_field( $option_data['desc'] ) . '</span>';
		}
	}

	/**
	 * Getters and Setters
	 */

	/**
	 * Will return the value of given argument for the page.
	 * 
	 * Although this may seem redundant as the methods that use this method could
	 * access the page_arguments property directly I may want to change how the page
	 * arguments are stored in the future. If the way the page arguments are accessed
	 * changes I will just have to update this one method 👍
	 */
	public function get_page_argument( string $arg ) {
		return $this->page_arguments[ $arg ];
	}

	public function get_parent_page() {
		return $this->get_page_argument( 'parent' );
	}
	
	public function get_page_title() {
		return $this->get_page_argument( 'page_title' );
	}

	public function get_page_description() {
		return $this->get_page_argument( 'page_description' );
	}

	public function get_menu_title() {
		return $this->get_page_argument( 'menu_title' );
	}
	
	public function get_menu_slug() {
		return $this->get_page_argument( 'menu_slug' );
	}
	
	public function get_required_capability() {
		return $this->get_page_argument( 'capability' );
	}
}