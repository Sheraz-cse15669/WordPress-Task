<?php

// Register the Ajax endpoint
add_action('wp_ajax_nopriv_get_architecture_projects', 'get_architecture_projects');
add_action('wp_ajax_get_architecture_projects', 'get_architecture_projects');

// Ajax callback function
function get_architecture_projects() {
    $is_logged_in = is_user_logged_in();

    // Query parameters
    $args = array(
        'post_type'      => 'project',
        'posts_per_page' => $is_logged_in ? 6 : 3,
        'tax_query'      => array(
            array(
                'taxonomy' => 'project_type',
                'field'    => 'slug',
                'terms'    => 'architecture',
            ),
        ),
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $query = new WP_Query($args);
    $response = array(
        'success' => true,
        'data'    => array(),
    );

    while ($query->have_posts()) {
        $query->the_post();
        $project_id = get_the_ID();
        $project_title = get_the_title();
        $project_link = get_permalink();

        $response['data'][] = array(
            'id'    => $project_id,
            'title' => $project_title,
            'link'  => $project_link,
        );
    }

    wp_reset_postdata();

    wp_send_json($response);
}

function enqueue_ajax_script() {
    wp_enqueue_script('ajax-script', get_template_directory_uri() . '/ajax-script.js', array('jquery'), '1.0', true);
    wp_localize_script('ajax-script', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_ajax_script');

// Function to fetch coffee-related text from the Coffee Ipsum API
function get_coffee_ipsum() {
    // API endpoint URL
    $api_url = 'https://coffeeipsum.com/api/v1/';

    $response = wp_remote_get($api_url);

    // Check if the request was successful
    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {

        $data = json_decode(wp_remote_retrieve_body($response));

        if (isset($data->paragraphs) && is_array($data->paragraphs) && !empty($data->paragraphs[0])) {

            return esc_html($data->paragraphs[0]);
        }
    }

    // Return a default message if there was an issue with the API request
    return 'Enjoy a cup of coffee!';
}

// Example usage:
$coffee_text = get_coffee_ipsum();
echo 'Coffee Ipsum: ' . $coffee_text;



// Function to fetch Kanye West quotes from the API
function get_kanye_quotes($count = 5) {

    $api_url = 'https://api.kanye.rest/';

    $quotes = array();

    // Loop to fetch quotes
    for ($i = 0; $i < $count; $i++) {

        $response = wp_remote_get($api_url);

        // Check if the request was successful
        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            // Decode the JSON response
            $data = json_decode(wp_remote_retrieve_body($response));

            if (!empty($data->quote)) {
                $quotes[] = esc_html($data->quote);
            }
        }
    }

    return $quotes;
}

$quotes = get_kanye_quotes(5);

// Display the quotes on a page
if (!empty($quotes)) {
    echo '<ul>';
    foreach ($quotes as $quote) {
        echo '<li>' . $quote . '</li>';
    }
    echo '</ul>';
} else {
    echo 'Unable to fetch Kanye West quotes.';
}


?>