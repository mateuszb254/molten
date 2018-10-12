$(function () {
    let form = $('#search-form');
    $(form).submit(function (e) {
        e.preventDefault();

        let name = $('input[name=search]').val();

        if ($(this).hasClass('stopped')) {
            return;
        }

        if (name == '') return;

        $(this).addClass('stopped');

        let path = $(this).attr('action');

        let request_path = path + '/' + name;

        $.ajax({
            dataType: 'json',
            url: request_path,
            beforeSend: function () {
                $('.lower table tr:not(:first-child)').remove();
                $('.content-pagination').remove();
            },
            success: function (data) {
                if (data) {
                    tableRow = '<tr> <td class="rank">' + data.position + '</td> <td class="player">' + data.name + '</td> <td class="rank">' + data.wins + '</td><td class="rank">' + data.loses + '</td> <td class="kingdom"> <img src="' + kingdom(parseInt(data.kingdom)) + '" alt=""> </td> <td class="lvl">' + data.points + ' </td> </tr>';
                } else {
                    tableRow = '<tr> <td class="rank">- </td> <td class="player">Nie znaleziono gildii...</td> <td class="rank"></td><td class="rank"></td> <td class="kingdom"></td> <td class="lvl"></td> </tr>';
                }

                $('.lower table').append(tableRow);
                $(form).removeClass('stopped');
            },
            statusCode: {
                404: function () {
                    tableRow = '<tr> <td class="rank">- </td> <td class="player">Nie znaleziono gildii...</td> <td class="rank"></td><td class="rank"></td> <td class="kingdom"></td> <td class="lvl"></td> </tr>';

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