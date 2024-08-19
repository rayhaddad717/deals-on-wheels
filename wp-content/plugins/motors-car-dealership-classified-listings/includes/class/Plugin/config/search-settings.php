<?php
add_filter(
	'mvl_get_all_nuxy_config',
	function ( $global_conf ) {
		$conf = apply_filters( 'search_settings_conf', array() );

		$conf = array(
			'name'   => esc_html__( 'Search and Filtering', 'stm_vehicles_listing' ),
			'fields' => $conf,
		);

		$global_conf['search_settings'] = $conf;

		return $global_conf;
	},
	20,
	1
);
