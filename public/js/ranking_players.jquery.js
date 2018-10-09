$(function () {
    $('#search-form').submit(function (e) {
        e.preventDefault();

        var name = $('input[name=search]').val();
        var path = $(this).attr('action');

        var request_path = path + '/' + name;

        $.ajax({
            dataType: 'json',
            url: request_path,
            beforeSend: function () {
                $('.lower table tr:not(:first-child)').remove();
                $('.rank-pagination').remove();
            },
            success: function (data) {
                var tableRow = '<tr> <td class="rank">- </td> <td class="player">' + data.name + '</td> <td class="kingdom"><img src="' + kingdom(data.kingdom) + '" alt=""> </td> <td class="lvl">' + data.level + ' </td> </tr>';

                $('.lower table').append(tableRow);
            },
            statusCode: {
                404: function () {
                    var tableRow = '<tr> <td class="rank"></td> <td class="player">Nie znaleziono postaci.</td> <td class="kingdom"> <img src="content/kingdom.png" alt=""> </td> <td class="lvl"></td></tr>';

                    $('.lower table').append(tableRow);
                }
            },
            error: function (e) {
                console.log(e);
            }
        });
    });
});