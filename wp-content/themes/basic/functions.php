<?php
/**
 * Created by Adrian Jelonek
 * Date: 07.06.18
 * Time: 20:21
 */

/*First expenses form action.*/
add_action('admin_post_add_expenses', 'process_expenses_from_user');

function process_expenses_from_user() {
    echo$_POST['user_new_expense'];
}