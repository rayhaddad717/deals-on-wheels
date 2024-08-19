<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$filter = apply_filters( 'stm_listings_filter_func', null );

if ( empty( $action ) ) {
	$action = 'listings-result';
}

$show_sold = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_sold_listings' );
?>
<form action="<?php echo esc_url( apply_filters( 'stm_listings_current_url', '' ) ); ?>" method="get" data-trigger="filter">
	<div class="filter filter-sidebar ajax-filter">

		<?php
		/**
		 * Hook: stm_listings_filter_before.
		 *
		 * @hooked stm_listings_parent_list_response - 10
		 * @hooked stm_listings_filter_nonce_response - 15
		 */
		do_action( 'stm_listings_filter_before' );
		?>

		<div class="sidebar-entry-header">
			<i class="motors-icons-car_search"></i>
			<span class="h4"><?php esc_html_e( 'Search Options', 'stm_vehicles_listing' ); ?></span>
		</div>

		<div class="row row-pad-top-24">

			<?php
			if ( empty( $filter['filters'] ) ) :
				$post_type_name = esc_html__( 'Listings', 'stm_vehicles_listing' );
				if ( stm_is_multilisting() ) {
					$ml = new STMMultiListing();
					if ( ! empty( $ml->stm_get_current_listing() ) ) {
						$multitype      = $ml->stm_get_current_listing();
						$post_type_name = $multitype['label'];
					}
				}
				?>
				<div class="col-md-12 col-sm-12">
					<p class="text-muted text-center">
						<?php echo sprintf( esc_html__( 'No categories created for %s', 'stm_vehicles_listing' ), $post_type_name ); //phpcs:ignore ?>
					</p>
				</div>
				<?php
			else :
				do_action(
					'stm_listings_load_template',
					'filter/types/keywords',
					array(
						'position' => 'top',
					)
				);

				$parent_list = apply_filters( 'stm_listings_parent_list', false );

				if ( ! is_array( $parent_list ) ) {
					$parent_list = array();
				}

				foreach ( $filter['filters'] as $attribute => $config ) :
					if ( ! empty( $filter['options'][ $attribute ] ) ) :
						if ( ! empty( $config['slider'] ) && ! empty( $config['numeric'] ) ) :
							do_action(
								'stm_listings_load_template',
								'filter/types/slider',
								array(
									'taxonomy' => $config,
									'options'  => $filter['options'][ $attribute ],
								)
							);
						else :
							?>
						<div class="col-md-12 col-sm-6 stm-filter_<?php echo esc_attr( $attribute ); ?>">
							<div class="form-group">
								<?php
								do_action(
									'stm_listings_load_template',
									'filter/types/select',
									array(
										'options'   => $filter['options'][ $attribute ],
										'name'      => $attribute,
										'is_parent' => in_array( $attribute, $parent_list, true ),
										'multiple'  => array_key_exists( 'is_multiple_select', $config ) ? $config['is_multiple_select'] : false,
									)
								);
								?>
							</div>
						</div>
								<?php
							endif;
						endif;
					endforeach;
				?>

				<?php
				if ( $show_sold && 'listings-sold' !== $action ) :
					$listing_status = apply_filters( 'stm_listings_input', '', 'listing_status' );
					?>
					<div class="col-md-12 col-sm-12 stm-filter_listing_status">
						<div class="form-group">
							<select name="listing_status" aria-label="<?php esc_attr_e( 'Select listing status', 'stm_vehicles_listing' ); ?>" class="form-control">
								<option value="">
									<?php esc_html_e( 'Listing status', 'stm_vehicles_listing' ); ?>
								</option>
								<option value="active" <?php selected( $listing_status, 'active' ); ?>>
									<?php esc_html_e( 'Active', 'stm_vehicles_listing' ); ?>
								</option>
								<option value="sold" <?php selected( $listing_status, 'sold' ); ?>>
									<?php esc_html_e( 'Sold', 'stm_vehicles_listing' ); ?>
								</option>
							</select>
						</div>
					</div>
				<?php endif; ?>

				<?php
				do_action( 'stm_listings_load_template', 'filter/types/location' );

				do_action(
					'stm_listings_load_template',
					'filter/types/features',
					array(
						'taxonomy' => 'stm_additional_features',
					)
				);

				do_action(
					'stm_listings_load_template',
					'filter/types/keywords',
					array(
						'position' => 'bottom',
					)
				);
			endif;
			?>

		</div>

		<!--View type-->
		<input type="hidden" id="stm_view_type" name="view_type" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 'view_type' ) ); ?>"/>
		<!--Filter links-->
		<input type="hidden" id="stm-filter-links-input" name="stm_filter_link" value=""/>
		<!--Popular-->
		<input type="hidden" name="popular" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 'popular' ) ); ?>"/>

		<input type="hidden" name="s" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 's' ) ); ?>"/>
		<input type="hidden" name="sort_order" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 'sort_order' ) ); ?>"/>

		<div class="sidebar-action-units">
			<input id="stm-classic-filter-submit" class="hidden" type="submit" value="<?php esc_attr_e( 'Show cars', 'stm_vehicles_listing' ); ?>"/>

			<a href="<?php echo esc_url( apply_filters( 'stm_filter_listing_link', '' ) ); ?>" class="button">
				<i aria-hidden="true" class="motors-icons-reset"></i>
				<span><?php esc_html_e( 'Reset all', 'stm_vehicles_listing' ); ?></span>
			</a>
		</div>

		<?php do_action( 'stm_listings_filter_after' ); ?>
	</div>
</form>
