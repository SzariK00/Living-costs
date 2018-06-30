<?php
/**
 * Created by Adrian Jelonek
 * Date: 29.06.18
 * Time: 22:04
 */

get_header();

/*Database connection*/
$userId = get_current_user_id();
$dataBaseConn = new PDO(DSN, DB_USER, DB_PASSWORD);

/*Loading all expenses names from a current user*/
$expensesNamesArr = Expenses::loadAllExpensesNames($dataBaseConn, $userId);

/*Saving user filters*/
$userPreviousExpense = $_GET['user_previous_expenses'];
$userExpenseValueMin = $_GET['user_expense_value_min'];
$userExpenseValueMax = $_GET['user_expense_value_max'];
$userExpenseStartDate = $_GET['user_expense_date_start'];
$userExpenseEndDate = $_GET['user_expense_date_end'];
$linkToExpensesListPage = get_permalink(get_page_by_title('zestawienie wydatkow'));
$linkToExpensesForm = get_permalink(get_page_by_title('formularz wydatkow'));
$selectedExpensesArr = Expenses::loadAllExpenses($dataBaseConn, $userId, $userPreviousExpense, $userExpenseValueMin, $userExpenseValueMax, $userExpenseStartDate, $userExpenseEndDate);

/*Charts core data*/
$expensesPerYear = Expenses::retrieveByYear($dataBaseConn, $userId, $userPreviousExpense);
$expensesPerMonth = Expenses::retrieveByMonth($dataBaseConn, $userId, $userPreviousExpense);
$expensesShares = Expenses::retrieveShares($selectedExpensesArr);

/*Variables needed for expenses loop*/
$queries = $_SERVER['QUERY_STRING'];
$position = strpos($queries, 'expense=');
$subtract = strlen($queries) - $position;
$linkHelperForDeletingExpenseProcess = "?" . substr($queries, 0, -$subtract);

/*Expenses loop function*/
function expensesLoop($selectedExpensesArr)
{
    global $queries;
    global $subtract;
    $sumOfExpenses = 0;
    $link = '';

    foreach ($selectedExpensesArr as $expenseEntry) {
        $userExpenseId = $expenseEntry['ID'];
        $expenseValueType = floatval($expenseEntry['expense_value']);
        $sumOfExpenses += $expenseValueType;

        /*Important! Need to prevent from duplicated URL query parameters*/
        if (isset($_GET['expense'])) {
            $link = "?" . substr($queries, 0, -$subtract) . 'expense=' . $userExpenseId;
        } elseif (!isset($_GET['expense'])) {
            $link = '?expense=' . $userExpenseId;
        }

        /*Drawing table with expenses*/
        echo '<tbody>';
        echo '<tr>';
        echo '<td>' . $expenseEntry['ID'] . '</td>';
        echo '<td>' . $expenseEntry['user_name'] . '</td>';
        echo '<td>' . $expenseEntry['expense_type'] . '</td>';
        echo '<td>' . $expenseEntry['expense_value'] . '</td>';
        echo '<td>' . $expenseEntry['expense_date'] . '</td>';
        echo '<td>' . "<a href=" . $link . ">Usuń</a>" . '</td>';
        echo '</tr>';
        echo '</tbody>';
    }
    echo '<tr>';
    echo '<td colspan="5">' . "Suma wydatków: " . $sumOfExpenses . '</td>';
    echo '</tr>';
}

/*Variable needed for deletion process*/
$isExpenseSet = $_GET['expense'];
$adminUrl = admin_url('admin-post.php');

/*Visible layer implementation*/
require 'page-zestawienie-wydatkow-view.php';
require 'charts.php';

get_footer();