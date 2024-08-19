<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$post_id               = get_the_ID();
$user_added_by         = get_post_meta( $post_id, 'stm_car_user', true );
$show_number           = apply_filters( 'motors_vl_get_nuxy_mod', false, 'stm_show_number' );
$show_whatsapp         = apply_filters( 'motors_vl_get_nuxy_mod', false, 'stm_show_seller_whatsapp' );
$show_email            = apply_filters( 'motors_vl_get_nuxy_mod', false, 'stm_show_seller_email' );
$nuxy_whatsapp_massage = apply_filters( 'motors_vl_get_nuxy_mod', false, 'stm_whatsapp_massage' );
$massage_template      = ( ! empty( $nuxy_whatsapp_massage ) ) ? $nuxy_whatsapp_massage : '';
$listing_url           = get_the_permalink( $post_id );
$listing_title         = get_the_title( $post_id );

$shortcodes = array(
	'{{listing_url}}'   => $listing_url,
	'{{listing_title}}' => $listing_title,
);

foreach ( $shortcodes as $shortcode => $value ) {
	if ( strpos( $massage_template, $shortcode ) !== false ) {
		$massage_template = str_replace( $shortcode, $value, $massage_template );
	}
}

if ( ! empty( $user_added_by ) ) :

	$user_data = get_userdata( $user_added_by );

	if ( $user_data ) :

		$user_fields = apply_filters( 'stm_get_user_custom_fields', $user_added_by );
		$is_dealer   = apply_filters( 'stm_get_user_role', false, $user_added_by );
		if ( $is_dealer ) :
			$ratings = \MotorsVehiclesListing\User\UserReviewsController::get_reviews( $user_added_by );
			?>

			<div class="stm-listing-car-dealer-info">
				<a class="stm-no-text-decoration" href="<?php echo ( 'directory_classified' === apply_filters( 'motors_vl_get_nuxy_mod', 'directory_dealer', 'directory_selected' ) ) ? esc_url( apply_filters( 'stm_get_author_link', $user_added_by ) ) : '#!'; ?>">
					<h3 class="title">
						<?php echo wp_kses_post( apply_filters( 'stm_display_user_name', $user_added_by, '', '', '' ) ); ?>
					</h3>
				</a>
				<div class="clearfix">
					<div class="dealer-image">
						<div class="stm-dealer-image-custom-view">
							<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', $user_added_by ) ) ?? '#!'; ?>">
								<img src="<?php echo esc_url( ( ! empty( $user_fields['logo'] ) ) ? esc_url( $user_fields['logo'] ) : STM_LISTINGS_URL . '/assets/images/empty_dealer_logo.png' ); ?>" alt="" />
							</a>
						</div>
					</div>
					<?php if ( ! empty( $ratings['average'] ) ) : ?>
						<div class="dealer-rating">
							<div class="stm-rate-unit">
								<div class="stm-rate-inner">
									<div class="stm-rate-not-filled"></div>
									<div class="stm-rate-filled" style="width:<?php echo esc_attr( $ratings['average_width'] ); ?>"></div>
								</div>
							</div>
							<div class="stm-rate-sum">
								(<?php esc_html_e( 'Reviews', 'stm_vehicles_listing' ); ?> <?php echo esc_attr( $ratings['count'] ); ?>
								)
							</div>
						</div>
					<?php endif; ?>
				</div>

				<div class="dealer-contacts">
					<?php if ( ! empty( $user_fields['phone'] ) ) : ?>
						<div class="dealer-contact-unit phone">
							<i class="motors-icons-phone_2"></i>
							<?php if ( $show_number ) : ?>
								<div class="phone heading-font">
									<?php echo esc_html( $user_fields['phone'] ); ?>
								</div>
							<?php else : ?>
								<div class="phone heading-font">
									<?php echo esc_html( substr_replace( $user_fields['phone'], '*******', 3, strlen( $user_fields['phone'] ) ) ); ?>
								</div>
								<span class="stm-show-number" data-listing-id="<?php echo intval( get_the_ID() ); ?>" data-id="<?php echo esc_attr( $user_fields['user_id'] ); ?>">
									<?php echo esc_html__( 'Show number', 'stm_vehicles_listing' ); ?>
								</span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( $show_whatsapp && ! empty( $user_fields['phone'] ) && ! empty( $user_fields['stm_whatsapp_number'] ) ) : ?>
						<div class="dealer-contact-unit whatsapp">
							<a href="https://wa.me/<?php echo esc_attr( trim( preg_replace( '/[^0-9]/', '', $user_fields['phone'] ) ) ); ?>?text=<?php echo rawurlencode( $massage_template ); ?>" target="_blank">
								<div class="whatsapp-btn">
									<i class="motors-icons-whatsapp"></i>
									<span>
										<?php echo esc_html__( 'Chat via WhatsApp', 'stm_vehicles_listing' ); ?>
									</span>
								</div>
							</a>
						</div>
					<?php endif; ?>
					<?php if ( $show_email && ! empty( $user_fields['email'] ) && ! empty( $user_fields['show_mail'] ) ) : ?>
						<div class="dealer-contact-unit mail">
							<a href="mailto:<?php echo esc_attr( $user_fields['email'] ); ?>">
								<div class="email-btn">
									<i class="fas fa-envelope"></i>
									<span>
										<?php echo esc_html__( 'Message to dealer', 'stm_vehicles_listing' ); ?>
									</span>
								</div>
							</a>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $user_fields['location'] ) ) : ?>
						<div class="dealer-contact-unit address">
							<i class="motors-icons-pin_2"></i>
							<div class="address"><?php echo esc_attr( $user_fields['location'] ); ?></div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php else : ?>
			<div class="stm-listing-car-dealer-info stm-common-user">

				<div class="clearfix stm-user-main-info-c">
					<div class="image">
						<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', $user_added_by ) ); ?>">
							<?php if ( ! empty( $user_fields['image'] ) ) : ?>
								<img src="<?php echo esc_url( $user_fields['image'] ); ?>" alt=""/>
							<?php else : ?>
								<div class="no-avatar">
									<i class="fas fa-camera"></i>
								</div>
							<?php endif; ?>
						</a>
					</div>
					<a class="stm-no-text-decoration" href="<?php echo esc_url( apply_filters( 'stm_get_author_link', $user_added_by ) ); ?>">
						<h3 class="title"><?php echo wp_kses_post( apply_filters( 'stm_display_user_name', $user_added_by, '', '', '' ) ); ?></h3>
						<div class="stm-label"><?php esc_html_e( 'Private Seller', 'stm_vehicles_listing' ); ?></div>
					</a>
				</div>

				<div class="dealer-contacts">
					<?php if ( ! empty( $user_fields['phone'] ) ) : ?>
						<div class="dealer-contact-unit phone">
							<i class="fas fa-phone"></i>
							<?php if ( $show_number ) : ?>
								<div class="phone heading-font"><?php echo esc_html( $user_fields['phone'] ); ?></div>
							<?php else : ?>
								<div class="phone heading-font">
									<?php echo esc_html( substr_replace( $user_fields['phone'], '*******', 3, strlen( $user_fields['phone'] ) ) ); ?>
								</div>
								<span class="stm-show-number" data-listing-id="<?php echo intval( get_the_ID() ); ?>" data-id="<?php echo esc_attr( $user_fields['user_id'] ); ?>"><?php echo esc_html__( 'Show number', 'stm_vehicles_listing' ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $args['show_whatsapp'] ) && ! empty( $user_fields['phone'] ) && ! empty( $user_fields['stm_whatsapp_number'] ) ) : ?>
						<div class="dealer-contact-unit whatsapp">
							<a href="https://wa.me/<?php echo esc_attr( trim( preg_replace( '/[^0-9]/', '', $user_fields['phone'] ) ) ); ?>?text=<?php echo rawurlencode( $massage_template ); ?>" target="_blank">
								<div class="whatsapp-btn heading-font">
									<i class="motors-icons-whatsapp"></i>
									<span>
										<?php echo esc_html__( 'Chat via WhatsApp', 'stm_vehicles_listing' ); ?>
									</span>
								</div>
							</a>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $user_fields['email'] ) && ! empty( $user_fields['show_mail'] ) ) : ?>
						<div class="dealer-contact-unit mail">
							<i class="fas fa-envelope-open"></i>
							<div class="stm-label"><?php esc_html_e( 'Seller Email', 'stm_vehicles_listing' ); ?></div>
							<div class="address">
								<a class="heading-font" href="mailto:<?php echo esc_attr( $user_fields['email'] ); ?>">
									<?php echo esc_html( $user_fields['email'] ); ?>
								</a>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php
		endif;
	endif;
endif;
