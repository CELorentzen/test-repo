<?php /* Template Name: OPTilbudTemplate */ ?>

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

    </main>


</div>

<?php
include 'footer.php';
//get_sidebar( 'content-bottom' );
?>