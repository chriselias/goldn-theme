<?php
/**
 * The template for displaying the footer.
 *
 * @package GeneratePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

	</div><!-- #content -->
</div><!-- #page -->

<?php
/**
 * generate_before_footer hook.
 *
 * @since 0.1
 */
do_action( 'generate_before_footer' );
?>

<div <?php generate_do_element_classes( 'footer' ); ?>>
	<?php
	/**
	 * generate_before_footer_content hook.
	 *
	 * @since 0.1
	 */
	do_action( 'generate_before_footer_content' );

	/**
	 * generate_footer hook.
	 *
	 * @since 1.3.42
	 *
	 * @hooked generate_construct_footer_widgets - 5
	 * @hooked generate_construct_footer - 10
	 */
	do_action( 'generate_footer' );

	/**
	 * generate_after_footer_content hook.
	 *
	 * @since 0.1
	 */
	do_action( 'generate_after_footer_content' );
	?>
</div><!-- .site-footer -->

<?php
/**
 * generate_after_footer hook.
 *
 * @since 2.1
 */
do_action( 'generate_after_footer' );

wp_footer();
?>


<?php

$widget = get_field("accessibility_widget", "option");
if ($widget === 'yes') { ?>
	<script data-account="REgnYgvRk6" src="https://cdn.userway.org/widget.js"></script>
<?php } ?>

</body>
</html>


<script type="text/javascript">
var _userway_config = {
/* uncomment the following line to override default position*/
/* position: 3,*/
/* uncomment the following line to override default size (values: small, large)*/
/* size: 'small', */
/* uncomment the following line to override default language (e.g., fr, de, es, he, nl, etc.)*/
/* language: 'en',*/
/* uncomment the following line to override color set via widget (e.g., #053f67)*/
/* color: '#053e67',*/
/* uncomment the following line to override type set via widget (1=person, 2=chair, 3=eye, 4=text)*/
/* type: '1', */
/* statement_text: 'Our Accessibility Statement', */
/* statement_url: 'http://www.example.com/accessibility', */
/* uncomment the following line to override support on mobile devices*/
/* mobile: true, */
account: 'REgnYgvRk6'
};
</script>
<script type="text/javascript" src="https://cdn.userway.org/widget.js"></script>