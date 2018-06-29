jQuery(document).ready(function ($) {

    /*Bootstrap styles for form in index.php*/
    $(".login-username").addClass('form-group');
    $(".login-password").addClass('form-group');
    $(".login-remember").addClass('form-group');
    $(".login-submit").addClass('form-group');
    $(".input").addClass('form-control');
    $(".button.button-primary").addClass('btn btn-primary btn-lg btn-block');

    /*Previous expense value set in form*/
    document.querySelector("#previous_expenses").addEventListener("change", function () {
        document.querySelector("#new_expense").value = document.querySelector("#previous_expenses").value;
    });

});