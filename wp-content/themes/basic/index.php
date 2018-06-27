<?php
/**
 * Created by Adrian Jelonek
 * Date: 07.06.18
 * Time: 18:22
 */
?>

<?php if (is_user_logged_in()): ?>
    <?php $expensesFormPage = get_page_by_title('formularz wydatkow'); ?>
    <?php wp_redirect(get_permalink($expensesFormPage->ID)); ?>
<?php else: ?>
    <?php get_header(); ?>
    <h1>Aby korzystać z aplikacji musisz się zarejestrować.</h1>
    <a href="<?php echo wp_registration_url(); ?>">Zarejestruj się!</a>
    <?php wp_login_form(); ?>
<?php endif; ?>

<?php get_footer(); ?>