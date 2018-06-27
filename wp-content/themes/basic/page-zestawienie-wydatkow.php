<?php
/**
 * Created by Adrian Jelonek
 * Date: 12.06.18
 * Time: 22:05
 */
get_header(); ?>

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
            <input type="number" pattern="[0-9]+([\,|\.][0-9]+)?" step="0.01" id="expense_value_min" name="user_expense_value_min">
        </div>
        <div>
            <label for="expense_value_max">Maksymalna wartość wydatku:</label>
            <input type="number" pattern="[0-9]+([\,|\.][0-9]+)?" step="0.01" id="expense_value_max" name="user_expense_value_max">
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
            <input type="hidden" name="expense">
        </div>
        <div class="button">
            <button type="submit">Przefiltruj swoje wydatki</button>
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
            $userExpenseId = $expenseEntry['ID'];
            $queries = $_SERVER['QUERY_STRING'];
            $expenseValueType = floatval($expenseEntry['expense_value']);
            $sumOfExpenses += $expenseValueType;

            /*Important! Need to prevent from duplicated URL query parameters*/
            if (isset($_GET['expense'])) {
                $position = strpos($queries, 'expense=');
                $subtract = strlen($queries) - $position;
                $link = "?" . substr($queries, 0, -$subtract) . 'expense=' . $userExpenseId;
                $linkHelperForDeletingExpenseProcess = "?" . substr($queries, 0, -$subtract);
            } elseif (!isset($_GET['expense'])) {
                $link = '?expense=' . $userExpenseId;
            }

            /*Drawing table with expenses*/
            echo '<tr>';
            echo '<td>' . $expenseEntry['ID'] . '</td>';
            echo '<td>' . $expenseEntry['user_name'] . '</td>';
            echo '<td>' . $expenseEntry['expense_type'] . '</td>';
            echo '<td>' . $expenseEntry['expense_value'] . '</td>';
            echo '<td>' . $expenseEntry['expense_date'] . '</td>';
            echo '<td>' . "<a href=" . $link . ">Usuń</a>" . '</td>';
        }
        echo '<tr>';
        echo '<td colspan="5">' . "Suma wydatków: " . $sumOfExpenses . '</td>';
        echo '</tr>';

        /*Delete expenses form starts here*/
        if ($_GET['expense']) : ?>
            <p> Czy jesteś pewnien(a), że chcesz usunąć wydatek o ID: <?php echo $_GET['expense']; ?> z bazy
                danych? </p>
            <form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
                <input type="hidden" name="expense_id_to_delete_from_url" value="<?php echo $_GET['expense']; ?>">
                <div class="button">
                    <button type="submit" name="yes" value="<?php echo $linkHelperForDeletingExpenseProcess; ?>">Tak
                    </button>
                </div>
                <div class="button">
                    <button type="submit" name="no" value="<?php echo $linkHelperForDeletingExpenseProcess; ?>">Nie
                    </button>
                </div>
                <!--Need type='hidden' to operate with admin-post.php-->
                <input type="hidden" name="action" value="delete_expenses">
            </form>
        <?php endif; ?>
    </table>

<?php
$expensesPerYear = Expenses::retrieveByYear($dataBaseConn, $userId, $userPreviousExpense);
$expensesPerMonth = Expenses::retrieveByMonth($dataBaseConn, $userId, $userPreviousExpense);
$expensesShares = Expenses::retrieveShares($selectedExpensesArr);
?>

    <div id="expensesSharesChart" style="height: 370px; width: 100%;"></div>
    <div id="expensesPerMontChart" style="height: 370px; width: 100%;"></div>
    <div id="expensesPerYearChart" style="height: 370px; width: 100%;"></div>

    <script>
        window.onload = function () {

            let chart2 = new CanvasJS.Chart("expensesPerYearChart", {
                animationEnabled: true,
                exportEnabled: true,
                theme: "light5",
                title: {
                    text: "Skumulowane wartości wybranych typów wydatków w latach."
                },
                legend: {
                    cursor: "pointer",
                    verticalAlign: "center",
                    horizontalAlign: "right",
                    itemclick: toggleDataSeries
                },
                data: [{
                    type: "column",
                    name: "",
                    indexLabel: "{y}",
                    yValueFormatString: "#,##0 zł",
                    showInLegend: false,
                    dataPoints: <?php echo json_encode($expensesPerYear, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart2.render();

            function toggleDataSeries(e) {
                if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                }
                else {
                    e.dataSeries.visible = true;
                }
                chart2.render();
            }

            let chart = new CanvasJS.Chart("expensesSharesChart", {
                animationEnabled: true,
                exportEnabled: true,
                title: {
                    text: "Udział wartościowy typów wydatków w wybranym czasie."
                },
                subtitles: [{
                    text: "Wartość w zł"
                }],
                data: [{
                    type: "pie",
                    showInLegend: false,
                    legendText: "{label}",
                    indexLabelFontSize: 16,
                    indexLabel: "{label} - #percent%",
                    yValueFormatString: "#,##0 zł",
                    dataPoints: <?php echo json_encode($expensesShares, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

            let chart3 = new CanvasJS.Chart("expensesPerMontChart", {
                animationEnabled: true,
                exportEnabled: true,
                theme: "light1",
                title: {
                    text: "Skumulowane wartości wybranych typów wydatków w miesiącach."
                },
                axisX: {
                    crosshair: {
                        enabled: true,
                        snapToDataPoint: true
                    }
                },
                axisY: {
                    title: "Wartość w zł",
                    crosshair: {
                        enabled: true,
                        snapToDataPoint: true
                    }
                },
                toolTip: {
                    enabled: true
                },
                data: [{
                    type: "area",
                    yValueFormatString: "#,##0 zł",
                    dataPoints: <?php echo json_encode($expensesPerMonth, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart3.render();


        };
    </script>

<?php get_footer() ?>