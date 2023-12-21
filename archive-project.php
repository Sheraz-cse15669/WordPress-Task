<?php
/*
Template Name: Project Archive
*/

get_header();

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'post_type'      => 'project', // Replace 'project' with your custom post type
    'posts_per_page' => 6,
    'paged'          => $paged,
);

$query = new WP_Query($args);

if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
        // Display your project content here
        ?>
        <article <?php post_class(); ?>>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="entry-content">
                <?php the_excerpt(); ?>
            </div>
        </article>
        <?php
    endwhile;

    // Add pagination links
    echo '<div class="pagination">';
    echo paginate_links(array(
        'total'     => $query->max_num_pages,
        'current'   => max(1, get_query_var('paged')),
        'prev_text' => __('&laquo; Previous'),
        'next_text' => __('Next &raquo;'),
    ));
    echo '</div>';

else :
    // No posts found
    echo '<p>No projects found.</p>';

endif;

// Restore original post data
wp_reset_postdata();

get_footer();
?>
