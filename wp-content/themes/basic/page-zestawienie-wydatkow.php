<?php
/**
 * Created by Adrian Jelonek
 * Date: 12.06.18
 * Time: 22:05
 */
get_header();
?>

<h1>Zestawienie dotychczasowych wydatków</h1>
<h2>Przefiltruj wydatki:</h2>

<!--Filtering form starts here-->
<form method="get">
    <label for="previous_expenses">Rodzaj wydatku:</label>
    <select id="previous_expenses" name="user_previous_expenses">
        <option value="" selected></option>
        <?php
        $userId = get_current_user_id();
        $dataBaseConn = new PDO(DSN, DB_USER, DB_PASSWORD);

        /*Loading all expenses names from a current user*/
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
        <!--Need type='hidden' to operate with admin-post.php-->
        <input type="hidden" name="action" value="show_filtered_expenses">
    </div>
    <div class="button">
        <button type="submit" autofocus>Przefiltruj swoje wydatki</button>
    </div>
</form>

<?php
/*Saving user filters*/
$userPreviousExpense = $_GET['user_previous_expenses'];
$userExpenseValueMin = $_GET['user_expense_value_min'];
$userExpenseValueMax = $_GET['user_expense_value_max'];
$userExpenseStartDate = $_GET['user_expense_date_start'];
$userExpenseEndDate = $_GET['user_expense_date_end'];

$linkToExpensesListPage = get_permalink(get_page_by_title('zestawienie wydatkow'));
$selectedExpensesArr = Expenses::loadAllExpenses($dataBaseConn, $userId, $userPreviousExpense, $userExpenseValueMin, $userExpenseValueMax, $userExpenseStartDate, $userExpenseEndDate);
?>
<!--Printing table with expenses-->
<table>
    <tr>
        <th>ID wydatku</th>
        <th>Nazwa użytkownika</th>
        <th>Typ wydatku</th>
        <th>Wartość wydatku</th>
        <th>Data wydatku</th>
    </tr>
    <?php foreach ($selectedExpensesArr as $expenseEntry) {
        $fullUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $smallUrl = $_SERVER['REQUEST_URI'];
        $userExpenseId = $expenseEntry['ID'];

        echo '<tr>';
        echo '<td>' . $expenseEntry['ID'] . '</td>';
        echo '<td>' . $expenseEntry['user_name'] . '</td>';
        echo '<td>' . $expenseEntry['expense_type'] . '</td>';
        echo '<td>' . $expenseEntry['expense_value'] . '</td>';
        echo '<td>' . $expenseEntry['expense_date'] . '</td>';
        echo '<td>' . "<a href=" . $smallUrl . "&expense=" . "$userExpenseId" . ">Usuń wydatek</a>";
        echo '</tr>';
    }

    /*Delete expenses form starts here*/
    if (strpos($fullUrl, 'expense=')) : ?>
        <p> Czy jesteś pewnien(a), że chcesz usunąć wydatek o ID: <?php echo $_GET['expense']; ?> z bazy danych? </p>
        <form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
            <input type="hidden" name="expense_id_to_delete_from_url" value="<?php echo $_GET['expense']; ?>">
            <div class="button">
                <button type="submit" name="yes">Tak</button>
            </div>
            <div class="button">
                <button type="submit" name="no">Nie</button>
            </div>
            <!--Need type='hidden' to operate with admin-post.php-->
            <input type="hidden" name="action" value="delete_expenses">
        </form>
    <?php endif; ?>
</table>

<?php get_footer() ?>