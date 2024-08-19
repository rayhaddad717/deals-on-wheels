<?php

namespace MotorsVehiclesListing\Plugin;

require_once STM_LISTINGS_PATH . '/includes/class/Plugin/settings_patch.php';

class Settings {

	private $assets_url = STM_LISTINGS_URL . '/includes/class/Plugin/assets/img/';

	public function __construct() {
		add_action( 'init', array( $this, 'mvl_plugin_conf_autoload' ) );
		if ( apply_filters( 'stm_disable_settings_setup', true ) ) {
			add_action( 'wpcfto_after_settings_saved', array( $this, 'mvl_save_featured_as_term' ), 50, 2 );
			add_filter( 'wpcfto_options_page_setup', array( $this, 'mvl_settings' ) );
			add_action( 'stm_importer_done', array( $this, 'mlv_save_settings' ), 20, 1 );
			add_filter( 'wpcfto_icons_set', array( $this, 'icons_set_icon_picker' ) );
		}
	}

	public function icons_set_icon_picker( $icon_set ) {
		$_icons = stm_get_cat_icons( 'motors-icons', true );

		if ( ! empty( $_icons ) ) {
			return array_merge( $icon_set, $_icons );
		}

		return $icon_set;
	}

	public function mvl_plugin_conf_autoload() {
		$config_map = array(
			'listing-settings',
			'listing-settings/general',
			'listing-settings/currency',
			'listing-settings/listing-card',
			'listing-settings/listing-card-certified-logo',
			'search-settings',
			'search-settings/filter-position',
			'search-settings/sorting',
			'search-settings/filter-features',
			'search-settings/general',
			'single-listing',
			'single-listing/general',
			'single-listing/single-listing-layout',
			'user-main',
			'user-settings/user-settings',
			'monetization',
			'pages',
			'pages-settings/pages-settings',
			'google-services',
			'google-services/recaptcha-settings',
		);

		if ( ! stm_is_motors_theme() ) {
			$config_map = array_merge(
				$config_map,
				array(
					'shortcodes',
					'shortcodes/shortcodes-list',
				)
			);
		}

		foreach ( $config_map as $file_name ) {
			require_once STM_LISTINGS_PATH . '/includes/class/Plugin/config/' . $file_name . '.php';
		}
	}

	public function mvl_settings( $setup ) {
		$opts = apply_filters( 'mvl_get_all_nuxy_config', array() );

		$motors_favicon = $this->assets_url . 'icon.png';
		$motors_logo    = $this->assets_url . 'logo.png';

		$setup[] = array(
			'option_name' => STM_LISTINGS_SETTINGS_NAME,
			'title'       => esc_html__( 'Motors Plugin Settings', 'stm_vehicles_listing' ),
			'sub_title'   => esc_html__( 'by StylemixThemes', 'stm_vehicles_listing' ),
			'logo'        => $motors_logo,

			/*
			* Next we add a page to display our awesome settings.
			* All parameters are required and same as WordPress add_menu_page.
			*/
			'page'        => array(
				'page_title' => 'Motors Plugin Settings',
				'menu_title' => 'Motors Plugin Settings',
				'menu_slug'  => 'mvl_plugin_settings',
				'icon'       => $motors_favicon,
				'position'   => 6,
			),

			/*
			* And Our fields to display on a page. We use tabs to separate settings on groups.
			*/
			'fields'      => $opts,
		);
		return $setup;
	}

	public function mvl_save_featured_as_term( $id, $settings ) {

		if ( array_key_exists( 'addl_user_features', $settings ) ) {
			foreach ( $settings['addl_user_features'] as $addl_user_feature ) {
				if ( ! empty( $addl_user_feature['tab_title_labels'] ) ) {
					$feature_list = explode( ',', $addl_user_feature['tab_title_labels'] );

					foreach ( $feature_list as $item ) {
						wp_insert_term( trim( $item ), 'stm_additional_features' );
					}
				}
			}
		}
	}

	public function mlv_save_settings() {
		$layout         = get_option( 'stm_motors_chosen_template', '' );
		$theme_settings = get_option( 'wpcfto_motors_' . $layout . '_settings', array() );

		update_option( 'motors_vehicles_listing_plugin_settings_updated', true );
		update_option( STM_LISTINGS_SETTINGS_NAME, $theme_settings );
	}
}
