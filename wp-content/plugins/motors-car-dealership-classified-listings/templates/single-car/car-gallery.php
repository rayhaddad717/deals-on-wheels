<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$listing_id = get_the_ID();

//Getting gallery list
$gallery       = get_post_meta( $listing_id, 'gallery', true );
$video_preview = get_post_meta( $listing_id, 'video_preview', true );
$gallery_video = get_post_meta( $listing_id, 'gallery_video', true );

$as_sold          = get_post_meta( $listing_id, 'car_mark_as_sold', true );
$sold_badge_color = apply_filters( 'motors_vl_get_nuxy_mod', '', 'sold_badge_bg_color' );

$show_featured_btn = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_featured_btn' );

// remove "special" if the listing is sold
if ( ! empty( $as_sold ) ) {
	delete_post_meta( $listing_id, 'special_car' );
}

$special_car = get_post_meta( $listing_id, 'special_car', true );

$badge_text     = get_post_meta( $listing_id, 'badge_text', true );
$badge_bg_color = get_post_meta( $listing_id, 'badge_bg_color', true );

if ( empty( $badge_text ) ) {
	$badge_text = esc_html__( 'Special', 'stm_vehicles_listing' );
}

$badge_style = '';
if ( ! empty( $badge_bg_color ) ) {
	$badge_style = 'style=background-color:' . $badge_bg_color . ';';
}

$car_media = apply_filters( 'stm_get_car_medias', array(), $listing_id );

?>

<div class="stm-car-carousels">

	<?php if ( empty( $as_sold ) && ! empty( $special_car ) && 'on' === $special_car ) : ?>
		<div class="special-label h5" <?php echo esc_attr( $badge_style ); ?>><?php echo esc_html( $badge_text ); ?></div>
	<?php elseif ( apply_filters( 'stm_sold_status_enabled', true ) && ! empty( $as_sold ) ) : ?>
		<?php $badge_style = 'style=background-color:' . $sold_badge_color . ';'; ?>
		<div class="special-label h5" <?php echo esc_attr( $badge_style ); ?>><?php esc_html_e( 'Sold', 'stm_vehicles_listing' ); ?></div>
	<?php endif; ?>

	<div class="stm-gallery-actions">
		<?php if ( ! empty( $car_media['car_videos_count'] ) && $car_media['car_videos_count'] > 0 ) : ?>
			<div class="stm-car-medias">
				<div class="stm-listing-videos-unit stm-car-videos-<?php echo esc_attr( get_the_id() ); ?>">
					<i class="fas fa-film"></i>
					<span>
					<?php echo esc_html( $car_media['car_videos_count'] ); ?>
					<?php esc_html_e( 'Video', 'stm_vehicles_listing' ); ?>
				</span>
				</div>
			</div>

			<script>
				jQuery(document).ready(function () {

					jQuery(".stm-car-videos-<?php echo esc_js( $listing_id ); ?>").on('click', function () {
						jQuery(this).lightGallery({
							dynamic: true,
							dynamicEl: [
								<?php foreach ( $car_media['car_videos'] as $car_video ) : ?>
								{
									src: "<?php echo esc_url( $car_video ); ?>"
								},
								<?php endforeach; ?>
							],
							download: false,
							mode: 'lg-fade',
						})
					}); //click
				}); //ready

			</script>
		<?php endif; ?>

		<?php if ( ! empty( $show_featured_btn ) ) : ?>
			<div
				class="stm-gallery-action-unit stm-listing-favorite-action"
				data-id="<?php echo esc_attr( $listing_id ); ?>"
				data-toggle="tooltip"
				data-placement="bottom"
				title="Add to favorites"
			>
				<i class="fa-regular fa-star"></i>
			</div>
		<?php endif; ?>
	</div>

	<div class="stm-big-car-gallery owl-carousel">

		<?php
		if ( has_post_thumbnail() ) :
			$full_src = wp_get_attachment_image_src( get_post_thumbnail_id( $listing_id ), 'full' );
			?>
			<div class="stm-single-image" data-id="big-image-<?php echo esc_attr( get_post_thumbnail_id( $listing_id ) ); ?>">
				<a href="<?php echo esc_url( $full_src[0] ); ?>" class="stm_light_gallery" rel="stm-car-gallery">
					<?php the_post_thumbnail( 'full', array( 'class' => 'img-responsive' ) ); ?>
				</a>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $video_preview ) && ! empty( $gallery_video ) ) : ?>
			<?php $src = wp_get_attachment_image_src( $video_preview, 'full' ); ?>
			<?php if ( ! empty( $src[0] ) ) : ?>
				<div class="stm-single-image video-preview" data-id="big-image-<?php echo esc_attr( $video_preview ); ?>">
					<a class="light_gallery_iframe" data-src="<?php echo esc_url( $gallery_video ); ?>">
						<img src="<?php echo esc_url( $src[0] ); ?>" class="img-responsive" alt="<?php esc_attr_e( 'Video preview', 'stm_vehicles_listing' ); ?>"/>
					</a>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( ! empty( $gallery ) ) : ?>
			<?php foreach ( $gallery as $gallery_image ) : ?>
				<?php $src = wp_get_attachment_image_src( $gallery_image, 'full' ); ?>
				<?php $full_src = wp_get_attachment_image_src( $gallery_image, 'full' ); ?>
				<?php if ( ! empty( $src[0] ) && get_post_thumbnail_id( $listing_id ) !== $gallery_image ) : ?>
					<div class="stm-single-image" data-id="big-image-<?php echo esc_attr( $gallery_image ); ?>">
						<a href="<?php echo esc_url( $full_src[0] ); ?>" class="stm_light_gallery" rel="stm-car-gallery">
							<img src="<?php echo esc_url( $src[0] ); ?>" alt="<?php echo esc_attr( get_the_title( $listing_id ) ) . ' ' . esc_html__( 'full', 'stm_vehicles_listing' ); ?>"/>
						</a>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="stm-single-image placeholder">
				<?php $plchldr = 'plchldr350.png'; ?>
				<img
					src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/images/' . $plchldr ); ?>"
					class="img-responsive"
					alt="<?php esc_attr_e( 'Placeholder', 'stm_vehicles_listing' ); ?>"
				/>
			</div>
		<?php endif; ?>

	</div>

	<?php if ( has_post_thumbnail() && ( ! empty( $gallery ) || ( ! empty( $video_preview ) && ! empty( $gallery_video ) ) ) ) : ?>
		<div class="stm-thumbs-car-gallery owl-carousel">

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="stm-single-image" id="big-image-<?php echo esc_attr( get_post_thumbnail_id( $listing_id ) ); ?>">
					<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'img-responsive' ) ); ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $video_preview ) && ! empty( $gallery_video ) ) : ?>
				<?php $src = wp_get_attachment_image_src( $video_preview ); ?>
				<?php if ( ! empty( $src[0] ) ) : ?>
					<div class="stm-single-image video-preview" data-id="big-image-<?php echo esc_attr( $video_preview ); ?>">
						<a class="light_gallery_iframe" data-src="<?php echo esc_url( $gallery_video ); ?>">
							<img src="<?php echo esc_url( $src[0] ); ?>" alt="<?php esc_html_e( 'Video preview', 'stm_vehicles_listing' ); ?>"/>
						</a>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php
			if ( ! empty( $gallery ) ) :
				foreach ( $gallery as $gallery_image ) :
					$src = wp_get_attachment_image_src( $gallery_image );
					if ( ! empty( $src[0] ) && get_post_thumbnail_id( $listing_id ) !== $gallery_image ) :
						?>
						<div class="stm-single-image" id="big-image-<?php echo esc_attr( $gallery_image ); ?>">
							<img src="<?php echo esc_url( $src[0] ); ?>" alt="<?php echo esc_attr( get_the_title( $listing_id ) ) . ' ' . esc_attr__( 'full', 'stm_vehicles_listing' ); ?>"/>
						</div>
						<?php
					endif;
				endforeach;
			endif;
			?>
		</div>
	<?php endif; ?>
</div>

<!--Enable carousel-->
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		let big = $('.stm-big-car-gallery'),
			small = $('.stm-thumbs-car-gallery'),
			flag = false,
			duration = 800;

		let owlRtl = false;
		if ($('body').hasClass('rtl')) {
			owlRtl = true;
		}

		big.owlCarousel({
				rtl: owlRtl,
				items: 1,
				smartSpeed: 800,
				dots: false,
				nav: false,
				margin: 0,
				autoplay: false,
				loop: false,
				responsiveRefreshRate: 1000
			})
			.on('changed.owl.carousel', function (e) {
				let item = $('.owl-item', small);

				item.removeClass('current');
				item.eq(e.item.index).addClass('current');
				if (!flag) {
					flag = true;
					small.trigger('to.owl.carousel', [e.item.index, duration, true]);
					flag = false;
				}
			});

		small.owlCarousel(
			{
				rtl: owlRtl,
				items: 5,
				smartSpeed: 800,
				dots: false,
				margin: 22,
				autoplay: false,
				nav: true,
				navElement: 'div',
				loop: false,
				navText: [],
				responsiveRefreshRate: 1000,
				responsive: {
					0: {
						items: 2
					},
					500: {
						items: 4
					},
					768: {
						items: 5
					},
					1000: {
						items: 5
					}
				}
			})
			.on('click', '.owl-item', function () {
				big.trigger('to.owl.carousel', [$(this).index(), 400, true]);
			})
			.on('changed.owl.carousel', function (e) {
				if (!flag) {
					flag = true;
					big.trigger('to.owl.carousel', [e.item.index, duration, true]);
					flag = false;
				}
			});

		if ( $( '.stm-single-image', small ).length < 6 ) {
			$( '.stm-single-car-page .owl-controls' ).hide();
			small.css( { 'margin-top': '22px' } );
		}
	})
</script>
<?php //phpcs:enable ?>
