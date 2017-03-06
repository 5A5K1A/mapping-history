<?php
/**
 * @package WordPress Theme by Occhio Web Development
 * @subpackage Mapping History theme created by Occhio Web Development
 */
?>

		</div><!-- end container -->

	<!-- JavaScript templates -->
	<?php Template::Render('js-infowindow'); ?>
	<?php Template::Render('js-image-popup'); ?>

	<!-- WordPress footer -->
	<?php wp_footer(); ?>

	<?php if( OD_ENV == 'local' ) : ?>
	<!-- browser sync -->
	<script type='text/javascript' id="__bs_script__">
	//<![CDATA[
    document.write("<script async src='http://HOST:3000/browser-sync/browser-sync-client.2.11.1.js'><\/script>".replace("HOST", location.hostname));
	//]]>
	</script>
	<?php endif; ?>

	</body>
</html>
