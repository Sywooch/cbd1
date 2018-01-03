$(document).ready(function() {
    $('ul.tabs li').click(function(){
        var tab_id = $(this).attr('data-tab');

        $('ul.tabs li').removeClass('current');
        $('.tab-content').removeClass('current');

        $(this).addClass('current');
        $("#"+tab_id).addClass('current');
    });

    $(window).scroll(function() {
        if ($(this).scrollTop() > 1){
            $('#top-menu .logo').css('width', '56px').css('height', 'auto');
        }
        else{
            $('#top-menu .logo').css('width', '112px');
        }
    });
});
