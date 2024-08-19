<?php
add_action(
	'wp_loaded',
	function () {
		$settings = array(
			'listing_archive',
			'compare_archive',
			'price_currency',
			'price_currency_position',
			'price_delimeter',
			'show_listing_stock',
			'show_listing_compare',
			'show_stock',
			'show_test_drive',
			'show_compare',
			'show_share',
			'show_pdf',
			'show_certified_logo_1',
			'show_certified_logo_2',
			'show_featured_btn',
			'single_car_break',
			'show_vin',
			'show_registered',
			'show_history',
			'user_add_car_page',
			'user_post_limit',
			'user_post_images_limit',
			'user_premoderation',
			'site_demo_mode',
		);

		$nuxy_settings = get_option( STM_LISTINGS_SETTINGS_NAME, array() );

		$patch_settings = array();

		if ( function_exists( 'wpcfto_get_settings_map' ) && empty( $nuxy_settings ) ) {

			$nuxy_settings_map = wpcfto_get_settings_map( 'settings', STM_LISTINGS_SETTINGS_NAME );
			foreach ( $nuxy_settings_map as $section_name => $fields ) {
				foreach ( $fields['fields'] as $field_name => $field ) {
					$f_name = ( 'compare_page' === $field_name ) ? 'compare_archive' : $field_name;
					if ( in_array( $f_name, $settings, true ) ) {
						$value = get_theme_mod( $f_name, '' );
						if ( ! empty( $value ) ) {
							$patch_settings[ $field_name ] = $value;
						}

						remove_theme_mod( $f_name );
					}
				}
			}
		}

		if ( ! get_option( 'motors_vehicles_listing_plugin_settings_updated', false ) || empty( get_option( STM_LISTINGS_SETTINGS_NAME, '' ) ) ) {
			$layout         = get_option( 'stm_motors_chosen_template', '' );
			$theme_settings = get_option( 'wpcfto_motors_' . $layout . '_settings', array() );

			if ( ! empty( $theme_settings['listing_boat_filter'] ) && true === (bool) $theme_settings['listing_boat_filter'] ) {
				$theme_settings['listing_filter_position'] = 'horizontal';
			}

			$theme_settings['new_user_registration']         = true;
			$theme_settings['allow_user_register_as_dealer'] = true;
			$new_settings = array_merge( $theme_settings, $patch_settings );

			update_option( 'motors_vehicles_listing_plugin_settings_updated', true );
			update_option( STM_LISTINGS_SETTINGS_NAME, $new_settings );
		}
	}
);
