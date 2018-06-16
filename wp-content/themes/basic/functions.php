<?php
/**
 * Created by Adrian Jelonek
 * Date: 07.06.18
 * Time: 20:21
 */

/*What is needed*/
require 'class/Expenses.php';
const DSN = 'mysql:host=localhost;dbname=projekt';

/*Setting connection to database*/
$dataBaseConn = new PDO(DSN, DB_USER, DB_PASSWORD);

/*Processing add expenses form*/
add_action('admin_post_add_expenses', 'add_expenses_to_db');

function add_expenses_to_db()
{
    global $dataBaseConn;

    /*Loading data from form*/
    if (isset($_POST['submit'])) {
        $currentUserId = get_current_user_id();
        $currentUserName = get_current_user();
        $userNewExpense = $_POST['user_new_expense'];
        $userExpenseValue = $_POST['user_expense_value'];
        $userExpenseDate = $_POST['user_expense_date'];
        $linkToExpensesForm = get_permalink(get_page_by_title('formularz wydatkow'));

        /*Checking data from form*/
        if (empty($userNewExpense) && empty($userExpenseValue) && empty($userExpenseDate)) {
            header("Location: $linkToExpensesForm" . "?expense=all_empty");
        } elseif (empty($userNewExpense)) {
            header("Location: $linkToExpensesForm" . "?expense=expense_name_empty");
        } elseif (!preg_match("/^[a-zA-Z0-9-ĄąŻżŹźĆćĘęÓóŁłŃńŚś ]*$/", $userNewExpense)) {
            header("Location: $linkToExpensesForm" . "?expense=wrong_expense_name");
        } elseif (strlen($userNewExpense) > 20) {
            header("Location: $linkToExpensesForm" . "?expense=to_long_expense_name");
        } elseif (empty($userExpenseValue)) {
            header("Location: $linkToExpensesForm" . "?expense=expense_value_empty");
        } elseif ((strpos($userExpenseValue, ',') || strpos($userExpenseValue, '.')) && strlen($userExpenseValue) > 9) {
            header("Location: $linkToExpensesForm" . "?expense=expense_value_too_high");
        } elseif ((!strpos($userExpenseValue, ',') || !strpos($userExpenseValue, '.')) && strlen($userExpenseValue) > 8) {
            header("Location: $linkToExpensesForm" . "?expense=expense_value_too_high");
        } elseif (!preg_match("/^[0-9-.,]*$/", $userExpenseValue)) {
            header("Location: $linkToExpensesForm" . "?expense=wrong_expense_value");
        } elseif (empty($userExpenseDate)) {
            header("Location: $linkToExpensesForm" . "?expense=expense_date_empty");
        } else {

            /*Adding expenses to database*/
            $addObjectWithExpense = new Expenses();
            $addObjectWithExpense->setUserId($currentUserId)->setUserName($currentUserName)->setExpenseType($userNewExpense)->setExpenseValue($userExpenseValue)->setExpenseDate($userExpenseDate);
            $addObjectWithExpense->saveToDB($dataBaseConn);

            /*Redirecting to expenses dashboard*/
            $showExpensesPage = get_page_by_title('zestawienie wydatkow');
            wp_redirect(get_permalink($showExpensesPage->ID));
        }
    }
}

/*Processing delete expenses form*/
add_action('admin_post_delete_expenses', 'delete_expenses_from_db');

function delete_expenses_from_db()
{
    if (isset($_POST['no'])) {
        /*Redirecting to expenses dashboard*/
        $showExpensesPage = get_page_by_title('zestawienie wydatkow');
        wp_redirect(get_permalink($showExpensesPage->ID));
    } elseif (isset($_POST['yes'])) {
        global $dataBaseConn;
        $expenseToDelById = $_POST['expense_id_to_delete_from_url'];
        Expenses::deleteExpenseFromExpensesById($dataBaseConn, $expenseToDelById);
        /*Redirecting to expenses dashboard*/
        $showExpensesPage = get_page_by_title('zestawienie wydatkow');
        wp_redirect(get_permalink($showExpensesPage->ID));
    }
}