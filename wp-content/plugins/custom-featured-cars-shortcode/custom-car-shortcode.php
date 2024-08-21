<?php
/*
Plugin Name: Custom Car Shortcode
Description: A plugin to create a custom shortcode for displaying featured cars.
Version: 1.0
Author: Your Name
*/

function fetchCars(int $limit)
{
    try {
        global $wpdb;
        //map of all cars
        $cars = [];
        //fetch all cars
        $carsResults = $wpdb->get_results("
       SELECT post_title, ID
        FROM wp_posts wpp
        WHERE wpp.post_type = 'listings'
        and wpp.post_status = 'publish'
        LIMIT $limit
    ");
        //for each car, i need an image, the name, the description, the year, the drive type, the mileage, and the location
        foreach ($carsResults as $car) {
            //fetch all changes
            $allPostIDSResult = $wpdb->get_results("
        SELECT ID from wp_posts 
        WHERE post_parent = $car->ID or ID = $car->ID");

            //save the array of ids
            $allPostIDSResult = array_map(function ($post) {
                return $post->ID;
            }, $allPostIDSResult);

            $allPostIDsString = implode(',', $allPostIDSResult);
            //fetch the location
            $locationResults = $wpdb->get_results("
        SELECT meta_value,meta_key from wp_postmeta 
        WHERE post_id in ($allPostIDsString) and meta_key = 'stm_car_location'")[0];

            //fetch the title
            $carTitle = $car->post_title;
            //fetch the image
            $carImageResults = $wpdb->get_results("
        SELECT guid from wp_posts
        WHERE post_parent in ($allPostIDsString) and post_type = 'attachment' and post_mime_type like 'image/%'
        ORDER BY ID DESC
        ")[0];
            $carImage = $carImageResults->guid;
            $cars[] = [
                'title' => $carTitle,
                'image' => $carImage,
                'location' => $locationResults->meta_value
            ];
        }
        return $cars;
    } catch (Exception $e) {
        return [];
    }
}
add_filter('the_content', 'remove_empty_p', 11);
function remove_empty_p($content)
{
    //return preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
    return preg_replace('#<p></p>#i', '', $content);
}

// Custom function to show dynamic featured contents
function custom_featured_cars_shortcode($atts)
{
    // Attributes with default values
    $atts = shortcode_atts(
        array(
            'limit' => 6, // Default number of cars to display
        ),
        $atts,
        'custom_featured_cars'
    );

    // Fetch cars from the database
    // Assuming `fetchCars` is a function that retrieves cars based on the limit
    $cars = fetchCars($atts['limit']);

    if (!$cars) {

        return '<p>No featured cars found.</p>';
    }
    // Temporarily disable wpautop
    // Check if wpautop filter is active
    if (has_filter('the_content', 'wpautop')) {
        echo '<!-- wpautop is active -->';
    } else {
    }
    echo '<!-- wpautop is NOT active -->';
    remove_filter('the_content', 'wpautop');
    // Start output buffering
    ob_start();
?>
    <div class="wp-block-group featured-car-section wow slideInRight delay-1000" style="padding:50px 0;">
        <h2 class="has-text-align-center has-text-color" style="color:#222222;font-size:28px">FEATURED CARS</h2>
        <figure class="wp-block-image size-full title-img">
            <img src="<?php echo esc_url(get_template_directory_uri()) . '/images/car-border.png'; ?>" alt="" class="wp-image-19" />
        </figure>
        <p class="has-text-align-center head-text has-text-color" style="color:#8c8282;font-size:14px">
            <?php echo esc_html__("Lorem Ipsum is simply dummy text of the printing and typesetting industry.", "auto-car-dealership"); ?>
        </p>

        <div class="wp-block-columns car-col" style="gap:2rem;">
            <?php foreach ($cars as $car) : ?>
                <div class="wp-block-column car-box">
                    <figure class="wp-block-image size-full" style="overflow: hidden; width:100%; aspect-ratio:3/2;">
                        <img src="<?php echo esc_url($car['image']); ?>" alt="" class="wp-image-41" style="object-fit: cover; object-position: center; width: 100%; height: 100%;" />
                    </figure>

                    <div class="wp-block-columns price-col has-background" style="background-color:#f0c541">
                        <div class="wp-block-column">
                            <p class="has-text-align-center m-0 px-3 has-text-color" style="color:#23393d;font-size:16px;font-style:normal;font-weight:600">
                                <?php echo esc_html__('NEW', 'auto-car-dealership'); ?>
                            </p>
                        </div>

                        <div class="wp-block-column">
                            <p class="has-text-align-center location-text m-0 px-3 has-text-color" style="color:#23393d;font-size:16px;font-style:normal;font-weight:600"><?php echo esc_html__($car['location'], 'auto-car-dealership'); ?></p>
                        </div>
                    </div>

                    <h4 class="has-text-color" style="color:#222222;font-size:14px;font-style:normal;font-weight:700">
                        <?php echo esc_html__($car['title'], 'auto-car-dealership'); ?>
                    </h4>

                    <p class="has-text-color" style="color:#847e7e;font-size:14px;font-style:normal;font-weight:500">
                        <?php echo esc_html__('Sed ut perspiciatis unde omnis iste natus error sit', 'auto-car-dealership'); ?>
                    </p>

                    <div class="wp-block-columns car-features">
                        <div class="wp-block-column has-text-color has-background" style="color:#5d5252;background-color:#f1eded">
                            <p class="has-text-align-center car-year has-text-color" style="color:#5d5252;font-size:12px;font-style:normal;font-weight:600">
                                <?php echo esc_html__('2017', 'auto-car-dealership'); ?>
                            </p>
                        </div>

                        <div class="wp-block-column has-background" style="background-color:#f1eded">
                            <p class="has-text-align-center car-auto has-text-color" style="color:#5d5252;font-size:12px;font-style:normal;font-weight:600">
                                <?php echo esc_html__('Automatic', 'auto-car-dealership'); ?>
                            </p>
                        </div>

                        <div class="wp-block-column has-background" style="background-color:#f1eded">
                            <p class="has-text-align-center car-capacity has-text-color" style="color:#5d5252;font-size:12px;font-style:normal;font-weight:600">
                                <?php echo esc_html__('21,000 miles', 'auto-car-dealership'); ?>
                            </p>
                        </div>
                    </div>
                </div><?php endforeach; ?>
        </div>
    </div>
<?php


    $output = ob_get_clean();
    //remove tabs, newlines, or any consecutive spaces from the output so they are not replaced with empty paragraphs
    return preg_replace('/\r|\n|\t| {2,}/', '', $output);
}

// Register the shortcode
function register_custom_featured_cars_shortcode()
{
    add_shortcode('custom_featured_cars', 'custom_featured_cars_shortcode');
}
add_action('init', 'register_custom_featured_cars_shortcode');
