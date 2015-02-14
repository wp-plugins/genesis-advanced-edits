<?php
/**
 * Plugin Name: Genesis Advanced Edits
 * Description: Genesis Advanced Edits allows you to make edits to a range of content areas in your Genesis theme. It also allows you to configure your Genesis site in ways beyond those available in the standard Genesis settings panel, such as enabling custom post type archives settings. This is NOT an official StudioPress plugin.
 * Author: Carlo Manf
 * Plugin URI: http://carlomanf.id.au/products/genesis-advanced-edits/
 * Author URI: http://carlomanf.id.au/products/genesis-advanced-edits/
 * Version: 1.2
 */

//* Load the callback function
if ( !function_exists( 'cm_settings_field_callback' ) )
	require_once( 'lib/cm-settings-callbacks.php' );

//* Register settings page
add_action( 'admin_menu', 'genesis_advanced_edits_register_settings_page' );
function genesis_advanced_edits_register_settings_page() {
	add_submenu_page(
		'genesis', //* parent slug
		'Genesis Advanced Edits', //* title of page
		'Advanced Edits', //* menu text
		'manage_options', //* capability to view the page
		'genesis_advanced_edits', //* ID
		function() { //* callback genesis_advanced_edits_options_page_callback
			echo '<div class="wrap">';
			printf( '<h2>%s</h2>', 'Genesis Advanced Edits' );
			echo '<form method="post" action="options.php">';
			echo '<p>Genesis Advanced Edits allows you to make edits to a range of content areas in your Genesis theme. It also allows you to configure your Genesis site in ways beyond those available in the standard Genesis settings panel, such as enabling custom post type archives settings. This is NOT an official StudioPress plugin.</p>';
			submit_button();
			settings_fields( 'genesis_advanced_edits' );
			do_settings_sections( 'genesis_advanced_edits' );
			submit_button();
			echo '</form></div>';
		}
	);
}

//* Initialise settings section
add_action( 'admin_init', 'genesis_advanced_edits_initialise_settings_section' );
function genesis_advanced_edits_initialise_settings_section() {
	if ( false === get_option( 'genesis_advanced_edits' ) )
		add_option( 'genesis_advanced_edits' );

	//* will be used for several fields
	$public_post_types = get_post_types( array( 'public' => true ) );

	add_settings_section(
		'genesis_advanced_edits_entry_header', //* ID
		'Entry Header', //* title to be displayed
		function() { //* callback genesis_advanced_edits_entry_header
			echo '<p>By default, the entry header displays the post date, the author and the number of comments. It displays in single view and in archives, but not for Pages. You can customise the criteria for displaying the entry header and customise its content.</p>';
		},
		'genesis_advanced_edits' //* settings page to add to
	);

	add_settings_field(
		'entry_header_display', //* ID
		'Display Entry Header', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_entry_header', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'type' => 'checkbox',
			'fieldset' => $public_post_types,
			'prefix' => 'entry_header_display_',
			'labelset' => $public_post_types
		)
	);

	add_settings_field(
		'entry_header_archive', //* ID
		'<label for="genesis_advanced_edits[entry_header_archive]">Remove from Archives</label>', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_entry_header', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'entry_header_archive',
			'type' => 'checkbox',
			'label' => 'Tick this box to remove the entry header from archives, search results and blog pages.'
		)
	);

	add_settings_field(
		'entry_header', //* ID
		'Replace Entry Header Text?', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_entry_header', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'entry_header',
			'type' => 'checkbox',
			'label' => 'Tick this box to replace the default Genesis entry header text with your own version.',
			'description' => 'If you leave this box unticked, whatever you enter in the field below will be ignored and the default text will be used.'
		)
	);

	add_settings_field(
		'entry_header_text', //* ID
		'<label for="genesis_advanced_edits[entry_header_text]">Custom Entry Header Text</label>', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_entry_header', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'entry_header_text',
			'type' => 'text',
			'description' => '<strong>Default:</strong> ' . '[post_date] ' . __( 'by', 'genesis' ) . ' [post_author_posts_link] [post_comments] [post_edit]',
			'filters' => array( 'esc_attr' )
		)
	);

	add_settings_field(
		'entry_header_pt', //* ID
		'Post Type Specific?', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_entry_header', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'entry_header_pt',
			'type' => 'checkbox',
			'label' => 'Tick this box to use unique entry header text for each post type.',
			'description' => 'If you tick this box, all the fields below will be used. If you leave it unticked, they will be ignored.'
		)
	);

	add_settings_field(
		'entry_header_pt_text', //* ID
		'Post Type Specific Custom Entry Header Text', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_entry_header', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'type' => 'text',
			'filters' => array( 'esc_attr' ),
			'fieldset' => $public_post_types,
			'prefix' => 'entry_header_pt_text_',
			'labelset' => $public_post_types
		)
	);

	add_settings_section(
		'genesis_advanced_edits_entry_footer', //* ID
		'Entry Footer', //* title to be displayed
		function() { //* callback genesis_advanced_edits_entry_footer
			echo '<p>By default, the entry footer displays the categories and tags for a post. It displays in single view and in archives, but not for Pages. You can customise the criteria for displaying the entry footer and customise its content.</p>';
		},
		'genesis_advanced_edits' //* settings page to add to
	);

	add_settings_field(
		'entry_footer_display', //* ID
		'Display Entry Footer', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_entry_footer', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'type' => 'checkbox',
			'fieldset' => $public_post_types,
			'prefix' => 'entry_footer_display_',
			'labelset' => $public_post_types
		)
	);

	add_settings_field(
		'entry_footer_archive', //* ID
		'<label for="genesis_advanced_edits[entry_footer_archive]">Remove from Archives</label>', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_entry_footer', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'entry_footer_archive',
			'type' => 'checkbox',
			'label' => 'Tick this box to remove the entry footer from archives, search results and blog pages.'
		)
	);

	add_settings_field(
		'entry_footer', //* ID
		'Replace Entry Footer Text?', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_entry_footer', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'entry_footer',
			'type' => 'checkbox',
			'label' => 'Tick this box to replace the default Genesis entry footer text with your own version.',
			'description' => 'If you leave this box unticked, whatever you enter in the field below will be ignored and the default text will be used.'
		)
	);

	add_settings_field(
		'entry_footer_text', //* ID
		'<label for="genesis_advanced_edits[entry_footer_text]">Custom Entry Footer Text</label>', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_entry_footer', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'entry_footer_text',
			'type' => 'text',
			'description' => '<strong>Default:</strong> ' . '[post_categories] [post_tags]',
			'filters' => array( 'esc_attr' )
		)
	);

	add_settings_field(
		'entry_footer_pt', //* ID
		'Post Type Specific?', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_entry_footer', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'entry_footer_pt',
			'type' => 'checkbox',
			'label' => 'Tick this box to use unique entry footer text for each post type.',
			'description' => 'If you tick this box, all the fields below will be used. If you leave it unticked, they will be ignored.'
		)
	);

	add_settings_field(
		'entry_footer_pt_text', //* ID
		'Post Type Specific Custom Entry Footer Text', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_entry_footer', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'type' => 'text',
			'filters' => array( 'esc_attr' ),
			'fieldset' => $public_post_types,
			'prefix' => 'entry_footer_pt_text_',
			'labelset' => $public_post_types
		)
	);

	add_settings_section(
		'genesis_advanced_edits_edit_post', //* ID
		'Edit Post Link', //* title to be displayed
		function() { //* callback genesis_advanced_edits_edit_post
			echo '<p>The Edit Post link displays on posts and pages. It only displays to yourself and any other users who have the capability to edit your content, but you can choose to not have it display at all.</p>';
		},
		'genesis_advanced_edits' //* settings page to add to
	);

	add_settings_field(
		'edit_post', //* ID
		'<label for="genesis_advanced_edits[edit_post]">Remove Edit Post Link?</label>', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_edit_post', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'edit_post',
			'type' => 'checkbox',
			'label' => 'Tick this box to remove the edit post link on posts and pages when signed in.'
		)
	);

	add_settings_section(
		'genesis_advanced_edits_pagination', //* ID
		'Pagination Links', //* title to be displayed
		function() { //* callback genesis_advanced_edits_pagination
			echo '<p>The pagination links appear on archives, search results and blog pages when there are too many posts to fit on a single page. You can customise the text used on the pagination links.</p>';
		},
		'genesis_advanced_edits' //* settings page to add to
	);

	add_settings_field(
		'pagination', //* ID
		'Replace Pagination Link Text?', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_pagination', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'pagination',
			'type' => 'checkbox',
			'label' => 'Tick this box to replace the default Genesis pagination link text with your own version.',
			'description' => 'If you leave this box unticked, whatever you enter in the fields below will be ignored and the default text will be used.'
		)
	);

	add_settings_field(
		'prev_link_text', //* ID
		'<label for="genesis_advanced_edits[prev_link_text]">Custom Previous Link Text</label>', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_pagination', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'prev_link_text',
			'type' => 'text',
			'description' => '<strong>Default:</strong> ' . '&#x000AB;' . __( 'Previous Page', 'genesis' ),
			'filters' => array( 'esc_attr' )
		)
	);

	add_settings_field(
		'next_link_text', //* ID
		'<label for="genesis_advanced_edits[next_link_text]">Custom Next Link Text</label>', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_pagination', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'next_link_text',
			'type' => 'text',
			'description' => '<strong>Default:</strong> ' . __( 'Next Page', 'genesis' ) . '&#x000BB;',
			'filters' => array( 'esc_attr' )
		)
	);

	add_settings_section(
		'genesis_advanced_edits_content_limit', //* ID
		'Content Character Limit', //* title to be displayed
		function() { //* callback genesis_advanced_edits_noposts
			echo '<p>Genesis provides a feature called the Content Character Limit, which trims your content to a certain number of characters and can be used in various places across your site. If you prefer, you can choose to use the native WordPress excerpt instead.</p>';
		},
		'genesis_advanced_edits' //* settings page to add to
	);

	add_settings_field(
		'content_limit', //* ID
		'<label for="genesis_advanced_edits[content_limit]">Use Native Excerpt?</label>', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_content_limit', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'content_limit',
			'type' => 'checkbox',
			'label' => 'Tick this box to use the native WordPress excerpt in place of the Genesis content character limit.',
		)
	);

	add_settings_section(
		'genesis_advanced_edits_site_footer', //* ID
		'Site Footer', //* title to be displayed
		function() { //* callback genesis_advanced_edits_site_footer
			echo '<p>The site footer is a line of text that displays at the bottom of every page of your site.</p>';
		},
		'genesis_advanced_edits' //* settings page to add to
	);

	add_settings_field(
		'site_footer', //* ID
		'Replace Site Footer Text?', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_site_footer', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'site_footer',
			'type' => 'checkbox',
			'label' => 'Tick this box to replace the default Genesis site footer text with your own version.',
			'description' => 'If you leave this box unticked, whatever you enter in the field below will be ignored and the default text will be used.'
		)
	);

	add_settings_field(
		'site_footer_text', //* ID
		'<label for="genesis_advanced_edits[site_footer_text]">Custom Site Footer Text</label>', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_site_footer', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'site_footer_text',
			'type' => 'text',
			'description' => '<strong>Default:</strong> ' . sprintf( '[footer_copyright before="%s "] &#x000B7; [footer_childtheme_link before="" after=" %s"] [footer_genesis_link url="http://www.studiopress.com/" before=""] &#x000B7; [footer_wordpress_link] &#x000B7; [footer_loginout]', __( 'Copyright', 'genesis' ), __( 'on', 'genesis' ) ),
			'filters' => array( 'esc_attr' )
		)
	);

	add_settings_section(
		'genesis_advanced_edits_noposts', //* ID
		'No Posts', //* title to be displayed
		function() { //* callback genesis_advanced_edits_noposts
			echo '<p>The No Posts text displays when no posts are found for the given criteria, usually in a search.</p>';
		},
		'genesis_advanced_edits' //* settings page to add to
	);

	add_settings_field(
		'noposts', //* ID
		'Replace No Posts Text?', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_noposts', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'noposts',
			'type' => 'checkbox',
			'label' => 'Tick this box to replace the default Genesis No Posts text with your own version.',
			'description' => 'If you leave this box unticked, whatever you enter in the field below will be ignored and the default text will be used.'
		)
	);

	add_settings_field(
		'noposts_text', //* ID
		'<label for="genesis_advanced_edits[noposts_text]">Custom No Posts Text</label>', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_noposts', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => 'noposts_text',
			'type' => 'text',
			'description' => '<strong>Default:</strong> ' . __( 'Sorry, no content matched your criteria.', 'genesis' ),
			'filters' => array( 'esc_attr' )
		)
	);

	add_settings_section(
		'genesis_advanced_edits_404', //* ID
		'404 Page Content', //* title to be displayed
		function() { //* callback genesis_advanced_edits_404
			echo '<p>The 404 page displays when a user tries to reach a page that doesn&apos;t exist. You can customise the headline and body text on the 404 page.</p>';
		},
		'genesis_advanced_edits' //* settings page to add to
	);

	add_settings_field(
		'404', //* ID
		'Replace 404 Content?', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_404', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => '404',
			'type' => 'checkbox',
			'label' => 'Tick this box to replace the default Genesis 404 content with your own version.',
			'description' => 'If you leave this box unticked, whatever you enter in the fields below will be ignored and the default content will be used.'
		)
	);

	add_settings_field(
		'404_headline', //* ID
		'<label for="genesis_advanced_edits[404_headline]">Custom 404 Headline</label>', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_404', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => '404_headline',
			'type' => 'text',
			'description' => '<strong>Default:</strong> ' . __( 'Not found, error 404', 'genesis' ),
			'filters' => array( 'esc_attr' )
		)
	);

	add_settings_field(
		'404_body', //* ID
		'Custom 404 Body', //* label
		'cm_settings_field_callback', //* callback
		'genesis_advanced_edits', //* settings page to add to
		'genesis_advanced_edits_404', //* section to add to
		array(
			'setting' => 'genesis_advanced_edits',
			'field' => '404_body',
			'type' => 'editor',
			'rows' => 8,
			'description' => '<strong>Default:</strong> ' . sprintf( __( 'The page you are looking for no longer exists. Perhaps you can return back to the site\'s <a href="%s">homepage</a> and see if you can find what you are looking for. Or, you can try finding it by using the search form below.', 'genesis' ), home_url() )
		)
	);

	$post_types_with_archives = get_post_types( array( 'has_archive' => true ) );
	if ( $post_types_with_archives ) {
		add_settings_section(
			'genesis_advanced_edits_cpt_archives_settings', //* ID
			'Custom Post Type Archives Settings', //* title to be displayed
			function() { //* callback genesis_advanced_edits_cpt_archives_settings
				echo '<p>Select the post types for which you would like to enable Genesis custom post type archives settings. Only post types with archives are listed.</p>';
			},
			'genesis_advanced_edits' //* settings page to add to
		);

		add_settings_field(
			'cpt_archives_settings', //* ID
			'Enable Custom Post Type Archives Settings', //* label
			'cm_settings_field_callback', //* callback
			'genesis_advanced_edits', //* settings page to add to
			'genesis_advanced_edits_cpt_archives_settings', //* section to add to
			array(
				'setting' => 'genesis_advanced_edits',
				'type' => 'checkbox',
				'fieldset' => $post_types_with_archives,
				'prefix' => 'cpt_archives_settings_',
				'labelset' => $post_types_with_archives
			)
		);
	}

	register_setting( 'genesis_advanced_edits', 'genesis_advanced_edits' );
}

//* Remove actions that will be replaced
add_action( 'get_header', 'genesis_advanced_edits_remove_actions' );
function genesis_advanced_edits_remove_actions() {
	remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
	remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

	remove_action( 'genesis_loop', 'genesis_404' );
}

//* Select whether to do the entry header
add_action( 'genesis_entry_header', 'genesis_advanced_edits_do_entry_header', 12 );
function genesis_advanced_edits_do_entry_header() {
	$options = get_option( 'genesis_advanced_edits' );
	if ( !is_singular() && isset( $options[ 'entry_header_archive' ] ) ) //* display on archives
		return;

	$post_type = get_post_type();
	if ( !isset( $options[ 'entry_header_display_' . $post_type ] ) ) //* post type control
		return;

	if ( 'page' !== $post_type )
		return genesis_post_info();

	//* Here's where it gets messy...
	global $post;
	$post->post_type = 'genesis_advanced_edits_temp_post_type'; //* this is necessary due to the nature of genesis_post_info
	genesis_post_info();
	$post->post_type = 'page';
}

//* Customise the entry header
add_filter( 'genesis_post_info', 'genesis_advanced_edits_filter_entry_header' );
function genesis_advanced_edits_filter_entry_header( $text ) {
	$options = get_option( 'genesis_advanced_edits' );

	$post_type = get_post_type();
	if ( 'genesis_advanced_edits_temp_post_type' === $post_type ) //* the messy part
		$post_type = 'page';

	if ( isset( $options[ 'entry_header_pt' ] ) ) //* post type specific custom text
		$text = isset( $options[ 'entry_header_pt_text_' . $post_type ] ) ? wptexturize( do_shortcode( $options[ 'entry_header_pt_text_' . $post_type ] ) ) : '';

	else if ( isset( $options[ 'entry_header' ] ) ) //* non post type specific custom text
		$text = isset( $options[ 'entry_header_text' ] ) ? wptexturize( do_shortcode( $options[ 'entry_header_text' ] ) ) : '';

	return $text;
}

//* Select whether to do the entry footer (markup open)
add_action( 'genesis_entry_footer', 'genesis_advanced_edits_do_entry_footer_markup_open', 5 );
function genesis_advanced_edits_do_entry_footer_markup_open() {
	$options = get_option( 'genesis_advanced_edits' );
	if ( !is_singular() && isset( $options[ 'entry_footer_archive' ] ) ) //* display on archives
		return;

	$post_type = get_post_type();
	if ( !isset( $options[ 'entry_footer_display_' . $post_type ] ) ) //* post type control
		return;

	if ( 'post' === $post_type )
		return genesis_entry_footer_markup_open();

	//* Here's where it gets messy...
	global $post;
	$post->post_type = 'post'; //* this is necessary due to the nature of genesis_entry_footer_markup_open
	genesis_entry_footer_markup_open();
	$post->post_type = $post_type;
}

//* Select whether to do the entry footer
add_action( 'genesis_entry_footer', 'genesis_advanced_edits_do_entry_footer' );
function genesis_advanced_edits_do_entry_footer() {
	$options = get_option( 'genesis_advanced_edits' );
	if ( !is_singular() && isset( $options[ 'entry_footer_archive' ] ) ) //* display on archives
		return;

	$post_type = get_post_type();
	if ( !isset( $options[ 'entry_footer_display_' . $post_type ] ) ) //* post type control
		return;

	if ( 'page' !== $post_type )
		return genesis_post_meta();

	//* Here's where it gets messy...
	global $post;
	$post->post_type = 'genesis_advanced_edits_temp_post_type'; //* this is necessary due to the nature of genesis_post_meta
	genesis_post_meta();
	$post->post_type = 'page';
}

//* Select whether to do the entry footer (markup close)
add_action( 'genesis_entry_footer', 'genesis_advanced_edits_do_entry_footer_markup_close', 15 );
function genesis_advanced_edits_do_entry_footer_markup_close() {
	$options = get_option( 'genesis_advanced_edits' );
	if ( !is_singular() && isset( $options[ 'entry_footer_archive' ] ) ) //* display on archives
		return;

	$post_type = get_post_type();
	if ( !isset( $options[ 'entry_footer_display_' . $post_type ] ) ) //* post type control
		return;

	if ( 'post' === $post_type )
		return genesis_entry_footer_markup_close();

	//* Here's where it gets messy...
	global $post;
	$post->post_type = 'post'; //* this is necessary due to the nature of genesis_entry_footer_markup_close
	genesis_entry_footer_markup_close();
	$post->post_type = $post_type;
}

//* Customise the entry footer
add_filter( 'genesis_post_meta', 'genesis_advanced_edits_filter_entry_footer' );
function genesis_advanced_edits_filter_entry_footer( $text ) {
	$options = get_option( 'genesis_advanced_edits' );

	$post_type = get_post_type();
	if ( 'genesis_advanced_edits_temp_post_type' === $post_type ) //* the messy part
		$post_type = 'page';

	if ( isset( $options[ 'entry_footer_pt' ] ) ) //* post type specific custom text
		$text = isset( $options[ 'entry_footer_pt_text_' . $post_type ] ) ? wptexturize( do_shortcode( $options[ 'entry_footer_pt_text_' . $post_type ] ) ) : '';

	else if ( isset( $options[ 'entry_footer' ] ) ) //* non post type specific custom text
		$text = isset( $options[ 'entry_footer_text' ] ) ? wptexturize( do_shortcode( $options[ 'entry_footer_text' ] ) ) : '';

	return $text;
}

//* Don't echo genesis edit post link
add_filter( 'genesis_edit_post_link', 'genesis_advanced_edits_do_edit_post' );
function genesis_advanced_edits_do_edit_post( $boolean ) {
	$options = get_option( 'genesis_advanced_edits' );
	if ( isset( $options[ 'edit_post' ] ) )
		$boolean = false;

	return $boolean;
}

//* Modify previous and next link text
add_filter( 'genesis_prev_link_text', 'genesis_advanced_edits_do_prev_link_text' );
function genesis_advanced_edits_do_prev_link_text( $content ) {
	$options = get_option( 'genesis_advanced_edits' );
	if ( isset( $options[ 'pagination' ] ) )
		$content = isset( $options[ 'prev_link_text' ] ) ? wptexturize( esc_attr( $options[ 'prev_link_text' ] ) ) : '';

	return $content;
}

add_filter( 'genesis_next_link_text', 'genesis_advanced_edits_do_next_link_text' );
function genesis_advanced_edits_do_next_link_text( $content ) {
	$options = get_option( 'genesis_advanced_edits' );
	if ( isset( $options[ 'pagination' ] ) )
		$content = isset( $options[ 'next_link_text' ] ) ? wptexturize( esc_attr( $options[ 'next_link_text' ] ) ) : '';

	return $content;
}

//* Replace genesis 'content character limit' with native wordpress excerpt
add_filter( 'get_the_content_limit', 'genesis_advanced_edits_do_content_limit', 10, 4 );
function genesis_advanced_edits_do_content_limit( $output, $content, $link, $max_characters ) {
	$options = get_option( 'genesis_advanced_edits' );
	if ( isset( $options[ 'content_limit' ] ) )
		$output = sprintf( '<p>%s</p>', get_the_excerpt() );

	return $output;
}

//* Customise the site footer text
add_filter( 'genesis_footer_creds_text', 'genesis_advanced_edits_do_footer_text' );
function genesis_advanced_edits_do_footer_text( $text ) {
	$options = get_option( 'genesis_advanced_edits' );
	if ( isset( $options[ 'site_footer' ] ) )
		$text = isset( $options[ 'site_footer_text' ] ) ? wptexturize( do_shortcode( $options[ 'site_footer_text' ] ) ) : '';

	return $text;
}

//* Customise noposts text
add_filter( 'genesis_noposts_text', 'genesis_advanced_edits_do_noposts' );
function genesis_advanced_edits_do_noposts( $text ) {
	$options = get_option( 'genesis_advanced_edits' );
	if ( isset( $options[ 'noposts' ] ) )
		$text = isset( $options[ 'noposts_text' ] ) ? wptexturize( do_shortcode( $options[ 'noposts_text' ] ) ) : '';

	return $text;
}

//* Customise the 404 page
add_action( 'genesis_loop', 'genesis_advanced_edits_do_404' );
function genesis_advanced_edits_do_404() {
	if ( !is_404() )
		return;

	$options = get_option( 'genesis_advanced_edits' );
	if ( !isset( $options[ '404' ] ) )
		return genesis_404();

	echo '<article class="entry">';
	printf( '<h1 class="entry-title">%s</h1>', isset( $options[ '404_headline' ] ) ? wptexturize( esc_attr( $options[ '404_headline' ] ) ) : '' );
	printf ( '<div class="entry-content">%s%s</div>', isset( $options[ '404_body' ] ) ? wpautop( wptexturize( do_shortcode( $options[ '404_body' ] ) ) ) : '', get_search_form( false ) );
	echo '</article>';
}

//* Add support for custom post type archives settings
add_action( 'wp_loaded', 'genesis_advanced_edits_do_cpt_archives_settings' );
function genesis_advanced_edits_do_cpt_archives_settings() {
	$options = get_option( 'genesis_advanced_edits' );
	$post_types_with_archives = get_post_types( array( 'has_archive' => true ) );

	foreach ( $post_types_with_archives as $post_type )
		if ( isset( $options[ 'cpt_archives_settings_' . $post_type ] ) )
			add_post_type_support( $post_type, 'genesis-cpt-archives-settings' );
}
