<?php
/**
 * Created by Adrian Jelonek
 * Date: 07.06.18
 * Time: 18:22
 */

$registerLink = wp_registration_url();
$expensesFormPage = get_page_by_title('formularz wydatkow');

if (is_user_logged_in()) {
    wp_redirect(get_permalink($expensesFormPage->ID));
} else {
    get_header();
    require 'index.view.php';
    get_footer();
}

