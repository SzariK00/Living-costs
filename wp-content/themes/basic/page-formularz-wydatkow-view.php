<div class="container">
    <div class="row">
        <div class="col header">
            <h1 class="header__logo">E-budżet.pl</h1>
            <h2 class="header__intro">Wprowadź swoje wydatki do bazy danych</h2>
        </div>
    </div>
<!--Add expenses form starts here-->
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
                    <input class="form-control" type="text" id="new_expense" name="user_new_expense" aria-describedby="user_new_expense">
                    <small id="user_new_expense" class="form-text text-muted">
                        Nazwa wydatku nie może przekraczać 25 znaków.
                    </small>
                </div>
                <div class="form-group">
                    <label for="expense_value">Wartość wydatku:</label>
                    <input class="form-control" type="number" pattern="[0-9]+([\,|\.][0-9]+)?" step="0.01" id="expense_value" name="user_expense_value" aria-describedby="user_expense_value">
                    <small id="user_expense_value" class="form-text text-muted">
                        Wartość wydatku nie może przekraczać 8 znaków. Możesz wpisywać liczby z użyciem kropki lub przecinka.
                    </small>
                </div>
                <div class="form-group">
                    <label for="expense_date">Data wydatku:</label>
                    <input class="form-control" type="date" id="expense_date" name="user_expense_date">
                    <!--Need type='hidden' to operate with admin-post.php-->
                    <input type="hidden" name="action" value="add_expenses">
                </div class="form-group">
                <div class="button form-group">
                    <button class="btn btn-primary btn-lg btn-block" type="submit" name="submit">Wyślij swoje wydatki</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <?php
            /*Error messages for validation process*/
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
                    "Wprowadziłeś za długą wartość wydatku. <br /> Pamiętaj, aby wartość wydatku nie przekraczała 10 znaków!" . '</p>';
            } elseif ($dateEmpty) {
                echo '<p class="alert alert-danger" role="alert">' .
                    'Nie wprowadziłeś daty wydatku!' . '</p>';
            }
            ?>
        </div>
    </div>
</div>
