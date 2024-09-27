@if(!isset($records))
    <?php $records = null;?>
@endif
<script src="{{admin_asset('js/flashcard/lodash.js')}}"></script>
<script>
    let isSwitching = false;
    let debounceTimeout = null;
    // nextCard function
      function  nextCard(e) {
        var currentCardId = $(".flashcard.active").data("card-id");
        if(currentCardId == $('.flashcard').length) return;
        $('.page').addClass('disable-links')
        var nextCardId = parseInt(currentCardId)+1;
        switchCard(nextCardId, "slideInRight", "slideOutLeft");
         $('.page').removeClass('disable-links')
    }
    // prevCard function
    function prevCard(e) {
        var currentCardId = $(".flashcard.active").data("card-id");
        if(currentCardId == 1) return;
        $('.page').addClass('disable-links')
        var prevCardId = parseInt(currentCardId)-1;
        switchCard(prevCardId, "slideInLeft", "slideOutRight");
        $('.page').removeClass('disable-links')
    }
    // switchCard function
    function switchCard(cardId, inClass, outClass, option = null) {
        if (isSwitching) {
            return;
        }
        isSwitching = true;

        // effect
        if (!inClass || !outClass) {
            inClass = "slideInRight";
            outClass = "slideOutLeft";
        }
        //
        // auto flip flash card
        let currentCard = $(".flashcard.active");
        if (currentCard.length == 1) {
            currentCard
                .addClass("animated")
                .addClass(outClass)
                .fadeOut(200)
                .promise()
                .done(function() {
                    $(".flashcard")
                        .removeClass(inClass)
                        .removeClass(outClass)
                        .removeClass("animated")
                        .removeClass('active');
                    $(".flashcard[data-card-id=" + cardId + "]")
                        .addClass("animated")
                        .addClass(inClass)
                        .addClass("active")
                        .fadeIn(200)
                        .promise()
                        .done(function() {
                            isSwitching = false;
                            clearTimeout(debounceTimeout);
                            debounceTimeout = setTimeout(() => {
                                addAudio(cardId)
                            }, 1500);
                        });
                });

        } else {
            $(".flashcard").removeClass("active");
            $(".flashcard[data-card-id=" + cardId + "]")
                .addClass("animated")
                .addClass(inClass)
                .addClass("active");
            isSwitching = false;
        }
        // pagination
        $('.total_counter').html(cardId + '/' + $('.flashcard').length)
        // auto flip flash card
        if (option == 'auto') {
            setTimeout(function() {
                $('#card' + cardId).prop('checked', true);
            }, 3500);
        }
        // add audio
        function addAudio(cardId) {
            $('#audio' + cardId).empty();
            let url = '/public/uploads/flashcard/' + $('#audio' + cardId).data("audio");
            if ($('input[name="sAudio"]').prop("checked")) {
                $('#audio' + cardId).append(
                    '<audio style="display: none;" controls="controls" onloadeddata="var audioPlayer = this; setTimeout(function() { audioPlayer.play(); }, 500)">' +
                    ' <source src="' + url + '" type="audio/mp3" />' +
                    ' </audio>')
            }
        }
    }
    // nextCard and prevCard
    $(document).ready(function() {
        // Switch flash card
        switchCard(1);
        $(document).on('click', '.page-next', nextCard);
        $(document).on('click', '.page-prev', prevCard);
    })
    // auto play audio after 2 sec
    function audioPlay(url) {
        let audio = new Audio(url);
        audio.play();
    }
    // set loop
    var i = 1;
    var interval ;
    // auto loop function
    /*function checkloop(el){
        i = 1;
        if (el.checked === true){
            //disabled pagination
            $('.page').addClass('disable-links')
            //disabled button random play
            $('input[name="rloop"]').attr("disabled", true);
            // pagination
            $('.total_counter').html(1 + '/' + $('.flashcard').length);
            // loop
            if (!interval){
                interval = setInterval(function(){
                    let total = $('.flashcard').length;
                    if (i <=  total){
                        switchCard(i,null,null,'auto')
                        document.getElementById('card'+i).checked = false
                        i++
                    }else {
                        i = 1;
                    }
                }, 7000);
            }
        } else {
            // clear loop
            $('.page').removeClass('disable-links')
            clearInterval(interval);
            interval = null;
            $('input[name="rloop"]').removeAttr("disabled");
        }
    }*/
    // data flash card random
    let flashData  =  {!! json_encode($records)  !!} ;
    // random loop function
   /* function randomloop(el){
        i = 1;
        if (el.checked === true){
            //disabled pagination
            $('.page').addClass('disable-links')
            //disabled button auto play
           $('input[name="loop"]').attr("disabled", true);
           //shuffle flash card
           let randomData = _.shuffle(flashData);
           // romove element flash card
            $('.lecture-player-main').empty();
            // add shuffle flash card
            $.each(randomData, function (index, value) {
                let  active = '';
                if (index === 0){
                    active = 'active';
                }
                $('.lecture-player-main').append(`<div class="flashcard animated slideInRight ${active}" data-card-id="${index+1}">
                                                <input type="checkbox" id="card${index+1}" class="more" aria-hidden="true">
                                                <div class="content ">
                                                    <div aria-hidden="true" class="front" style="background-image: url(https://elearning.hikariacademy.edu.vn/public/assets/images/banners/subscribe.jpg)">
                                                        <label for="card${index+1}" aria-hidden="true">
                                                            <div class="inner">
                                                                <h2>${value.m1tuvung}</h2>
                                                                <div class="rating">
                                                                    <p>${(value.m1vidu != null) ? value.m1vidu : ''}</p>
                                                                </div>
                                                                <div class="tutorial">
                                                                    <p>Click để lật mặt</p>
                                                                </div>
                                                            </div>
                                                            <div id="audio${index+1}" data-audio ="${value.mp3}" style="display: none">
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="back">
                                                        <label for="card${index+1}" aria-hidden="true">
                                                            <div class="inner">
                                                                <div class="description">
                                                                    <p class="text-primary" style="font-size: 24px">${value.m2ynghia}</p>
                                                                    <p style="font-size: 24px">${value.m2cachdoc}</p>
                                                                    <p>${value.m2amhanviet}</p>
                                                                    <p></p>
                                                                </div>
                                                                <div class="tutorial">
                                                                    <p>Click để lật mặt</p>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>`);
            });
            // pagination
            $('.total_counter').html(1 + '/' + $('.flashcard').length);
            // loop
            if (!interval){
                interval = setInterval(function(){
                    let total = $('.flashcard').length;
                    if (i <=  total){
                        switchCard(i,null,null,'auto')
                        document.getElementById('card'+i).checked = false
                        i++
                    }else {
                        i = 1;
                    }
                }, 7000);
            }
        } else {
            // clear loop
            $('.page').removeClass('disable-links')
            clearInterval(interval);
            interval = null;
            $('input[name="loop"]').removeAttr("disabled");
        }
    }*/
    //  auto loop and random loop function
    function loopAll(el){
        let currentCard = 1;
        clearInterval(interval);
        interval = null;
        if (el === 1 || el === 2){
            //disabled pagination
            $('.page').addClass('disable-links')
            if (el === 2){
                //shuffle flash card
                let randomData = _.shuffle(flashData);
                // romove element flash card
                $('.lecture-player-main').empty();
                // add shuffle flash card
                $.each(randomData, function (index, value) {
                    let  active = '';
                    if (index === 0){
                        active = 'active';
                    }
                    $('.lecture-player-main').append(`<div class="flashcard animated slideInRight ${active}" data-card-id="${index+1}">
                                                <input type="checkbox" id="card${index+1}" class="more" aria-hidden="true">
                                                <div class="content ">
                                                    <div aria-hidden="true" class="front" style="background-image: url(https://elearning.hikariacademy.edu.vn/public/assets/images/banners/subscribe.jpg)">
                                                        <label for="card${index+1}" aria-hidden="true">
                                                            <div class="inner">
                                                                <h2>${value.m1tuvung}</h2>
                                                                <div class="rating">
                                                                    <p>${(value.m1vidu != null) ? value.m1vidu : ''}</p>
                                                                </div>
                                                                <div class="tutorial">
                                                                    <p>Click để lật mặt</p>
                                                                </div>
                                                            </div>
                                                            <div id="audio${index+1}" data-audio ="${value.mp3}" style="display: none">
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="back">
                                                        <label for="card${index+1}" aria-hidden="true">
                                                            <div class="inner">
                                                                <div class="description">
                                                                    <p class="text-primary" style="font-size: 24px; text-align: center">${value.m2ynghia || ''}</p>
                                                                    <p style="font-size: 24px">${value.m2cachdoc || ''}</p>
                                                                    <p>${value.m2amhanviet || ''}</p>
                                                                    <p></p>
                                                                </div>
                                                                <div class="tutorial">
                                                                    <p>Click để lật mặt</p>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>`);
                });

                // pagination
                $('.total_counter').html(1 + '/' + $('.flashcard').length);
            } else {
                currentCard = $(".flashcard.active").data("card-id");
                currentCard = parseInt(currentCard)
            }

            if (!interval) {
                interval = setInterval(function() {
                    let total = $('.flashcard').length;
                    if (currentCard <= total) {
                        switchCard(currentCard, null, null, 'auto')
                        document.getElementById('card' + currentCard).checked = false
                        currentCard++;
                    } else {
                        currentCard = 1;
                    }
                }, 7000);
            }
        } else {
            // clear loop
            $('.page').removeClass('disable-links')
           /* $('input[name="rloop"]').removeAttr("disabled");*/
        }
    }
    $(document).ready(function() {
        $(document).on('click', '.switch2', function (){
            let value_loop = parseInt($(this).data('loop'))
            loopAll(value_loop);
        });
    })
    function change_period2(period) {
        let monthly = document.getElementById("monthly2");
        let semester = document.getElementById("semester2");
        let annual = document.getElementById("annual2");
        let selector = document.getElementById("selector");
        if (period === "monthly") {
            selector.style.left = 0;
            selector.style.width = monthly.clientWidth + "px";
            selector.style.backgroundColor = "#1a17ee";
            selector.innerHTML = "Tự động chuyển";
            semester.classList.remove('active');
        } else if (period === "semester") {
            selector.style.left = monthly.clientWidth + "px";
            selector.style.width = semester.clientWidth + "px";
            selector.innerHTML = "Ngừng";
            selector.style.backgroundColor = "#1a17ee";
            semester.classList.remove('active');
        } else {
            selector.style.left = monthly.clientWidth + semester.clientWidth + 1 + "px";
            selector.style.width = annual.clientWidth + "px";
            selector.innerHTML = "Xem ngẫu nhiên";
            selector.style.backgroundColor = "#1a17ee";
            semester.classList.remove('active');
        }
    }
</script>