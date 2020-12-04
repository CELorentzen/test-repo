<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	<?php
	/**
	 * Hook: woocommerce_before_single_product_summary.
	 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
	 */
	do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary">
		<?php
		/**
		 * Hook: woocommerce_single_product_summary.
		 *
		 * @hooked woocommerce_template_single_title - 5
		 * @hooked woocommerce_template_single_rating - 10
		 * @hooked woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked woocommerce_template_single_add_to_cart - 30
		 * @hooked woocommerce_template_single_meta - 40
		 * @hooked woocommerce_template_single_sharing - 50
		 * @hooked WC_Structured_Data::generate_product_data() - 60
		 */
		do_action( 'woocommerce_single_product_summary' );
		?>
	</div>

<!-- Price Listing -->

<div class="priceContainer">
	<div class="priceListing">
			<?php
				$tilbud = get_field('tilbudToggle', $postID, false);
				//Check if the product is on sale
				if ( $tilbud = 0 ){
					echo '<div class="price"> Daglig Pris: ' . get_field('dagligPris', $postID, false) . ' DKK </div>';
					echo '<div class="price"> Ugentlig Pris: ' . get_field('ugentligPris', $postID, false) . ' DKK </div>';
					echo '<div class="price"> Månedlig Pris: ' . get_field('maanedligPris', $postID, false) . ' DKK </div>';
				}else{
					//Check if there's a sale on the daily price
					if ( get_field('dagligTilbud', $postID, false) != ''){
						echo '<div class="price"> Daglig Pris: ';
							echo '<div class="priceOriginal"> ' . get_field('dagligPris', $postID, false) . ' </div>';
							echo '<div class="price"> ' . get_field('dagligTilbud', $postID, false) . ' DKK </div>';
							echo '</div>';
					}else{echo '<div class="price"> Daglig Pris: ' . get_field('dagligPris', $postID, false) . ' DKK </div>';}
					
					//Check if there's a sale on the weekly price
					if ( get_field('ugentligTilbud', $postID, false) != ''){
						echo '<div class="price"> Ugentlig Pris: ';
							echo '<div class="priceOriginal"> ' . get_field('ugentligPris', $postID, false) . ' </div>';
							echo '<div class="price"> ' . get_field('ugentligTilbud', $postID, false) . ' DKK </div>';
							echo '</div>';
					}else{echo '<div class="price"> Ugentlig Pris: ' . get_field('ugentligPris', $postID, false) . ' DKK </div>';}
					
					//Check if there's a sale on the monthly price
					if ( get_field('maanedligTilbud', $postID, false) != ''){
						echo '<div class="price"> Månedlig Pris: ';
							echo '<div class="priceOriginal" >' . get_field('maanedligPris', $postID, false) . ' </div>';
							echo '<div class="price"> ' . get_field('maanedligTilbud', $postID, false) . ' DKK </div>';
							echo '</div>';
					}else{echo '<div class="price"> Månedlig Pris: ' . get_field('maanedligPris', $postID, false) . ' DKK </div>';}
				}
			?>
		</div>

	<!-- Udlejnings Form -->

		<div class="udlejningForm">
				<form method="POST">
					Navn: <input type="text" name="formName"><br>
					Telefon nummer: <input type="number" name="formPhone"><br>
					E-mail adresse: <input type="email" name="formMail"><br>
					<input type="submit" name="formSubmit"><br>
				</form>
		</div>

		<?php if (!empty($_POST['formName']) && !empty($_POST['formPhone'] && !empty($_POST['formMail']))){
		echo '<div class="formReciever">';
			echo '<div class="formOutput">Du har sendt en forespørgsel fra ' . $_POST['formMail'] . ' og ' . $_POST['formPhone'] . '.</div>';
			echo '<div class="formOutput">Du har sendt en foresprøgsel på følgende produkt:</div>';
			echo '<div class="formOutput">' . $product->get_title() . '</div>';
			echo '<div class="formOutput">' . $product->get_categories() . '</div>';
			echo '<div class="formOutput">Til priserne:';
			//Check for daily sale
			if ($tilbud = 1 && get_field('dagligTilbud') != 0) {echo'<div class="formOutput">' . get_field('dagligTilbud', $postID, false) . ' DKK dagligt </div>';}
			else {echo '<div class="formOutput">' . get_field('dagligPris', $postID, false) . ' DKK dagligt </div>';}
			//Check for weekly sale
			if ($tilbud = 1 && get_field('ugentligTilbud') != 0) {echo'<div class="formOutput">' . get_field('ugentligTilbud', $postID, false) . ' DKK dagligt </div>';}
			else {echo '<div class="formOutput">' . get_field('ugentligPris', $postID, false) . ' DKK ugenligt </div>';}
			//Check for monthly sale
			if ($tilbud = 1 && get_field('maanedligTilbud') != 0) {echo'<div class="formOutput">' . get_field('maanedligTilbud', $postID, false) . ' DKK dagligt </div>';}
			else {echo '<div class="formOutput">' . get_field('maanedligPris', $postID, false) . ' DKK månedligt </div>';}

			echo 'Forespørgslen er sendt på følgende dato: ' . date('d/m/y');
		echo '</div>';
		}
		?>
</div>
	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>


<?php do_action( 'woocommerce_after_single_product' ); ?>
