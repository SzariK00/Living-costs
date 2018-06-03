<?php
/**
 * Created by Adrian Jelonek
 * Date: 03.06.18
 * Time: 16:35
 */
get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) :    the_post(); ?>
  <!-- post -->
  <?php the_title() ?>
<?php endwhile; ?>
  <!-- post navigation -->
<?php else: ?>
  <!-- no posts found -->
<?php endif; ?>

<?php get_footer() ?>