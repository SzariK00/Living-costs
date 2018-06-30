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
$linkToExpensesListPage = get_permalink(get_page_by_title('zestawienie wydatkow'));

/*Database connection*/
$dataBaseConn = new PDO(DSN, DB_USER, DB_PASSWORD);

/*Loading all expenses names from current user*/
$expensesNamesArr = Expenses::loadAllExpensesNames($dataBaseConn, $userId);

/*Variables for form validation process*/
$allEmpty = strpos($fullUrl, 'expense=all_empty');
$nameEmpty = strpos($fullUrl, 'expense=expense_name_empty');
$wrongName = strpos($fullUrl, 'expense=wrong_expense_name');
$longName = strpos($fullUrl, 'expense=to_long_expense_name');
$valueEmpty = strpos($fullUrl, 'expense=expense_value_empty');
$wrongValue = strpos($fullUrl, 'expense=wrong_expense_value');
$highValue = strpos($fullUrl, 'expense=expense_value_too_high');
$dateEmpty = strpos($fullUrl, 'expense=expense_date_empty');

/*Error messages function for form validation process*/
function validationErrors($allEmpty, $nameEmpty, $wrongName, $longName, $valueEmpty, $wrongValue, $highValue, $dateEmpty)
{
    if ($allEmpty) {
        echo '<p class="alert alert-danger" role="alert">' .
            'Nie uzupełniłeś(aś) żadnego pola!' . '</p>';
    } elseif ($nameEmpty) {
        echo '<p class="alert alert-danger" role="alert">' .
            'Nie wprowadziłeś nazwy wydatku!' . '</p>';
    } elseif ($wrongName) {
        echo '<p class="alert alert-danger" role="alert">' .
            "Wprowadziłeś niepoprawną nazwę wydatku. <br /> Pamiętaj, aby nie używać znaków specjalnych!" . '</p>';
    } elseif ($longName) {
        echo '<p class="alert alert-danger" role="alert">' .
            "Za długa nazwa wydatku. <br /> Nazwa wydaku może zawierać maksymalnie 25 znaków!" . '</p>';
    } elseif ($valueEmpty) {
        echo '<p class="alert alert-danger" role="alert">' .
            'Nie wprowadziłeś wartości wydatku!' . '</p>';
    } elseif ($wrongValue) {
        echo '<p class="alert alert-danger" role="alert">' .
            "Wprowadziłeś niepoprawną wartość wydatku. <br /> Pamiętaj, aby nie używać znaków specjalnych!" . '</p>';
    } elseif ($highValue) {
        echo '<p class="alert alert-danger" role="alert">' .
            "Wprowadziłeś za długą wartość wydatku!" . '</p>';
    } elseif ($dateEmpty) {
        echo '<p class="alert alert-danger" role="alert">' .
            'Nie wprowadziłeś daty wydatku!' . '</p>';
    }
}

/*Visible layer implementation*/
require 'page-formularz-wydatkow-view.php';

get_footer();
