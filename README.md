# Relative Options

Relative options makes it easy to add options pages to your plugin.

## Usage

First install the plugin which will allow you to use it in your theme.

Once installed, you need to instantiate the `RelativeMarketing\Options\Page` class with your desired options then call the `render` method to setup the page. You should do this inside the `plugins_loaded` action to ensure the class is available.

```php
add_action( 'plugins_loaded', __NAMESPACE__ . '\\your_plugin_setup_options' );

function your_plugin_setup_options() {
	if ( ! class_exists( '\RelativeMarketing\Options\Page' ) ) {
		// The plugin is not installed/activated, you will need to notify the user
		return;
	}

	// The following page arguments exist and are required
	$page_arguments = array(
		// Support doesn't currently exist for parent page creation
		'parent'     => 'options-general.php',
		// The title of your page
		'page_title' => 'Some page title',
		// The description of your page
		'page_description' => 'Add a description here',
		// Text that will appear in the wordpress sidebar
		'menu_title' => 'Example',
		// Slug unique to your plugin
		'menu_slug'  => 'some-unique-slug',
		// Capability a user must have to see the page
		'capability' => 'manage_options',
	);

	$sections = array(
		// Each item of the sections array is a new section 
		'unique_id_for_your_section' => array(
			'title'  => 'Title of the section',
			// Fields are the individual options
			'fields' => array(
				// An option
				'example_option_id' => array(
					// Heading and description will be output with the field
					'heading' => 'Example option name',
					'desc'    => 'Some description',
					'type'    => 'input',
				),
			),
		)
	);

	// Now call the constructor and render the page
	$page = new \RelativeMarketing\Options\Page( $page_arguments, $sections );
	$page->render();
}
```

Given the above you will now see a newly created page in your settings. To access saved options you can use `get_option( 'example_option_id' );`

