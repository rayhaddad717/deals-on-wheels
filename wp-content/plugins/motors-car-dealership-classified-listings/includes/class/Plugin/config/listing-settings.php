<?php
add_filter(
	'mvl_get_all_nuxy_config',
	function ( $global_conf ) {
		$conf = apply_filters( 'listing_settings_conf', array() );

		$conf = array(
			'name'   => esc_html__( 'Listings Page', 'stm_vehicles_listing' ),
			'fields' => apply_filters( 'me_listing_settings_conf', $conf ),
		);

		$global_conf['listing_settings'] = $conf;

		return $global_conf;
	},
	10,
	1
);
