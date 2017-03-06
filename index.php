<?php
/**
 * @package WordPress Theme by Occhio Web Development
 * @subpackage Mapping History theme created by Occhio Web Development
 */

get_header();

get_template_part( 'content', (get_post_format()) ? get_post_format() : get_post_type()  );

get_footer();