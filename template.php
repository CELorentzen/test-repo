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
              echo '<div class="infoAfdeling infoHead">' . ucfirst($afdelingSelection) . '</div>'; //Department name taken from URL append. First letter is capitalized
              echo '<div class="infoTextContainer">';
              echo '<div class="infoFirma infoText"> OnlinePlus </div>';
              echo '<div class="infoAddress infoText"> Rugårdsvej 130D </div>';
              echo '<div class="infoAddress infoText"> 5000 Odense </div>';
              echo '<div class="infoPhone infoText"> Tlf: +45 70 22 11 44 </div>';
              echo '</div>';
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
                      echo '<div class="infoAfdeling infoHead">' . $currentAfdeling->name . '</div>';
                      echo '<div class="infoTextContainer">';
                      echo '<div class="infoFirma infoText"> OnlinePlus </div>';
                      echo '<div class="infoAddress infoText"> Rugårdsvej 130D </div>';
                      echo '<div class="infoAddress infoText"> 5000 Odense </div>';
                      echo '<div class="infoPhone infoText"> Tlf: +45 70 22 11 44 </div>';
                      echo '</div>';
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
                            echo '<div class="text">+45 ' . substr( get_field('telefon', $postID, false),0,4 ) . " " . substr( get_field('telefon', $postID, false),4,4 ) . '</div>';
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

          echo '<div class="footerContainer">';
              echo '<div class= footerContainerLeft>';
                echo '<div class="footerText footerHead">Er du i tvivl?</div>';
                echo '<div class=footerText>Er du i tvivl eller har du spørgsmål til vores produkter, er du mere end velkommen til at kontakte os på tlf. eller mail.</div>';
              echo '</div>';
              echo '<div class="footerContainerRight">';
                echo '<div class="footerContact footerPhone">';
                  echo '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-telephone-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">';
                    echo '<path fill-rule="evenodd" d="M2.267.98a1.636 1.636 0 0 1 2.448.152l1.681 2.162c.309.396.418.913.296 1.4l-.513 2.053a.636.636 0 0 0 .167.604L8.65 9.654a.636.636 0 0 0 .604.167l2.052-.513a1.636 1.636 0 0 1 1.401.296l2.162 1.681c.777.604.849 1.753.153 2.448l-.97.97c-.693.693-1.73.998-2.697.658a17.47 17.47 0 0 1-6.571-4.144A17.47 17.47 0 0 1 .639 4.646c-.34-.967-.035-2.004.658-2.698l.97-.969z"/>';
                  echo '</svg>';
                  echo '+45 70 22 11 44';
                echo '</div>';
                echo '<div class="footerContact">';
                  echo '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-envelope" fill="currentColor" xmlns="http://www.w3.org/2000/svg">';
                    echo '<path fill-rule="evenodd" d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z"/>';
                  echo '</svg>';
                  echo 'support@onlineplus.dk';
                echo '</div>';
              echo '</div>';
            echo '</div>';
        ?>

    </main>


</div>

<?php
include 'footer.php';
?>