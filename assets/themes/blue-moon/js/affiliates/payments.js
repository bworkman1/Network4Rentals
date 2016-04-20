$(function() {

    $('.viewShow').hover(
        function() {
            $(this).children('.showThis').addClass('in');
        },
        function() {
            $(this).children('.showThis').removeClass('in');
        }
    );
    $('.toolTip').tooltip();
});