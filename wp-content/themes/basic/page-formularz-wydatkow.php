<?php
/**
 * Created by Adrian Jelonek
 * Date: 07.06.18
 * Time: 18:24
 */
get_header();
echo get_page_by_title('zestawienie-wydatkow');
?>

<!--First expenses form.-->
<form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
    <label for="previous_expenses">Wybierz poprzedni rodzaj wydatku:</label>
    <select id="previous_expenses" name="user_previous_expenses">
        <option value="first" selected></option>
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
        <!--Need to operate with admin-post.php-->
        <input type="hidden" name="action" value="add_expenses">
    </div>
    <div class="button">
        <button type="submit">Wyślij swoje wydatki</button>
    </div>
</form>

<?php get_footer() ?>
