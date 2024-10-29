<?php

/*
 * Template Name: Login
 *
 * A very basic template for a login page. If you need to make changes to this template, copy it to your
 * theme directory, and the Behind Closed Doors will use that template instead.
 */

get_header();

// Really basic loop
if ( have_posts() ) : while ( have_posts() ) : the_post();
  
  the_content();
  
endwhile; endif;

if ( class_exists( 'WPBehindClosedDoors' ) )   // Make sure Behind Closed Doors is active
  WPBehindClosedDoors::RenderLoginForm( array() );      // and optionally render the login form
  
get_footer();