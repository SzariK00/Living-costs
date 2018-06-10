<?php
/**
 * Created by Adrian Jelonek
 * Date: 07.06.18
 * Time: 18:22
 */
get_header(); ?>

<?php if (is_user_logged_in()): ?>
    <?php $q = new WP_Query(['pagename' => 'formularz-wydatkow']) ?>

    <?php if ( $q->have_posts() ) : while ( $q->have_posts() ) : $q->the_post(); ?>
        <!-- post -->
        <!--Redirect to formularz-wydatkow page-->
        <?php wp_redirect(get_permalink()); ?>
    <?php endwhile; ?>
        <!-- post navigation -->
    <?php else: ?>
        <!-- no posts found -->
    <?php endif; ?>
<?php else: ?>
    <!--TODO Dodać komunikat o konieczności dokonania rejestracji.-->
    <?php wp_register(); ?>
<?php endif; ?>

<?php get_footer(); ?>