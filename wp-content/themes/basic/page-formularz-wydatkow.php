<?php
/**
 * Created by Adrian Jelonek
 * Date: 07.06.18
 * Time: 18:24
 */
get_header();

/*Base variables*/
$userId = get_current_user_id();
$adminUrl = admin_url('admin-post.php');
$fullUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

/*Database connection*/
$dataBaseConn = new PDO(DSN, DB_USER, DB_PASSWORD);

/*Loading all expenses names from current user using static method*/
$expensesNamesArr = Expenses::loadAllExpensesNames($dataBaseConn, $userId);

/*Variables for validation process*/
$allEmpty = strpos($fullUrl, 'expense=all_empty');
$nameEmpty = strpos($fullUrl, 'expense=expense_name_empty');
$wrongName = strpos($fullUrl, 'expense=wrong_expense_name');
$longName = strpos($fullUrl, 'expense=to_long_expense_name');
$valueEmpty = strpos($fullUrl, 'expense=expense_value_empty');
$wrongValue = strpos($fullUrl, 'expense=wrong_expense_value');
$highValue = strpos($fullUrl, 'expense=expense_value_too_high');
$dateEmpty = strpos($fullUrl, 'expense=expense_date_empty');

/*Visible layer implementation*/
require 'page-formularz-wydatkow-view.php';

get_footer();
