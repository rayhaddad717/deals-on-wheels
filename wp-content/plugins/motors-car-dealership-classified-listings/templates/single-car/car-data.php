<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly

$data              = stm_get_single_car_listings();
$post_id           = get_the_ID();
$vin_num           = get_post_meta( $post_id, 'vin_number', true );
$show_registered   = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_registered' );
$registration_date = get_post_meta( $post_id, 'registration_date', true );
$show_history      = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_history' );
?>

<?php if ( ! empty( $data ) ) : ?>
	<div class="single-car-data">
		<?php
		/*If automanager, and no image in admin, set default image carfax*/
		$history_link_1   = get_post_meta( $post_id, 'history_link', true );
		$certified_logo_1 = get_post_meta( $post_id, 'certified_logo_1', true );

		if ( $show_history && ! empty( $certified_logo_1 ) ) :
			$certified_logo_1 = wp_get_attachment_image_src( $certified_logo_1, 'stm-img-255' );

			if ( ! empty( $certified_logo_1[0] ) ) {
				$certified_logo_1 = $certified_logo_1[0];
				?>
				<div class="text-center stm-single-car-history-image">
					<a href="<?php echo esc_url( $history_link_1 ); ?>" target="_blank">
						<img src="<?php echo esc_url( $certified_logo_1 ); ?>" class="img-responsive dp-in" alt=""/>
					</a>
				</div>
				<?php
			}
		endif;
		?>
		<table>
			<?php foreach ( $data as $data_value ) : ?>
				<?php
				$affix = '';
				if ( ! empty( $data_value['number_field_affix'] ) ) {
					$affix = $data_value['number_field_affix'];
				}

				if ( 'price' !== $data_value['slug'] ) :
					$data_meta = get_post_meta( $post_id, $data_value['slug'], true );

					if ( ! empty( $data_meta ) && ! apply_filters( 'is_empty_value', $data_meta ) ) :
						?>
						<tr>
							<td class="t-label"><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $data_value['single_name'], 'Category name' ) ); ?></td>
							<?php if ( ! empty( $data_value['numeric'] ) ) : ?>
								<td class="t-value h6"><?php echo wp_kses_post( ucfirst( $data_meta . $affix ) ); ?></td>
							<?php else : ?>
								<?php
								$data_meta_array = explode( ',', $data_meta );
								$datas           = array();
								if ( ! empty( $data_meta_array ) ) {
									foreach ( $data_meta_array as $data_meta_single ) {
										$data_meta = get_term_by( 'slug', $data_meta_single, $data_value['slug'] );
										if ( ! empty( $data_meta->name ) ) {
											$datas[] = $data_meta->name . $affix;
										}
									}
								}
								?>
								<td class="t-value h6"><?php echo esc_html( implode( ', ', $datas ) ); ?></td>
							<?php endif; ?>
						</tr>
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>

			<!--VIN NUMBER-->
			<?php if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_vin' ) && ! empty( $vin_num ) ) : ?>
				<tr>
					<td class="t-label"><?php esc_html_e( 'Vin', 'stm_vehicles_listing' ); ?></td>
					<td class="t-value t-vin h6"><?php echo wp_kses_post( $vin_num ); ?></td>
				</tr>
			<?php endif; ?>

			<!--Registered Date-->
			<?php if ( $show_registered ) : ?>
				<tr>
					<td class="t-label"><?php esc_html_e( 'Registered', 'stm_vehicles_listing' ); ?></td>
					<td class="t-value t-vin h6"><?php echo ! empty( $registration_date ) ? esc_html( $registration_date ) : esc_html__( 'N/A', 'stm_vehicles_listing' ); ?></td>
				</tr>
			<?php endif; ?>
		</table>
	</div>
<?php endif; ?>
