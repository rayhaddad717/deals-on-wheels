<?php
/*
Plugin Name: Custom Car Shortcode
Description: A plugin to create a custom shortcode for displaying featured cars.
Version: 1.0
Author: Your Name
*/
//  custom function to show dynamic featured contents
function custom_featured_cars_shortcode($atts)
{
    global $wpdb;

    // Attributes with default values
    $atts = shortcode_atts(
        array(
            'limit' => 6, // Default number of cars to display
        ),
        $atts,
        'custom_featured_cars'
    );

    // Query to fetch cars from the database
    // Adjust this query based on how your cars are stored in the database
    $atts['limit'] = 6;
    // inner join wp_postmeta wppm
    // on wpp.ID = wppm.post_id
    $cars = [];
    $results = $wpdb->get_results("
       SELECT *
        FROM wp_posts wpp
        
        WHERE wpp.post_type = 'listings'
        and wpp.post_status = 'publish'
        LIMIT {$atts['limit']}
    ");
    foreach (array_slice($results, 0, 3) as $car) {
        $carImageResults = $wpdb->get_results("
       SELECT *
        FROM wp_posts wpp
        
        WHERE wpp.post_parent = {$car->ID}
        and wpp.post_mime_type = 'image/jpeg'
        LIMIT 1
    ");
        $carImage = $carImageResults[0];
        $cars[] = [
            'title' => $car->post_title,
            'image' => $carImage->guid,
        ];
    }
    echo '<pre> end';
    print_r($cars);
    echo '</pre>';
    // Start building the output
    ob_start();
    echo '
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"50px","bottom":"50px"}}},"className":"featured-car-section wow slideInRight delay-1000","layout":{"type":"constrained"}} -->
    <div class="wp-block-group featured-car-section wow slideInRight delay-1000" style="padding-top:50px;padding-bottom:50px">
    <!-- wp:heading {"textAlign":"center","style":{"typography":{"fontSize":"28px"},"color":{"text":"#222222"}}} -->
    <h2 class="has-text-align-center has-text-color" style="color:#222222;font-size:28px">FEATURED CARS</h2>
    <!-- /wp:heading -->

    <!-- wp:image {"id":19,"sizeSlug":"full","linkDestination":"none","className":"title-img"} -->
    <figure class="wp-block-image size-full title-img"><img src="' . esc_url(get_template_directory_uri()) . '/images/car-border.png" alt="" class="wp-image-19" /></figure>
    <!-- /wp:image -->

    <!-- wp:paragraph {"align":"center","style":{"color":{"text":"#8c8282"},"typography":{"fontSize":"14px"}},"className":"head-text"} -->
    <p class="has-text-align-center head-text has-text-color" style="color:#8c8282;font-size:14px">' . esc_html__("Lorem Ipsum is simply dummy text of the printing and typesetting industry.", "auto-car-dealership") . '</p>
    <!-- /wp:paragraph -->';

    if ($cars) {
        echo '<div class="wp-block-columns car-col">';
        echo '<!-- wp:columns {"className":"car-col"} -->
            <div class="wp-block-columns car-col">';
        foreach (array_slice($cars, 0, 3) as $car) {
?>
            <!-- wp:column {"className":"car-box"} -->
            <!-- <a href="<?php get_template_directory_uri() ?>/listings/mercedes-sls-amg/"> -->
            <div class="wp-block-column car-box"><!-- wp:image {"id":41,"sizeSlug":"full","linkDestination":"none"} -->
                <figure class="wp-block-image size-full"
                    style="overflow: hidden; width:100%; aspect-ratio:3/2;"><img src="<?php echo $car['image'] ?>" alt="" class="wp-image-41" style="    object-fit: cover;
    object-position: center;
    width: 100%;
    height: 100%;" /></figure>
                <!-- /wp:image -->

                <!-- wp:columns {"style":{"color":{"background":"#f0c541"}},"className":"price-col"} -->
                <div class="wp-block-columns price-col has-background" style="background-color:#f0c541"><!-- wp:column -->
                    <div class="wp-block-column"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"600"},"color":{"text":"#23393d"}},"className":"m-0 px-3"} -->
                        <p class="has-text-align-center m-0 px-3 has-text-color" style="color:#23393d;font-size:16px;font-style:normal;font-weight:600"><?php echo esc_html__($car['image'], 'auto-car-dealership'); ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:column -->

                    <!-- wp:column -->
                    <div class="wp-block-column"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"600"},"color":{"text":"#23393d"}},"className":"location-text m-0 px-3"} -->
                        <p class="has-text-align-center location-text m-0 px-3 has-text-color" style="color:#23393d;font-size:16px;font-style:normal;font-weight:600"><?php echo esc_html__('Mumbai', 'auto-car-dealership'); ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:column -->
                </div>
                <!-- /wp:columns -->

                <!-- wp:heading {"level":4,"style":{"color":{"text":"#222222"},"typography":{"fontSize":"14px","fontStyle":"normal","fontWeight":"700"}}} -->
                <h4 class="has-text-color" style="color:#222222;font-size:14px;font-style:normal;font-weight:700"><?php echo esc_html__($car['title'], 'auto-car-dealership'); ?></h4>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500","fontSize":"14px"},"color":{"text":"#847e7e"}}} -->
                <p class="has-text-color" style="color:#847e7e;font-size:14px;font-style:normal;font-weight:500"><?php echo esc_html__('Sed ut perspiciatis unde omnis iste natus error sit', 'auto-car-dealership'); ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:columns {"className":"car-features"} -->
                <div class="wp-block-columns car-features"><!-- wp:column {"style":{"color":{"background":"#f1eded","text":"#5d5252"}}} -->
                    <div class="wp-block-column has-text-color has-background" style="color:#5d5252;background-color:#f1eded"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"12px","fontStyle":"normal","fontWeight":"600"},"color":{"text":"#5d5252"}},"className":"car-year"} -->
                        <p class="has-text-align-center car-year has-text-color" style="color:#5d5252;font-size:12px;font-style:normal;font-weight:600"><?php echo esc_html__('2017', 'auto-car-dealership'); ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:column -->

                    <!-- wp:column {"style":{"color":{"background":"#f1eded"}}} -->
                    <div class="wp-block-column has-background" style="background-color:#f1eded"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"12px","fontStyle":"normal","fontWeight":"600"},"color":{"text":"#5d5252"}},"className":"car-auto"} -->
                        <p class="has-text-align-center car-auto has-text-color" style="color:#5d5252;font-size:12px;font-style:normal;font-weight:600"><?php echo esc_html__('Automatic', 'auto-car-dealership'); ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:column -->

                    <!-- wp:column {"style":{"color":{"background":"#f1eded"}}} -->
                    <div class="wp-block-column has-background" style="background-color:#f1eded"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"12px","fontStyle":"normal","fontWeight":"600"},"color":{"text":"#5d5252"}},"className":"car-capacity"} -->
                        <p class="has-text-align-center car-capacity has-text-color" style="color:#5d5252;font-size:12px;font-style:normal;font-weight:600"><?php echo esc_html__('21,000 ml', 'auto-car-dealership'); ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:column -->
                </div>
                <!-- /wp:columns -->
            </div>
            <!-- </a> -->
            <!-- /wp:column -->

        <?php
        }
        echo ' </div>
            <!-- /wp:columns --></div>';


        // Second row of cars
        echo '<div class="wp-block-columns car-col">';
        echo '<!-- wp:columns {"className":"car-col"} -->
            <div class="wp-block-columns car-col">';
        foreach (array_slice($results, 3, 6) as $car) {
        ?>
            <!-- wp:column {"className":"car-box"} -->
            <div class="wp-block-column car-box"><!-- wp:image {"id":41,"sizeSlug":"full","linkDestination":"none"} -->
                <figure class="wp-block-image size-full"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/feature-car1.png" alt="" class="wp-image-41" /></figure>
                <!-- /wp:image -->

                <!-- wp:columns {"style":{"color":{"background":"#f0c541"}},"className":"price-col"} -->
                <div class="wp-block-columns price-col has-background" style="background-color:#f0c541"><!-- wp:column -->
                    <div class="wp-block-column"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"600"},"color":{"text":"#23393d"}},"className":"m-0 px-3"} -->
                        <p class="has-text-align-center m-0 px-3 has-text-color" style="color:#23393d;font-size:16px;font-style:normal;font-weight:600"><?php echo esc_html__('$169,921', 'auto-car-dealership'); ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:column -->

                    <!-- wp:column -->
                    <div class="wp-block-column"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"600"},"color":{"text":"#23393d"}},"className":"location-text m-0 px-3"} -->
                        <p class="has-text-align-center location-text m-0 px-3 has-text-color" style="color:#23393d;font-size:16px;font-style:normal;font-weight:600"><?php echo esc_html__('Mumbai', 'auto-car-dealership'); ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:column -->
                </div>
                <!-- /wp:columns -->

                <!-- wp:heading {"level":4,"style":{"color":{"text":"#222222"},"typography":{"fontSize":"14px","fontStyle":"normal","fontWeight":"700"}}} -->
                <h4 class="has-text-color" style="color:#222222;font-size:14px;font-style:normal;font-weight:700"><?php echo esc_html__('SILVER AUDI', 'auto-car-dealership'); ?></h4>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500","fontSize":"14px"},"color":{"text":"#847e7e"}}} -->
                <p class="has-text-color" style="color:#847e7e;font-size:14px;font-style:normal;font-weight:500"><?php echo esc_html__('Sed ut perspiciatis unde omnis iste natus error sit', 'auto-car-dealership'); ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:columns {"className":"car-features"} -->
                <div class="wp-block-columns car-features"><!-- wp:column {"style":{"color":{"background":"#f1eded","text":"#5d5252"}}} -->
                    <div class="wp-block-column has-text-color has-background" style="color:#5d5252;background-color:#f1eded"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"12px","fontStyle":"normal","fontWeight":"600"},"color":{"text":"#5d5252"}},"className":"car-year"} -->
                        <p class="has-text-align-center car-year has-text-color" style="color:#5d5252;font-size:12px;font-style:normal;font-weight:600"><?php echo esc_html__('2017', 'auto-car-dealership'); ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:column -->

                    <!-- wp:column {"style":{"color":{"background":"#f1eded"}}} -->
                    <div class="wp-block-column has-background" style="background-color:#f1eded"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"12px","fontStyle":"normal","fontWeight":"600"},"color":{"text":"#5d5252"}},"className":"car-auto"} -->
                        <p class="has-text-align-center car-auto has-text-color" style="color:#5d5252;font-size:12px;font-style:normal;font-weight:600"><?php echo esc_html__('Automatic', 'auto-car-dealership'); ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:column -->

                    <!-- wp:column {"style":{"color":{"background":"#f1eded"}}} -->
                    <div class="wp-block-column has-background" style="background-color:#f1eded"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"12px","fontStyle":"normal","fontWeight":"600"},"color":{"text":"#5d5252"}},"className":"car-capacity"} -->
                        <p class="has-text-align-center car-capacity has-text-color" style="color:#5d5252;font-size:12px;font-style:normal;font-weight:600"><?php echo esc_html__('21,000 ml', 'auto-car-dealership'); ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:column -->
                </div>
                <!-- /wp:columns -->
            </div>
            <!-- /wp:column -->

<?php
        }

        echo '<!-- /wp:group --></div>';
    } else {
        echo '<p>No featured cars found.</p>';
    }

    // Return the content
    return ob_get_clean();
}
function register_custom_featured_cars_shortcode()
{
    add_shortcode('custom_featured_cars', 'custom_featured_cars_shortcode');
}
add_action('init', 'register_custom_featured_cars_shortcode');
?>