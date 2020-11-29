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

        <div class="dropdownContainer">
          <form method="GET">
          <select name="afdeling" class="dropdownSelector" onchange='this.form.submit()'> <!--Append selected option to URL-->

          <option value=""><?php echo esc_attr(__('Vælg Afdeling')); ?></option> 

          <?php 
              $option = '<option value="all">Alle afdelinger</option>'; // Change page to default
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
        </div>

        <?php

        $afdelingSelection = sanitize_text_field( get_query_var('afdeling')); //Get the appended data from the URL
       
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
              echo '<div class="loopContainer">'; //The loopContainer is also the grid container, so it's needed here eventhough the department loop isn't present
                  echo '<div class="afdelingInfoBox">';
                    echo 'afdeling';
                  echo '</div>';
                  while ($workQuery->have_posts() ){
                    $workQuery->the_post();
                    echo '<div class="medarbejderContainer" tabindex="0">';
                    echo '<div class="gradientContainer">';
                      echo '<div class="image">' . get_the_post_thumbnail( get_the_ID(), 'full') . '</div>';
                    echo '</div>';
                    echo '<div class=medarbejderInfo>';
                      echo '<div class="text title">' . get_the_title() . '</div>';
                      echo '<div class="text">' . get_field('stilling', $postID, false) . '</div>';
                      echo '<div class="hiddenInfo">';
                        echo '<div class="text">' . get_field('telefon', $postID, false) . '</div>';
                      echo '</div>';
                    echo '</div>';
                  echo '</div>';
                  }

              echo '</div>';

            } else {
              //If CPT is empty
              echo 'Ingen medarbejdere fundet';
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
                echo '<div class="loopContainer">';
                    echo '<div class="afdelingInfoBox">';
                      echo 'afdeling';
                    echo '</div>';
                    while ($workQuery->have_posts() ){
                      $workQuery->the_post();
                      echo '<div class="medarbejderContainer" tabindex="0">';
                        echo '<div class="gradientContainer">';
                          echo '<div class="image">' . get_the_post_thumbnail( get_the_ID(), 'full') . '</div>';
                        echo '</div>';
                        echo '<div class=medarbejderInfo>';
                          echo '<div class="text title">' . get_the_title() . '</div>';
                          echo '<div class="text">' . get_field('stilling', $postID, false) . '</div>';
                          echo '<div class="hiddenInfo">';
                            echo '<div class="text">+45 ' . substr( get_field('telefon', $postID, false),0,4 ) . " " . substr( get_field('telefon', $postID, false),0,4 ) . '</div>';
                          echo '</div>';
                        echo '</div>';
                      echo '</div>';
                    }

                echo '</div>';
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
?>