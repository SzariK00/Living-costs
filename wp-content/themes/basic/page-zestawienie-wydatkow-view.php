<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h1 class="header__logo">E-budżet.pl</h1>
            <h2 class="header__intro">Zestawienie dotychczasowych wydatków</h2>
        </div>
    </div>
    <!--Filtering form starts here-->
    <div class="row justify-content-around">
        <div class="col-6">
            <form method="get">
                <h3>Przefiltruj wydatki:</h3>
                <label for="previous_expenses">Rodzaj wydatku:</label>
                <select id="previous_expenses" name="user_previous_expenses">
                    <option value="" selected></option>
                    <?php
                    foreach ($expensesNamesArr as $key => $expenseName) {
                        echo "<option>$expenseName</option>";
                    }
                    ?>
                </select>
                <div>
                    <label for="expense_value_min">Minimalna wartość wydatku:</label>
                    <input type="number" pattern="[0-9]+([\,|\.][0-9]+)?" step="0.01" id="expense_value_min"
                           name="user_expense_value_min">
                </div>
                <div>
                    <label for="expense_value_max">Maksymalna wartość wydatku:</label>
                    <input type="number" pattern="[0-9]+([\,|\.][0-9]+)?" step="0.01" id="expense_value_max"
                           name="user_expense_value_max">
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
            <!--Printing table with expenses-->
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID wydatku</th>
                        <th scope="col">Nazwa użytkownika</th>
                        <th scope="col">Typ wydatku</th>
                        <th scope="col">Wartość wydatku</th>
                        <th scope="col">Data wydatku</th>
                    </tr>
                </thead>
                <?php expensesLoop($selectedExpensesArr) ?>
                <!--Delete expenses starts here-->
                <?php if ($isExpenseSet) : ?>
                    <p> Czy jesteś pewnien(a), że chcesz usunąć wydatek o ID: <?php echo $isExpenseSet; ?> z bazy
                        danych? </p>
                    <form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
                        <input type="hidden" name="expense_id_to_delete_from_url" value="<?php echo $isExpenseSet; ?>">
                        <div class="button">
                            <button type="submit" name="yes"
                                    value="<?php echo $linkHelperForDeletingExpenseProcess; ?>">Tak
                            </button>
                        </div>
                        <div class="button">
                            <button type="submit" name="no" value="<?php echo $linkHelperForDeletingExpenseProcess; ?>">
                                Nie
                            </button>
                        </div>
                        <!--Need type='hidden' to operate with admin-post.php-->
                        <input type="hidden" name="action" value="delete_expenses">
                    </form>
                <?php endif; ?>
            </table>
        </div>
        <div class="col-6">
            <div id="expensesSharesChart" style="height: 370px; width: 100%; margin-bottom: 20px; border: 1px dotted black"></div>
            <div id="expensesPerMontChart" style="height: 370px; width: 100%; margin-bottom: 20px; border: 1px dotted black"></div>
            <div id="expensesPerYearChart" style="height: 370px; width: 100%; margin-bottom: 20px; border: 1px dotted black"></div>
        </div>
    </div>
</div>