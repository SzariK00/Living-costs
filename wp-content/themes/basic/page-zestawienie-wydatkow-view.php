<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h1 class="header__logo">E-budżet.pl</h1>
            <h2 class="header__intro">Zestawienie dotychczasowych wydatków</h2>
        </div>
    </div>
    <!--Filtering form starts here-->
    <div class="row justify-content-around">
        <div class="col-xl-6 order-2 order-xl-1">
            <form method="get">
                <h3>Przefiltruj wydatki:</h3>
                <div class="form-group">
                    <label for="previous_expenses">Rodzaj wydatku:</label>
                    <select class="form-control" id="previous_expenses" name="user_previous_expenses">
                        <option value="" selected></option>
                        <?php
                        foreach ($expensesNamesArr as $key => $expenseName) {
                            echo "<option>$expenseName</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="expense_value_min">Minimalna wartość wydatku:</label>
                    <input class="form-control" type="number" pattern="[0-9]+([\,|\.][0-9]+)?" step="0.01"
                           id="expense_value_min"
                           name="user_expense_value_min"
                           placeholder="np. 34,45">
                </div>
                <div class="form-group">
                    <label for="expense_value_max">Maksymalna wartość wydatku:</label>
                    <input class="form-control" type="number" pattern="[0-9]+([\,|\.][0-9]+)?" step="0.01"
                           id="expense_value_max"
                           name="user_expense_value_max"
                           placeholder="np. 55,25">
                </div>
                <div class="form-group">
                    <label for="expense_date_start">Początkowa data wydatku (od):</label>
                    <input class="form-control" type="date" id="expense_date_start" name="user_expense_date_start">
                </div>
                <div class="form-group">
                    <label for="expense_date_end">Końcowa data wydatku (do):</label>
                    <input class="form-control" type="date" id="expense_date_end" name="user_expense_date_end">

                    <!--Need type='hidden' to operate with admin-post.php-->
                    <input type="hidden" name="action" value="show_filtered_expenses">
                    <input type="hidden" name="expense">
                </div>
                <div class="button form-group">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">Przefiltruj swoje wydatki</button>
                </div>
                <?php if ($isExpenseSet || $isUserPreviousExpenseSet || $isValueMinSet || $isValueMaxSet || $isDateStartSet || $isDateEndSet) : ?>
                    <a href="<?php echo $siteShortUrl; ?>" class="btn btn-warning btn-lg btn-block" role="button"
                       aria-pressed="true">Zresetuj filtry</a>
                <?php endif; ?>
                <a href="<?php echo $linkToExpensesForm; ?>" class="btn btn-secondary btn-lg btn-block" role="button"
                   aria-pressed="true">Wróć do dodawania wydatków</a>
            </form>
            <!--Printing table with expenses-->
            <br/>
            <h4>Przefiltrowane wydatki:</h4>
            <?php if ($isExpenseSet) : ?>
                <div class="alert alert-warning" role="alert">
                    Czy jesteś pewnien(a), że chcesz usunąć wydatek o ID: <?php echo $isExpenseSet; ?> z bazy
                    danych?
                </div>
                <form class="to-delete" action="<?php echo $adminUrl; ?>" method="post">
                    <input type="hidden" name="expense_id_to_delete_from_url" value="<?php echo $isExpenseSet; ?>">
                    <button class="to-delete__yes btn btn-block btn-outline-success" type="submit" name="yes"
                            value="<?php echo $linkHelperForDeletingExpenseProcess; ?>">Tak
                    </button>
                    <button class="to-delete__no btn btn-block btn-outline-danger" type="submit" name="no"
                            value="<?php echo $linkHelperForDeletingExpenseProcess; ?>">Nie
                    </button>
                    <!--Need type='hidden' to operate with admin-post.php-->
                    <input type="hidden" name="action" value="delete_expenses">
                </form>
            <?php endif; ?>
            <div class="table-responsive">
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
                </table>
            </div>
        </div>
        <!--Printing charts-->
        <div class="col-xl-6 order-1 order-xl-2">
            <div id="expensesSharesChart"></div>
            <div id="expensesPerMontChart"></div>
            <div id="expensesPerYearChart"></div>
        </div>
    </div>
</div>