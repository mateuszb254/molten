$(function () {
    const articlesWrapper = $('#news > ul');
    const seeMoreButton = $('.see_more')
    const articlesPerPage = parseInt(articlesWrapper.attr('data-articles-per-page'));
    const countAllArticles = parseInt(articlesWrapper.attr('data-count-articles'));

    let countDisplayedArticles = parseInt($('ul').length);
    let requestUrl = articlesWrapper.attr('data-url');

    $(seeMoreButton).click(function (e) {
        e.preventDefault();

        $.ajax({
            dataType: "json",
            url: requestUrl,
            success: function (data) {
                let html = '';

                for (let i = 0; i < data.length; i++) {
                    let article = data[i];

                    let createdAt = article.createdAt;
                    let date = new Date(createdAt);

                    let months = [
                        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                    ];

                    html += '<li id="' + article.id + '">';
                    html += '<div class="date"> <p><big>' + date.getDate() + '</big><br/>' + months[date.getMonth()] + '</p></div>';
                    html += '<div class="image"> <div class="image_container"> <img src="content/thumb.png" alt=""/> <div href="" title="" class="frame"></div><p>Autor:' + article.author + '</p></div></div>';
                    html += '<div class="desc"> <h2>' + article.title + '.</h2> <p>' + article.content + '</p> <a href="" title="" class="button_more"></a> </div>';
                    html += '</li>';
                }

                articlesWrapper.append(html);
            }
        });

        countDisplayedArticles += articlesPerPage;

        if (countDisplayedArticles >= countAllArticles) {
            $(seeMoreButton).remove();
        }

        let splitteddApiUrl = requestUrl.split('/');
        splitteddApiUrl[3] = countDisplayedArticles;

        let newRequestUrl = requestUrl = splitteddApiUrl.join('/');

        articlesWrapper.attr('data-url', newRequestUrl);
    });
});