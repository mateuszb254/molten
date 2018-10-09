$(function () {
    let form = $('#search-form');
    $(form).submit(function (e) {
        e.preventDefault();

        let name = $('input[name=search]').val();

        if($(this).hasClass('stopped')) {
            return;
        }

        if(name == '') return;

        $(this).addClass('stopped');

        let path = $(this).attr('action');

        let request_path = path + '/' + name;

        $.ajax({
            dataType: 'json',
            url: request_path,
            beforeSend: function () {
                $('.lower table tr:not(:first-child)').remove();
                $('.rank-pagination').remove();
            },
            success: function (data) {
                let tableRow = '<tr> <td class="rank">- </td> <td class="player">' + data.name + '</td> <td class="kingdom"><img src="' + kingdom(data.kingdom) + '" alt=""> </td> <td class="lvl">' + data.level + ' </td> </tr>';

                $('.lower table').append(tableRow);
                $(form).removeClass('stopped');
            },
            statusCode: {
                404: function () {
                    let tableRow = '<tr> <td class="rank"></td> <td class="player">Nie znaleziono postaci.</td> <td class="kingdom"> <img src="content/kingdom.png" alt=""> </td> <td class="lvl"></td></tr>';

                    $('.lower table').append(tableRow);
                    $(form).removeClass('stopped');
                }
            },
            error: function (e) {
                console.log(e);
            }
        });
    });
});