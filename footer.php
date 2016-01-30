<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Mine
 */

?>

	</div><!-- #content -->
</div><!--header to footer-->

	<footer id="colophon" class="site-footer container-fluid" role="contentinfo">
		<div class="row">
			<div class="col-sm-4">
				<?php if ( dynamic_sidebar('footer-left')); ?>
			</div>
			<div class="col-sm-4">
				<?php if ( dynamic_sidebar('footer-center')); ?>
			</div>
			<div class="col-sm-4">
				<?php if ( dynamic_sidebar('footer-right')); ?>
			</div>
		</div>
		<div class="site-info">
			<p><span class="fa fa-copyright"></span> <?php echo date('Y'); ?> <?php echo sanitize_text_field(get_theme_mod('bpl_copyright')); ?></p>
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'mine' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'mine' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'mine' ), 'mine', '<a href="http://brandonlehr.com" rel="designer">Brandon Lehr</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
