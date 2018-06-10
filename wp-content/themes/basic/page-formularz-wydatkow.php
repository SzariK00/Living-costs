<?php
/**
 * Created by Adrian Jelonek
 * Date: 07.06.18
 * Time: 18:24
 */
get_header(); ?>

<!--First expenses form.-->
<form action="<?php echo admin_url('admin-post.php'); ?>" method="post"> //
    <label for="previous_expenses">Wybierz poprzedni rodzaj wydatku:</label>
    <select id="previous_expenses" name="user_previous_expenses">
        <option value="first">Wydatek 1</option>
        <option value="second">Wydatek 2</option>
        <option value="third">Wydatek 3</option>
    </select>
    <div>
        <label for="new_expense">Nowy rodzaj wydatku:</label>
        <input type="text" id="new_expense" name="user_new_expense">
    </div>
    <div>
        <label for="expense_value">Wartość wydatku:</label>
        <input type="text" id="expense_value" name="user_expense_value">
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
