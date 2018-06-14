<?php
/**
 * Created by Adrian Jelonek
 * Date: 12.06.18
 * Time: 22:05
 */
get_header();
?>

<h1>Zestawienie dotychczasowych wydatków</h1>
<h2>Dodaj filtry:</h2>
<form method="get">
    <label for="previous_expenses">Rodzaj wydatku:</label>
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
        <label for="expense_value_min">Minimalna wartość wydatku:</label>
        <input type="number" step="0.01" id="expense_value_min" name="user_expense_value_min">
    </div>
    <div>
        <label for="expense_value_max">Maksymalna wartość wydatku:</label>
        <input type="number" step="0.01" id="expense_value_max" name="user_expense_value_max">
    </div>
    <div>
        <label for="expense_date_start">Początkowa data wydatku:</label>
        <input type="date" id="expense_date_start" name="user_expense_date_start">
    </div>
    <div>
        <label for="expense_date_end">Końcowa data wydatku:</label>
        <input type="date" id="expense_date_end" name="user_expense_date_end">
        <!--Need to operate with admin-post.php-->
        <input type="hidden" name="action" value="show_filtered_expenses">
    </div>
    <div class="button">
        <button type="submit">Przefiltruj swoje wydatki</button>
    </div>
</form>

<?php
/*TODO: Może być za duży URL! Trzeba zabezpieczyć długość typu wydatków*/

$userPreviousExpense = $_GET['user_previous_expenses'];
$userExpenseValueMin = $_GET['user_expense_value_min'];
$userExpenseValueMax = $_GET['user_expense_value_max'];
$userExpenseStartDate = $_GET['user_expense_date_start'];
$userExpenseEndDate = $_GET['user_expense_date_end'];

$selectedExpensesArr = Expenses::loadAllExpenses($dataBaseConn, $userId, $userPreviousExpense, $userExpenseValueMin, $userExpenseValueMax, $userExpenseStartDate, $userExpenseEndDate);
?>
<table>
    <tr>
        <th>Nazwa użytkownika</th>
        <th>Typ wydatku</th>
        <th>Wartość wydatku</th>
        <th>Data wydatku</th>
    </tr>
<?php  foreach ($selectedExpensesArr as $expenseEntry) {
    echo '<tr>';
    echo '<td>' . $expenseEntry['user_name'] . '</td>';
    echo '<td>' . $expenseEntry['expense_type'] . '</td>';
    echo '<td>' . $expenseEntry['expense_value'] . '</td>';
    echo '<td>' . $expenseEntry['expense_date'] . '</td>';
    echo '</tr>';
}
?>
</table>
<?php get_footer() ?>

