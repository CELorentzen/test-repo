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

        <form method="GET">
        <select name="afdeling" onchange='this.form.submit()'> <!--Change page to match selected option-->

        <option value=""><?php echo esc_attr(__('Vælg Afdeling')); ?></option> 

        <?php 
            $option = '<option value="all">Alle Medarbejdere</option>'; // Change page to default
            $afdelinger = get_categories(); 
            foreach ($afdelinger as $afdeling) {
              $option .= '<option value="'.$afdeling->slug.'">';
              $option .= $afdeling->cat_name;
              $option .= ' ('.$afdeling->category_count.')';
              $option .= '</option>';
            }
            echo $option;
        ?>
        </select>
      </form>

        <?php

        $afdelingSelection = sanitize_text_field( get_query_var('afdeling')); //check URL for appended data
       
        if (isset($afdelingSelection)){
          
          //If a specific department is selected
          if ($afdelingSelection != 'all' && $afdelingSelection != ''){
            $args = array(
              'post_type'       => 'medarbejder',
              'posts_per_page'  => -1,
              'order'           => 'ASC',
              'tax_query'       => array(
                array(
                  'taxonomy'      => 'category',
                  'field'         => 'name',
                  'terms'         => $afdelingSelection,
                ),
              ),
            );
            
            //run The Loop to get each post
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

          }

          //If no department is selected, or the All option is selected
          else if ($afdelingSelection == 'all' || $afdelingSelection == '') {

            //Loop to sort the list by department
            $afdelingLoop = get_categories(); 
            foreach ($afdelingLoop as $currentAfdeling) {
              $args = array(
                'post_type'       => 'medarbejder',
                'posts_per_page'  => -1,
                'order'           => 'ASC',
                'tax_query'       => array(
                  array(
                    'taxonomy'      => 'category',
                    'field'         => 'name',
                    'terms'         => $currentAfdeling,
                  ),
                ),
              );
              
              //run The Loop to get each post for each category
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
                echo 'Ingen medarbejdere fundet';
              }
              wp_reset_postdata();
            }
        }
      }
        ?>

    </main>


</div>

<?php
include 'footer.php';
//get_sidebar( 'content-bottom');
?>