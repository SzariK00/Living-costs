<?php
/**
 * Created by Adrian Jelonek
 * Date: 07.06.18
 * Time: 18:24
 */
get_header(); ?>

<h1>Wprowadź wydatki do bazy danych</h1>

<!--Expenses form starts here.-->
<form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
    <label for="previous_expenses">Wybierz poprzedni rodzaj wydatku:</label>
    <select id="previous_expenses" name="user_previous_expenses">
        <option value="" selected></option>
        <?php
        $userId = get_current_user_id();
        $dsn = "mysql:host=localhost;dbname=projekt";
        $dataBaseConn = new PDO($dsn, DB_USER, DB_PASSWORD);

        /*Loading all expenses names from current user*/
        $expensesNamesArr = Expenses::loadAllExpensesNames($dataBaseConn, $userId);

        foreach ($expensesNamesArr as $key => $expenseName) {
            echo "<option>$expenseName</option>";
        }
        ?>
    </select>
    <div>
        <label for="new_expense">Nowy rodzaj wydatku:</label>
        <input type="text" id="new_expense" name="user_new_expense">
    </div>
    <div>
        <label for="expense_value">Wartość wydatku:</label>
        <input type="number" step="0.01" id="expense_value" name="user_expense_value">
    </div>
    <div>
        <label for="expense_date">Data wydatku:</label>
        <input type="date" id="expense_date" name="user_expense_date">
        <!--Need hidden to operate with admin-post.php-->
        <input type="hidden" name="action" value="add_expenses">
    </div>
    <div class="button">
        <button type="submit" name="submit">Wyślij swoje wydatki</button>
    </div>
</form>

<!--Throwing error messages after form validation process begins-->
<?php
$fullUrl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

if (strpos($fullUrl, 'expense=all_empty')) {
    echo '<p>' . 'Nie uzupełniłeś(aś) żadnego pola!' . '</p>';
} elseif (strpos($fullUrl, 'expense=expense_name_empty')) {
    echo '<p>' . 'Nie wprowadziłeś nazwy wydatku!' . '</p>';
} elseif (strpos($fullUrl, 'expense=wrong_expense_name')) {
    echo '<p>' . "Wprowadziłeś niepoprawną nazwę wydatku. <br /> Pamiętaj, aby nie używać znaków specjalnych!" . '</p>';
} elseif (strpos($fullUrl, 'expense=to_long_expense_name')) {
    echo '<p>' . "Za długa nazwa wydatku. <br /> Nazwa wydaku może mieć maksymalnie 25 znaków!" . '</p>';
} elseif (strpos($fullUrl, 'expense=expense_value_empty')) {
    echo '<p>' . 'Nie wprowadziłeś wartości wydatku!' . '</p>';
} elseif (strpos($fullUrl, 'expense=wrong_expense_value')) {
    echo '<p>' . "Wprowadziłeś niepoprawną wartość wydatku. <br /> Pamiętaj, aby nie używać znaków specjalnych!" . '</p>';
} elseif (strpos($fullUrl, 'expense=expense_value_too_high')) {
    echo '<p>' . "Wprowadziłeś za długą wartość wydatku. <br /> Pamiętaj, aby wartość wydatku nie przekraczała 10 znaków!" . '</p>';
} elseif (strpos($fullUrl, 'expense=expense_date_empty')) {
    echo '<p>' . 'Nie wprowadziłeś daty wydatku!' . '</p>';
}
?>

<?php get_footer() ?>
