<?php /* Template Name: OPTestTemplate */ ?>

<?php
include 'header.php';
?>

<div id="primary" class="content-area">

    <main id="main" class="site-main" role="main">

        <?php
        while (have_posts()) : the_post();
          get_template_part( 'template-parts/content', 'page');
        endwhile;
        ?>

        <?php
        $args = array(
          'post_type'       => 'medarbejder',
          'posts_per_page'  => -1,
          'category'        => '',
        );

        $workQuery = new WP_Query ( $args );

        if ($workQuery->have_posts() ){
            echo '<ul>';
            while ($workQuery->have_posts() ){
              $workQuery->the_post();
              echo '<li>' . get_the_title() . '</li>';
              echo '<p>' . get_the_post_thumbnail( get_the_ID(), 'full') . '</p>';
              echo '<p>' . get_field('stilling', $postID, false) . '</p>';
              echo '<p>' . get_field('telefon', $postID, false) . '</p>';
            }
            echo '</ul>';
        } else {
          //If CPT is empty
          echo 'Ingen medarbejdere ikke fundet';
        }
        wp_reset_postdata();

        ?>

    </main>


</div>

<?php
include 'footer.php';
//get_sidebar( 'content-bottom');
?>