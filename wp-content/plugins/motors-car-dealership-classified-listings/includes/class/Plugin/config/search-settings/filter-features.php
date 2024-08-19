<?php
add_filter(
	'search_settings_conf',
	function ( $conf_for_merge ) {
		$dependencies = apply_filters(
			'features_search_dependencies',
			array(
				'dependency'   => array(
					array(
						'key'   => 'listing_filter_position',
						'value' => 'left',
					),
					array(
						'key'   => 'listing_filter_position',
						'value' => 'right',
					),
				),
				'dependencies' => '||',
			)
		);
		$conf         = array(
			'enable_features_search' =>
				array_merge(
					array(
						'label'       => esc_html__( 'Filter by features', 'stm_vehicles_listing' ),
						'description' => esc_html__( 'The search results can be filtered based on features', 'stm_vehicles_listing' ),
						'type'        => 'checkbox',
					),
					$dependencies
				),
		);

		return array_merge( $conf_for_merge, $conf );
	},
	30,
	1
);
