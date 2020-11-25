<?php /* Template Name: OPTestTemplate */ ?>

<?php
//include 'header.php';
get_header();
?>

<div id="primary" class="content-area">

    <main id="main" class="site-main" role="main">

        <?php
        while (have_posts()) : the_post();
          get_template_part( 'template-parts/content', 'page');
        endwhile;
        ?>
        
    <select name="event-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'> <!--Change page to match selected option-->
   <!--
    <form method="GET">
    <select name="afdeling" onchange='this.form.submit()'>
    -->

      <option value=""><?php echo esc_attr(__('Vælg Afdeling')); ?></option> 

    <?php 
        $option = '<option value="' . get_option('home') . '/medarbejdere/">Alle Medarbejdere</option>'; // Change page to default
        $categories = get_categories(); 
        foreach ($categories as $category) {
          $option .= '<option value="'.get_option('home').'/medarbejdere/'.$category->slug.'">';
          $option .= $category->cat_name;
          $option .= ' ('.$category->category_count.')';
          $option .= '</option>';
        }
        echo $option;
    ?>
</select>
<!-- </form> -->
        <?php

if(isset($wp_query->query_vars['afdeling_cat'])) {
  $afdelingCat = urldecode($wp_query->query_vars['afdeling_cat']);
  }
        $args = array(
          'post_type'       => 'medarbejder',
          'posts_per_page'  => -1,
          'order'           => 'ASC',
          'tax_query'       => array(
            array(
              'taxonomy'      => 'category',
              'field'         => 'name',
              'terms'         => 'salg',
            ),
          ),
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