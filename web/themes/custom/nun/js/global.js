(function ($, Drupal) {
    $(document).ready(function() {
        $('body:not(".user-logged-in") .peel-nav .navbar-right li:last-child a, body:not(".user-logged-in") #block-nun-account-menu .navbar-right li:last-child a').removeAttr("data-toggle");

        $('body:not(".user-logged-in") .peel-nav .navbar-right li:last-child a, body:not(".user-logged-in") #block-nun-account-menu .navbar-right li:last-child a').text("Sign up / Log in");


  });
})(jQuery, Drupal);
