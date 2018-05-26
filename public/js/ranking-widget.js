$(function(){
    var elements =  $('.right .players, .right .guilds');

    var clickable;
    $(elements).click(function(e) {
        e.preventDefault();
        var clickedElement = $(e.target);

        if($(clickedElement).hasClass('active')) {
            return;
        }
        if(!clickable && clickable !== undefined) {
            return;
        }


        var animationTime = 500;

        var tables = $('.right_inner table');

        var clickedTable = $('.right_inner table.'+$(clickedElement).attr('class'));

        var count = $(clickedTable).find('tr').length;

        clickable = false;

        $(tables).animate({
            'height': 0
        }, animationTime, function(){
            $(tables).hide();
            $(clickedTable).show();
            $(clickedTable).animate({
                'height': (count*25)+5+'px'
            }, animationTime, function(){
                clickable = true;
            });
        });



        $(elements).removeClass('active');
        clickedElement.addClass('active');
    });
});