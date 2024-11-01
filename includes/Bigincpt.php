<?php
/**
 * Frontend handler class
 */
if ( ! class_exists( 'W3scbigin_Cpt' ) ) {
	class W3scbigin_Cpt {

		/**
		 * Initialize the class
		 */
		function __construct() {
			// Generate CPT
			add_action( 'init', array( $this, 'w3sc_bigin_post_type' ) );
			// initialize CMB2
			add_action( 'cmb2_admin_init', array( $this, 'w3scbigin_metabox' ) );
		}

		// Register Custom Post Type
		function w3sc_bigin_post_type() {
			$labels = array(
				'name'                  => _x( 'Bigin Integrations', 'Post Type General Name', 'w3sc-cf7-bigin' ),
				'singular_name'         => _x( 'Bigin Integration', 'Post Type Singular Name', 'w3sc-cf7-bigin' ),
				'menu_name'             => __( 'Bigin Integrations', 'w3sc-cf7-bigin' ),
				'name_admin_bar'        => __( 'Integration', 'w3sc-cf7-bigin' ),
				'archives'              => __( 'Integration Archives', 'w3sc-cf7-bigin' ),
				'attributes'            => __( 'Integration Attributes', 'w3sc-cf7-bigin' ),
				'parent_item_colon'     => __( 'Parent Integration:', 'w3sc-cf7-bigin' ),
				'all_items'             => __( 'All Integrations', 'w3sc-cf7-bigin' ),
				'add_new_item'          => __( 'Add New Integration', 'w3sc-cf7-bigin' ),
				'add_new'               => __( 'Add New', 'w3sc-cf7-bigin' ),
				'new_item'              => __( 'New Integration', 'w3sc-cf7-bigin' ),
				'edit_item'             => __( 'Edit Integration', 'w3sc-cf7-bigin' ),
				'update_item'           => __( 'Update Integration', 'w3sc-cf7-bigin' ),
				'view_item'             => __( 'View Integration', 'w3sc-cf7-bigin' ),
				'view_items'            => __( 'View Integrations', 'w3sc-cf7-bigin' ),
				'search_items'          => __( 'Search Integration', 'w3sc-cf7-bigin' ),
				'not_found'             => __( 'Not found', 'w3sc-cf7-bigin' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'w3sc-cf7-bigin' ),
				'featured_image'        => __( 'Featured Image', 'w3sc-cf7-bigin' ),
				'set_featured_image'    => __( 'Set featured image', 'w3sc-cf7-bigin' ),
				'remove_featured_image' => __( 'Remove featured image', 'w3sc-cf7-bigin' ),
				'use_featured_image'    => __( 'Use as featured image', 'w3sc-cf7-bigin' ),
				'insert_into_item'      => __( 'Insert into integration', 'w3sc-cf7-bigin' ),
				'uploaded_to_this_item' => __( 'Uploaded to this item', 'w3sc-cf7-bigin' ),
				'items_list'            => __( 'Items list', 'w3sc-cf7-bigin' ),
				'items_list_navigation' => __( 'Items list navigation', 'w3sc-cf7-bigin' ),
				'filter_items_list'     => __( 'Filter items list', 'w3sc-cf7-bigin' ),
			);
			$args   = array(
				'label'               => __( 'Integration', 'w3sc-cf7-bigin' ),
				'description'         => __( 'Integration to Zoho CRM with Contact Form 7', 'w3sc-cf7-bigin' ),
				'labels'              => $labels,
				'supports'            => array( 'title' ),
				'hierarchical'        => false,
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 51,
				'menu_icon'           => 'dashicons-bell',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => false,
				'can_export'          => false,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'rewrite'             => false,
				'capability_type'     => 'post',
				'show_in_rest'        => true,
			);
			register_post_type( 'w3sc_bigin', $args );
		}

		function w3scbigin_metabox() {
			$bigin_modules = array(
				'Contacts' => 'Contacts',
			);
			// Get all contact forms 7
			$all_contact_forms = array_column(
				get_posts(
					array(
						'post_type'   => 'wpcf7_contact_form',
						'numberposts' => -1,
					)
				),
				'post_title',
				'ID'
			);

			$integrationField = new_cmb2_box(
				array(
					'id'           => 'w3sc_bigin_integration_metabox',
					'title'        => esc_html__( 'Integration', 'w3sc-cf7-bigin' ),
					'object_types' => array( 'w3sc_bigin' ),
					'context'      => 'normal',
					'priority'     => 'high',
					'show_names'   => true, // Show field names on the left
				)
			);

			$integrationField->add_field(
				array(
					'name'             => 'Enable Integration',
					'id'               => 'integration_enable_disable',
					'desc'             => 'Select option',
					'type'             => 'select',
					'show_option_none' => true,
					'options'          => array(
						0 => 'Disable',
						1 => 'Enable',
					),
				)
			);

			$integrationField->add_field(
				array(
					'name'             => 'Bigin Module',
					'id'               => 'w3sc-cf7-bigin_module',
					'desc'             => 'Select option',
					'type'             => 'select',
					'show_option_none' => true,
					'options'          => $bigin_modules,
				)
			);
			$integrationField->add_field(
				array(
					'name'             => 'Select Contact form',
					'id'               => 'w3sc-cf7-bigin_cf7_form',
					'desc'             => 'Select option',
					'type'             => 'select',
					'show_option_none' => true,
					'options'          => $all_contact_forms,
				)
			);

			// ===============================================

			$cmb = new_cmb2_box(
				array(
					'id'           => 'w3sc_bigin_fields_metabox',
					'title'        => esc_html__( 'Field Mapping', 'w3sc-cf7-bigin' ),
					'object_types' => array( 'w3sc_bigin' ),
					'context'      => 'normal',
					'priority'     => 'high',
					'show_names'   => true, // Show field names on the left
				)
			);

			$cf7fields   = array();
			$biginFields = array();

			if ( isset( $_GET['post'] ) ) {
				$post_id = intval( sanitize_text_field( $_GET['post'] ) );
				if ( get_post_type( $post_id ) == 'w3sc_bigin' ) {
					$formID = get_post_meta(
						$post_id,
						'w3sc-cf7-bigin_cf7_form',
						true
					);
					// Fetch CF7 fields name

					if ( ! empty( $formID ) ) {
						$ContactForm = WPCF7_ContactForm::get_instance( $formID );
						$form_fields = $ContactForm->scan_form_tags();

						foreach ( $form_fields as $key => $value ) {
							$name               = $value->name;
							$datatype           = $value->basetype;
							$namesec            = $name . '' . '(' . $datatype . ')';
							$cf7fields[ $name ] = $namesec;
						}
					}

					// Fetch Bigin fields Name
					$access_token = w3scbigin_accessToken();
					if ( $access_token ) {
						$biginfields = $this->detect_biginfields( $access_token );
					}

					if ( ! empty( $biginfields ) ) {
						foreach ( $biginfields['fields'] as $key => $value ) {
							$name     = $value['api_name'];
							$datatype = $value['data_type'];
							$namesec  = $name . '' . '(' . $datatype . ')';
							// Skip unnecessary datatype
							if ( $datatype != 'lookup' && $datatype != 'ownerlookup' && $datatype != 'profileimage' ) {
								$biginFields[ $name ] = $namesec;
							}
						}
					}
				}
			}

			$group_field_id = $cmb->add_field(
				array(
					'id'          => 'w3sc_bigin_fields_repeat_group',
					'type'        => 'group',
					'description' => __( 'Map Contact form 7 fields to Bigin fields. N.B: Last_Name field is required.', 'w3sc-cf7-bigin' ),
					// 'repeatable'  => false, // use false if you want non-repeatable gro                                                   up
					'options'     => array(
						'group_title'    => __( 'Field Map {#}', 'w3sc-cf7-bigin' ), // since version 1.1.4, {#} gets replaced by row number
						'add_button'     => __( 'Map Another Field', 'w3sc-cf7-bigin' ),
						'remove_button'  => __( 'Remove Map', 'w3sc-cf7-bigin' ),
						'sortable'       => true,
						'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'w3sc-cf7-bigin' ), // Performs confirmation before removing group.
					),
				)
			);

			// Id's for group's fields only need to be unique for the group. Prefix is not needed.
			/*
			$cmb->add_group_field( $group_field_id, array(
			'name' => 'Manual Value',
			'id'   => 'manual_value',
			'type' => 'text',
			) );*/

			$cmb->add_group_field(
				$group_field_id,
				array(
					'name'             => 'CF7 Field Select',
					'desc'             => 'Select an option',
					'id'               => 'cf7_select',
					'type'             => 'select',
					'show_option_none' => true,
					'options'          => $cf7fields,
				)
			);

			$cmb->add_group_field(
				$group_field_id,
				array(
					'name'             => 'Bigin Field Select',
					'desc'             => 'Select an option',
					'id'               => 'bigin_select',
					'type'             => 'select',
					'show_option_none' => true,
					'options'          => $biginFields,
				)
			);
		}

		// Fetch bigin Field name/type
		function detect_biginfields( $access_token ) {
			$dataSet    = new AuthInfos();
			$dataCenter = $dataSet->getInfo( 'data_center' );
			$args       = array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $access_token,
				),
			);

			$test         = wp_remote_get(
				"https://www.zohoapis{$dataCenter}/bigin/v1/settings/fields?module=Contacts",
				$args
			);
			$responceData = json_decode( wp_remote_retrieve_body( $test ), true );
			return $responceData;
		}
	}
}
