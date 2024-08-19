<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$listing_id = get_the_ID();

$price      = get_post_meta( $listing_id, 'price', true );
$sale_price = get_post_meta( $listing_id, 'sale_price', true );

$regular_price_label       = get_post_meta( $listing_id, 'regular_price_label', true );
$regular_price_description = get_post_meta( $listing_id, 'regular_price_description', true );
$special_price_label       = get_post_meta( $listing_id, 'special_price_label', true );
$instant_savings_label     = get_post_meta( $listing_id, 'instant_savings_label', true );

//Get text price field
$car_price_form       = get_post_meta( $listing_id, 'car_price_form', true );
$car_price_form_label = get_post_meta( $listing_id, 'car_price_form_label', true );


$show_price      = true;
$show_sale_price = true;

if ( empty( $price ) ) {
	$show_price = false;
}

if ( empty( $sale_price ) ) {
	$show_sale_price = false;
}

if ( ! empty( $price ) && empty( $sale_price ) ) {
	$show_sale_price = false;
}

if ( ! empty( $price ) && ! empty( $sale_price ) ) {
	if ( intval( $price ) === intval( $sale_price ) ) {
		$show_sale_price = false;
	}
}

if ( empty( $price ) && ! empty( $sale_price ) ) {
	$price           = $sale_price;
	$show_price      = true;
	$show_sale_price = false;
}

$sellOnline   = apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_woo_online' );
$isSellOnline = ( $sellOnline ) ? (boolean) ! empty( get_post_meta( $listing_id, 'car_mark_woo_online', true ) ) : false;//phpcs:ignore
?>
<?php //SINGLE REGULAR PRICE ?>
<?php if ( $show_price && ! $show_sale_price ) : ?>

	<?php if ( $isSellOnline ) : ?>
		<a id="buy-car-online" class="buy-car-online-btn" href="#" data-id="<?php echo esc_attr( $listing_id ); ?>" data-price="<?php echo esc_attr( $price ); ?>" >
	<?php else : ?>
		<?php if ( ! empty( $car_price_form ) && 'on' === $car_price_form ) : ?>
			<a href="#" class="rmv_txt_drctn" data-toggle="modal" data-target="#get-car-price">
		<?php endif; ?>
	<?php endif; ?>

	<div class="single-car-prices">
		<div class="single-regular-price text-center">
			<?php if ( ! empty( $car_price_form_label ) ) : ?>
				<span class="h3"><?php echo esc_html( $car_price_form_label ); ?></span>
			<?php else : ?>
				<?php if ( $isSellOnline ) : ?>
					<span class="labeled"><?php esc_html_e( 'BUY CAR ONLINE:', 'stm_vehicles_listing' ); ?></span>
				<?php else : ?>
					<?php if ( ! empty( $regular_price_label ) ) : ?>
						<span class="labeled"><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $regular_price_label, 'Regular Price Label' ) ); ?></span>
					<?php endif; ?>
				<?php endif; ?>
				<span class="h3">
					<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
				</span>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $isSellOnline || ( ! empty( $car_price_form ) && 'on' === $car_price_form ) ) : ?>
		</a>
	<?php endif; ?>

	<?php if ( ! empty( $regular_price_description ) ) : ?>
		<div class="price-description-single"><?php echo esc_html( $regular_price_description ); ?></div>
	<?php endif; ?>

<?php endif; ?>

<?php //SINGLE REGULAR && SALE PRICE ?>
<?php if ( $show_price && $show_sale_price ) : ?>

	<div class="single-car-prices">
		<?php if ( ! empty( $car_price_form ) && 'on' === $car_price_form ) : ?>
			<a href="#" class="rmv_txt_drctn" data-toggle="modal" data-target="#get-car-price">
				<div class="single-regular-price text-center">
				<?php if ( ! empty( $car_price_form_label ) ) : ?>
					<span class="h3"><?php echo esc_html( $car_price_form_label ); ?></span>
				<?php endif; ?>
				</div>
			</a>
		<?php else : ?>
			<?php if ( $isSellOnline ) : ?>
			<a id="buy-car-online" class="buy-car-online-btn" href="#" data-id="<?php echo esc_attr( $listing_id ); ?>" data-price="<?php echo esc_attr( $sale_price ); ?>" >
		<?php endif; ?>
		<div class="single-regular-sale-price">
			<table>
				<?php if ( $isSellOnline ) : ?>
					<tr>
						<td colspan="2" style="border: 0; padding-bottom: 5px;" align="center">
							<span class="labeled"><?php esc_html_e( 'BUY CAR ONLINE', 'stm_vehicles_listing' ); ?></span>
						</td>
					</tr>
				<?php endif; ?>
				<tr>
					<td>
						<div class="regular-price-with-sale">
							<?php if ( ! empty( $regular_price_label ) ) : ?>
								<?php echo esc_html( $regular_price_label ); ?>
							<?php endif; ?>
							<?php if ( ! empty( $car_price_form_label ) ) : ?>
								<strong><?php echo esc_html( $car_price_form_label ); ?></strong>
							<?php else : ?>
								<strong>
									<?php echo wp_kses_post( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
								</strong>
							<?php endif; ?>

						</div>
					</td>
					<td>
						<?php
						if ( ! empty( $special_price_label ) ) :
							echo esc_html( $special_price_label );
							$mg_bt = '';
						else :
							$mg_bt = 'style=margin-top:0';
						endif;
						?>
						<div class="h4" <?php echo esc_attr( $mg_bt ); ?>><?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?></div>
					</td>
				</tr>
			</table>
		</div>
			<?php if ( $isSellOnline ) : ?>
				</a>
			<?php endif; ?>
		<?php endif; ?>
	</div>

	<?php if ( '' === $car_price_form && ! empty( $instant_savings_label ) ) : ?>
		<?php $savings = intval( $price ) - intval( $sale_price ); ?>
		<div class="sale-price-description-single">
			<?php echo esc_html( $instant_savings_label ); ?>
			<strong> <?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $savings ) ); ?></strong>
		</div>
	<?php endif; ?>
<?php endif; ?>

<?php if ( ! $show_price && ! $show_sale_price && ! empty( $car_price_form_label ) ) { ?>
	<?php if ( ! empty( $car_price_form ) && 'on' === $car_price_form ) : ?>
		<a href="#" class="rmv_txt_drctn" data-toggle="modal" data-target="#get-car-price">
	<?php endif; ?>

	<div class="single-car-prices">
		<div class="single-regular-price text-center">
			<span class="h3"><?php echo esc_html( $car_price_form_label ); ?></span>
		</div>
	</div>

	<?php if ( ! empty( $car_price_form ) && 'on' === $car_price_form ) : ?>
		</a>
	<?php endif; ?>

	<?php if ( ! empty( $regular_price_description ) ) : ?>
		<div class="price-description-single"><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $regular_price_description, 'Regular Price Description' ) ); ?></div>
	<?php endif; ?>
<?php } ?>
