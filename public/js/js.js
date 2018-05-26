        $(document).ready(function()
        {
            $(function(){
                $('#slides').slides({
                    preload: true,
                    preloadImage: 'img/loading.gif',
                    play: 3000,
                    pause: 2500,
                    generateNextPrev: true,
                    hoverPause: true,
                });
            });
            
            Cufon.replace('#news ul li .date, .slider .box_status p');
            Cufon.replace('#text h1, #text h2',{color: '-linear-gradient(#997d69, #734a3a)'});
            Cufon.replace('.box_main_mid .inner .right table', {textShadow:'1px 1px #000'});

        });
