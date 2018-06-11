<?php
/**
 * Created by Adrian Jelonek
 * Date: 07.06.18
 * Time: 20:21
 */
require 'class/Expenses.php';

/*First expenses form action.*/
add_action('admin_post_add_expenses', 'process_expenses_from_user');

function process_expenses_from_user()
{
    $currentUserId = get_current_user_id();
    $currentUserName = get_current_user();
    $userNewExpense = $_POST['user_new_expense'];
    $userExpenseValue = $_POST['user_expense_value'];
    $userExpenseDate = $_POST['user_expense_date'];

    $dsn = "mysql:host=localhost;dbname=projekt";
    $dataBaseConn = new PDO($dsn, DB_USER, DB_PASSWORD);

    $addObject = new Expenses($currentUserId, $currentUserName, $userNewExpense, $userExpenseValue, $userExpenseDate);
    $addObject->saveToDB($dataBaseConn);
}