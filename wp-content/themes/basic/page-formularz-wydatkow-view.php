<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="header__logo">E-budżet.pl</h1>
            <h2 class="header__intro">Wprowadź swoje wydatki do bazy danych</h2>
        </div>
    </div>
    <!--Adding expenses starts here-->
    <div class="row">
        <div class="col">
            <form action="<?php echo $adminUrl; ?>" method="post">
                <div class="form-group">
                    <label for="previous_expenses">Wybierz poprzedni rodzaj wydatku:</label>
                    <select class="form-control" id="previous_expenses" name="user_previous_expenses">
                        <option value="" selected></option>
                        <?php
                        /*Loading all expenses names from current user*/
                        foreach ($expensesNamesArr as $key => $expenseName) {
                            echo "<option>$expenseName</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="new_expense">Nowy rodzaj wydatku:</label>
                    <input class="form-control" type="text" id="new_expense" name="user_new_expense"
                           aria-describedby="user_new_expense" placeholder="np. zakupy spożywcze">
                    <small id="user_new_expense" class="form-text text-muted">
                        Nazwa wydatku nie może przekraczać 20 znaków (odstępy, tzw. "spacje", liczą się jak znaki).
                    </small>
                </div>
                <div class="form-group">
                    <label for="expense_value">Wartość wydatku:</label>
                    <input class="form-control" type="number" pattern="[0-9]+([\,|\.][0-9]+)?" step="0.01"
                           id="expense_value" name="user_expense_value" aria-describedby="user_expense_value"
                           placeholder="np. 34,45">
                    <small id="user_expense_value" class="form-text text-muted">
                        Maksymalnie liczba ośmiocyfrowa lub liczba ośmiocyfrowa z dwoma miejscami po przecinku.
                    </small>
                </div>
                <div class="form-group">
                    <label for="expense_date">Data wydatku:</label>
                    <input class="form-control" type="date" id="expense_date" name="user_expense_date">
                    <!--Need type='hidden' to operate with admin-post.php-->
                    <input type="hidden" name="action" value="add_expenses">
                </div class="form-group">
                <div class="button form-group">
                    <button class="btn btn-primary btn-lg btn-block" type="submit" name="submit">Dodaj swoje wydatki
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <!--Error messages for form validation process-->
            <?php validationErrors($allEmpty, $nameEmpty, $wrongName, $longName, $valueEmpty, $wrongValue, $highValue, $dateEmpty); ?>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <a href="<?php echo $linkToExpensesListPage; ?>" class="btn btn-secondary btn-lg btn-block" role="button"
               aria-pressed="true">Przejdź do zestawienia wydatków</a>
        </div>
    </div>
</div>