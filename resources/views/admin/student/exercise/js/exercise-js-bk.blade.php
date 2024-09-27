@if(!isset($records))



<?php $records =null;?>

@else

<?php $records = $exercise ;?>

@endif





@if(!isset($nextUrl))

<?php $nextUrl = 'javascript:;';?>

@else

<?php $nextUrl = null;?>

@endif





<script>





    var parameter = {



        exerciseData : <?php  echo json_encode($records)?>,



        blockAppend : '#data-exercise-hid',

        currentIndex : 0,



        footerAppend : '.btn-group-les',



        typeQuestion: null,



        footerResult :'.result-ntf',



        totalPoint  : 0,





        get : function (){

            return this.exerciseData;

        },

        getAppend : function (){

            return this.blockAppend;

        },

        setIndex : function (index){

            this.currentIndex  = index;

        },

        getIndex : function (){

            return  this.currentIndex ;

        },

        getFooter : function (){

            return  this.footerAppend ;

        },



        getfooterResult : function () {

            return this.footerResult;

        },



        settypeQuestion : function (question){

            this.typeQuestion = question;

        },

        gettypeQuestion : function (){

            return this.typeQuestion;

        },



        settotalPoint :function (total) {

            this.totalPoint  = total;

        },

        gettotalPoint : function () {

            return parseInt(this.totalPoint);

        }



    }

    function appendElements (index = null){



        if (parameter.get().length >0){

            index == null ? parameter.setIndex(0) :parameter.setIndex(index);



            let blockElements =createElements(parseInt(parameter.get()[parameter.getIndex()].dang));



            if ($(parameter.getAppend()).length){

                $(parameter.getAppend()).empty();

            }



            $(parameter.getAppend()).append(blockElements);



            let type_select = [1,2,3];

            if (type_select.indexOf(parseInt(parameter.get()[parameter.getIndex()].dang)) !== -1){



                parameter.settypeQuestion('item_select');

            }else if (parseInt(parameter.get()[parameter.getIndex()].dang) === 5){

                parameter.settypeQuestion('item_modal');

            } else {

                parameter.settypeQuestion('item_option');

            }

            $(parameter.getfooterResult()).empty();

            $(parameter.getFooter()).empty();



            let _totalQuest = {{count($records)}};

            let _widthProgressbar = 100*parseInt(parameter.getIndex())/ parseInt(_totalQuest) ;

            $('.main-bar').css('width',_widthProgressbar+'%')



            $('.cau').html(parseInt(parameter.getIndex())+1)

        }else {

            $(parameter.getAppend()).append('<div class="ct-cpl-screen">\n' +

                '<div class="info-cpl text-center">\n' +

                '<h2 class="above-text">Thông báo!</h2> \n' +

                '<h4 class="below-text">Chưa có bài tập cho bài học này</h4>\n' +

                ' <div class="score-bg-title"><img src="{{admin_asset("images/exercise/score-bg.png")}}" alt=""></div>\n' +



                '</div>\n' +

                '</div>');

        }



    }



    function createElements(type = null) {



        let _currentData = null;

        let _Elements = null ;

        let _headerElements = null;

        let _questionElements = null;

        let _blockQuestion = '';



        switch(type) {

            case 1:

            case 2:

            case 3:

            _currentData = parameter.get()[parameter.getIndex()];

            $.each(_currentData.answers , function (index, value){

                let _index = parseInt(index) +1;



                _blockQuestion += '<div class="item-select" style="text-align: center"><a style="display: inline-block !important; width: unset !important; text-align: left" href="javascript:;" id="'+_index+'">'+_currentData.answers[index]+'</a></div>'

            });



            _headerElements = '<h3 class="guide-user-les desc-web">Hãy lựa chọn đáp án cho câu hỏi dưới đây</h3>';

            _questionElements ='<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10 offset-xl-1">\n' +

            '                                            <div class="kg-study">\n' +

            '                                                <div class="ct-lesson ct-lesson20">\n' +

            '                                                    <div style="text-align: center" class="paragraph-les">\n' +

            '                                                        <div style="    text-align: left;display: inline-block;"><p style="text-align: unset">'+_currentData.mota+'</p></div>\n' +

            '                                                    </div>\n' +

            '                                                    <div class="list-select jp-font ">\n' +

            _blockQuestion+

            '                                                    </div>\n' +

            '                                                </div>\n' +

            '                                            </div>\n' +

            '                                        </div>'



            _Elements = _headerElements + _questionElements;



            break;

            case 4:



            _currentData = parameter.get()[parameter.getIndex()];

            $.each(_currentData.answers , function (index, value){

                _blockQuestion += '<div id="'+index+'" class="item-crt item-option item-select">\n' +

                '   <a href="javascript:void(0)" class="text"><span>'+_currentData.answers[index]+'</span></a>\n' +

                '</div>'

            });



            _headerElements = '<h3 class="guide-user-les desc-web">Sắp xếp từ thành một câu hoàn chỉnh</h3>';



            _questionElements ='<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10 offset-xl-1">\n' +

            '                                    <div class="ct-lesson ct-lesson3 cheking-screen">\n' +

            '                                        <div class="wp-block-type d-flex justify-content-between">\n' +

            '                                            <div class="kg-study kg-study-cuttom">\n' +

            '                                                <div class="block-type">\n' +

            '                                                    <div class="title-block-type"><a href="javascript:void(0)">Câu số '+_currentData.cau+'</a></div>\n' +

            '                                                    <div class=" title-block-type button-rest"><a href="javascript:void(0)"><i class="fa fa-trash" aria-hidden="true"></i>Làm lại</a></div>\n' +

            '                                                    <div class="list-selected list-crt d-flex flex-wrap">\n' +

            '                                                        <div class="wp-line-bot">\n' +

            '                                                            <div class="line-item line1"></div>\n' +

            '                                                            <div class="line-item line2"></div>\n' +

            '                                                        </div>\n' +

            '                                                    </div>\n' +

            '                                                    <div class="title-list-option">'+_currentData.mota+'</div>\n' +

            '                                                    <div class="list-option list-crt d-flex flex-wrap">\n' +

            _blockQuestion+

            '                                                    </div>\n' +

            '                                                </div>\n' +

            '                                            </div>\n' +

            '                                        </div>\n' +

            '                                    </div>\n' +

            '                                </div>'



            _Elements = _headerElements + _questionElements;

            break;



            case 5:



            _currentData = parameter.get()[parameter.getIndex()];





            if (_currentData.mota != null){



                let count = (_currentData.mota.match(/（　）/g) || []).length;

                console.log(count);

                _blockQuestion +=  '<div class="block-type block-type-cuttom">\n' +

                '<div class="title-block-type">\n' +

                '                       <!-- <a href="javascript:;">Câu số</a>-->\n' +

                '                    </div>\n' +

                '                    <h3 class="ques-block-empty jp-font" >'+

                _currentData.mota.replace(/（　）/g,'<span class="text-focus-ques"  onclick="openModal(this)">' +

                    '<span data-value="" data-check="0" id="" >(Chọn từ)</span></span>')+

                '</h3>\n'+

                '</div>\n';



                _headerElements = '<h3 class="guide-user-les desc-web">Chọn từ thích hợp vào vị trí còn trống trong câu</h3>';



                _questionElements ='     <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10 offset-xl-1 ">\n' +

                '        <div class="ct-lesson ct-lesson4">\n' +

                '            <div class="wp-block-type d-flex">\n' +



                _blockQuestion +



                '            </div>\n' +

                '        </div>\n' +

                '    </div>\n'+

                ' <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\n' +

                '        <div class="modal-dialog modal-wrapper modal-lg" role="document">\n' +

                '            <div class="modal-container">\n' +

                '                <div class="modal-body">\n' +

                '                    <div  class="popup-block-type">\n' +

                '                        <div  class="block-type">\n' +

                '                            <div  class="title-block-type">\n' +

                '                                <a  href="javascript:;">Câu số</a>\n' +

                '                            </div>\n' +

                '                            <div class="list-select-popup-check" id="modal-quest">\n' +

                '                            </div>\n' +

                '                            <span  class="close-popup-les" data-dismiss="modal"><i  aria-hidden="true"  class="fa fa-times"></i></span>\n' +

                '                        </div>\n' +

                '                    </div>\n' +

                '                </div>\n' +

                '\n' +

                '            </div>\n' +

                '        </div>\n' +

                '    </div>';

            }







            _Elements = _headerElements + _questionElements;







            break;

            default:



            _Elements = '<div class="ct-cpl-screen">\n' +

            '<div class="info-cpl text-center">\n' +

            '<h2 class="above-text">Hoàn thành!</h2> \n' +

            '<h4 class="below-text">Chúc mừng bạn đã hoàn thành xong bài học</h4>\n' +

            ' <div class="score-bg-title"><img src="{{admin_asset("images/exercise/score-bg.png")}}" alt=""></div>\n' +



            '</div>\n' +

            '</div>'

            break;

            // code block

        }





        return _Elements;

    }



    var appendFooter = (type = null) =>{



        let _actionElement = null;

        switch(type) {

            case 1:

            _actionElement ='<a  href="javascript:;" class="btn-nav-les btn-check-les">Kiểm tra &nbsp; <i class="fa fa-eye-slash" aria-hidden="true"></i></a>';

            break;

            case 2:

            _actionElement ='<a href="javascript:;" class="btn-nav-les btn-result-corect-les">Hoàn thành &nbsp; <i class="fa fa-check" aria-hidden="true"></i></a>'

            break;

            case  3:

            _actionElement ='<a href="javascript:;" class="btn-nav-les finish-les">Câu tiếp theo &nbsp; <i  aria-hidden="true" class="fa fa-angle-double-right"></i></a>'

            break;

            default:

            _actionElement = '<a href="{{$nextUrl}}" class="btn-nav-les btn-end-exercise btn-danger">Bài học kế tiếp &nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></a>'

            break;

        }



        return _actionElement;

    }













    async function allFunction(){



        //console.log(parameter.get().length)





        await appendElements(parameter.getIndex());



        await  $(document).on('click','.item-select',function(){
            let  type = parameter.gettypeQuestion();

            let _answerData = null;

            switch(type.toUpperCase()) {

                case "ITEM_SELECT":



                $('.'+type.replace('_','-')).each(function() {

                    $(this).removeClass('active');

                });

                $(this).addClass('active');





                if (!$(parameter.getFooter()).children(0).hasClass("btn-check-les")){

                    $(parameter.getFooter()).empty();

                    let _blockFooter = appendFooter(1);

                    $(parameter.getFooter()).append(_blockFooter);

                }

                break;

                case 'ITEM_OPTION':



                _answerData = parameter.get()[parameter.getIndex()].answers ;

                let _elementSelected = $('.list-selected');

                let _optionID =  $(this).attr('id');





                $(this).wrap(function() {

                    return '<!--'+_optionID+'-->';

                });



                _elementSelected.append('<div id="'+_optionID+'"  class="item-crt item-option item-option-selected">\n' +

                    '   <a href="javascript:void(0)" class="text jp-font"><span>'+_answerData[_optionID]+'</span></a>\n' +

                    '       <span class="delete icon-ntf"><i aria-hidden="true" class="fa fa-times-circle"></i></span>\n' +

                    '  </div>')





                if (!$('.list-option').children().hasClass('item-select')){



                   // $('.title-list-option').css('display','none');

                    if (!$(parameter.getFooter()).children(0).hasClass("btn-check-les")){

                        $(parameter.getFooter()).empty();

                        let _blockFooter = appendFooter(1);

                        $(parameter.getFooter()).append(_blockFooter);

                    }

                }





                break;





                case 'ITEM_MODAL':

                let _radioID = null;

                let _element_ID = 'ID_5';



                _radioID = parseInt($(this).attr('data-value'));



                _answerData  = parameter.get()[parameter.getIndex()].answers ;



                let _focusQuest =  $('.text-focus-ques');



                        console.log( _focusQuest.find('span#'+_element_ID).length)

                        _focusQuest.find('span#'+_element_ID).html(_answerData[parseInt(_radioID) -1]);

                        _focusQuest.find('span#'+_element_ID).attr('data-check',1);

                        _focusQuest.find('span#'+_element_ID).attr('data-value',(_radioID));



                        _focusQuest.find('span').attr('id','');

                        $('#exampleModal').modal('hide');

                        let count= 0;



                        $(_focusQuest.find('span')).each(function (index, value){



                            if (parseInt($(this).attr('data-check')) !== 0 ){

                                count ++;

                            }

                        });



                        if (count > 2){

                            if (!$(parameter.getFooter()).children(0).hasClass("btn-check-les")){

                                $(parameter.getFooter()).empty();

                                let _blockFooter = appendFooter(1);

                                $(parameter.getFooter()).append(_blockFooter);

                            }

                        }







                        break

                        default:



                        break;

                    }



                });





        await $(document).on('click','.btn-check-les',function(){

            let _currentAnswer = parameter.get()[parameter.getIndex()];



            let  type = parameter.gettypeQuestion();

            let _elementList =  null;

            let _studentAnswer = '';

            let _resultAnswer = null;

            let _blockFooter = '';

            switch(type.toUpperCase()) {

                case "ITEM_SELECT":



                _elementList =  $('.list-select');



                _studentAnswer = _elementList.find('div.active').children().attr('id');



                _resultAnswer = parseInt(_currentAnswer.dapan) === parseInt(_studentAnswer);



                _elementList.addClass('none-clicks');

                if (_resultAnswer){

                    _elementList.find('div.active').addClass('correct-answer')



                    $(parameter.getfooterResult()).empty();

                    $(parameter.getfooterResult()).append('<div  class="corect result-type d-flex align-items-center animated tada">\n' +

                        '                                            <i aria-hidden="true" class="fa fa-check-circle"></i>\n' +

                        '                                            <span >Chính xác</span>\n' +

                        '                                        </div>')





                    parameter.settotalPoint(parameter.gettotalPoint()+1);



                    $('.total-les').text(parameter.gettotalPoint());
                    console.log(parameter.gettotalPoint());

                }else {

                    _elementList.find('div.active').addClass('incorrect-answer')

                    _elementList.find('a#'+parseInt(_currentAnswer.dapan)).parent().addClass('correct-answer')

                    $(parameter.getfooterResult()).empty();

                    $(parameter.getfooterResult()).append('<div  class="incorect result-type d-flex align-items-center animated tada">\n' +

                        '                                            <i aria-hidden="true" class="fa fa-times-circle"></i>\n' +

                        '                                            <span >Không chính xác</span>\n' +

                        '                                        </div>');









                }



                $(parameter.getFooter()).empty();

                _blockFooter =  appendFooter(3);

                if (parameter.getIndex() === parameter.get().length -1 ){

                    _blockFooter = appendFooter(2);

                }



                $(parameter.getFooter()).append(_blockFooter);



                break;

                case 'ITEM_OPTION':



                _elementList = $('.item-option-selected');

                _studentAnswer = '';

                $.each(_elementList, function (index, value){

                    _studentAnswer += parseInt($(this).attr('id')) +1 + ((index<3) ?';':'');

                });



                _studentAnswer = Array.from(_studentAnswer.split(';'), Number);



                let _currentResult = null;



                _currentResult =  Array.from(_currentAnswer.dapan.split(';'), Number)





                _resultAnswer = (_studentAnswer.length === _currentResult.length) && _studentAnswer.every(function(element, index) {

                    return element === _currentResult[index];

                });



                _elementList.addClass('none-clicks');



                $('.button-rest').hide();

                if (_resultAnswer){

                    _elementList.parent().parent().addClass('correct-status')



                    $(parameter.getfooterResult()).empty();

                    $(parameter.getfooterResult()).append('<div  class="corect result-type d-flex align-items-center animated tada">\n' +

                        '                                            <i aria-hidden="true" class="fa fa-check-circle"></i>\n' +

                        '                                            <span >Chính xác</span>\n' +

                        '                                        </div>')





                    parameter.settotalPoint(parameter.gettotalPoint()+1);



                    $('.total-les').text(parameter.gettotalPoint());



                }else {



                    _elementList.parent().parent().addClass('incorrect-status')



                    $(parameter.getfooterResult()).empty();

                    $(parameter.getfooterResult()).append('<div  class="incorect result-type d-flex align-items-center animated tada">\n' +

                        '                                            <i aria-hidden="true" class="fa fa-times-circle"></i>\n' +

                        '                                            <span >Không chính xác</span>\n' +

                        '                                        </div>');







                    $.each(_currentResult , function (index, value){

                        $('.list-option').append('<div  class="item-crt item-option none-clicks">\n' +

                            '   <a href="javascript:void(0)" class="text"><span>'+(_currentAnswer.answers[parseInt(value) -1]) +'</span></a>\n' +

                            '  </div>' )

                    });







                }

                $(parameter.getFooter()).empty();

                _blockFooter =  appendFooter(3);

                if (parameter.getIndex() === parameter.get().length -1 ){

                    _blockFooter = appendFooter(2);

                }

                $(parameter.getFooter()).append(_blockFooter);



                break;



                case 'ITEM_MODAL':

                _elementList =   $('.text-focus-ques');





                        //console.log(_currentAnswer.quest[0].dapan)



                        $(_elementList.find('span')).each(function (index, value){



                            _studentAnswer += $(this).attr('data-value') + ((index < 2) ?';':'');



                        });



                        _studentAnswer = Array.from(_studentAnswer.split(';'), Number);



                        let _currentModal = null;



                        _currentModal =  Array.from(_currentAnswer.dapan.split(';'), Number)



                        _resultAnswer = (_studentAnswer.length === _currentModal.length) && _studentAnswer.every(function(element, index) {

                            return element === _currentModal[index];

                        });



                        _elementList.addClass('none-clicks');



                        console.log(_studentAnswer)



                        console.log(_currentModal)



                        if (_resultAnswer){

                            _elementList.parent().parent().addClass('correct-status')

                            _elementList.addClass('text-success');

                            _elementList.css('border-color','#5cb85c');



                            parameter.settotalPoint(parameter.gettotalPoint()+1);

                            $('.total-les').text(parameter.gettotalPoint());



                            $(parameter.getfooterResult()).empty();

                            $(parameter.getfooterResult()).append('<div  class="corect result-type d-flex align-items-center animated tada">\n' +

                                '                                            <i aria-hidden="true" class="fa fa-check-circle"></i>\n' +

                                '                                            <span >Chính xác</span>\n' +

                                '                                        </div>')



                        }else {
                            _elementList.parent().parent().addClass('incorrect-status')

                            /*$(this).parent().parent().append(' <h3 style="pointer-events: none;" class="mt-3 ques-block-empty jp-font">Đáp án: ' +

                                '<span class="text-focus-ques text-success" style="border-color: #5cb85c!important;">'+

                                _currentAnswer.quest[index].answers[parseInt(_currentAnswer.quest[index].dapan) -1]+

                                '</span> </h3>')*/



                                $(_elementList).each(function (index, value){

                                    $(this).css('border-bottom','dashed 2px #5cb85c')

                                    $(this).empty();

                                    $(this).html('<span class="text-success">'+_currentAnswer.answers[parseInt(_currentModal[index]) -1]+ '</span>')

                                });



                                $(parameter.getfooterResult()).empty();

                                $(parameter.getfooterResult()).append('<div  class="incorect result-type d-flex align-items-center animated tada">\n' +

                                    '                                            <i aria-hidden="true" class="fa fa-times-circle"></i>\n' +

                                    '                                            <span >Không chính xác</span>\n' +

                                    '                                        </div>');



                            }







                            $(parameter.getFooter()).empty();

                            _blockFooter =  appendFooter(3);

                            if (parameter.getIndex() === parameter.get().length -1 ){

                                _blockFooter = appendFooter(2);

                            }



                            $(parameter.getFooter()).append(_blockFooter);





                            break

                            default:



                            break;

                        }

                    });





await  $(document).on('click','.finish-les',function(){

    parameter.setIndex(parseInt(parameter.getIndex()) +1)

    appendElements(parameter.getIndex());





})



await $(document).on('click','.item-option-selected',function(){

    let  _answerClose = parameter.get()[parameter.getIndex()].answers ;

    let  _optionElement  = $('.list-option');



    let _closeID =  $(this).attr('id');



    let _swipElement = '<div id="'+_closeID+'" class="item-crt item-option item-select">\n' +

    '   <a href="javascript:void(0)" class="text"><span>'+_answerClose[_closeID]+'</span></a>\n' +

    '</div>';



    let _outElement = _optionElement.html().replace('<!--'+_closeID+'-->',_swipElement);

    _optionElement.html(_outElement);

    $(this).remove();



});



await  $(document).on('click','.btn-result-corect-les', async function(){

    if ($(parameter.getAppend()).length){

        $(parameter.getAppend()).empty();

    }

    $(parameter.getAppend()).append(createElements());

    $(parameter.getfooterResult()).empty();

    $(parameter.getFooter()).empty();





    $(parameter.getFooter()).append(appendFooter());



    $('.main-bar').css('width','100%');


    @if(Auth::check())
        await finishTimeVideo('{{$slug}}');
    @endif
    await netxUrl('{{$slug}}','{{$series}}','{{$combo_slug}}');





})



            /*await  $(document).on('click','.btn-end-exercise',function(){



                window.close();

            })*/



            $(document).on('click','.button-rest',function(){

               let _currentRest = null;

               let _blockReset = '';

               _currentRest = parameter.get()[parameter.getIndex()];

               $.each(_currentRest.answers , function (index, value){

                  _blockReset += '<div id="'+index+'" class="item-crt item-option item-select">\n' +

                  '   <a href="javascript:void(0)" class="text"><span>'+_currentRest.answers[index]+'</span></a>\n' +

                  '</div>'

              });





               $('.list-option').empty().append(_blockReset);

               $('.title-list-option').css('display','block');

               $.each($('.list-selected').find('div.item-option-selected') , function (index, value){

                  $(this).remove();

              });

               $(parameter.getFooter()).empty();

           })



        }



        function openModal(element){





            let _elmentFocusQues =  null;

            _elmentFocusQues = $(element);



            let _currentQuest = null;

            _currentQuest = parameter.get()[parameter.getIndex()].answers;



            let  _modalQuestion = '';



            let  _checkAnswer = _elmentFocusQues.find('span',0).attr('data-value') !== '' ? _elmentFocusQues.find('span').attr('data-value'): null ;



            $.each(_currentQuest , function (index, value){

                let _index = parseInt(index) +1;

                _modalQuestion +=' <div  class="item-check-select">\n' +

                '                                    <div  class="form-check">\n' +

                '                                        <input '+(_index === parseInt(_checkAnswer) ? 'checked' : '')+'  type="radio" name="quest5_'+0+'" id="'+value+'" class="form-check-input" value="">\n' +

                '                                        <label data-action="" data-value="'+_index+'"  for="'+value+'" class="item-select form-kana text-type">\n' +

                '                                                                            <span  class=" icon-input icon-no-checked">\n' +

                '                                                                                <i aria-hidden="true" class="fa fa-square-o"></i>\n' +

                '                                                                            </span>\n' +

                '                                            <span  class="icon-input icon-checked" >\n' +

                '                                                                                <i  aria-hidden="true" class="fa fa-check-square-o"></i>\n' +

                '                                                                            </span>\n' +

                '                                            <span  class="text-label jp-font">'+value+'</span>\n' +

                '                                        </label>\n' +

                '                                    </div>\n' +

                '                                </div>'





            });

            let _modalAppend = $('#modal-quest');



            _modalAppend.empty();

            _modalAppend.append(_modalQuestion);



            _elmentFocusQues.find('span',0).attr('id','ID_5');



            $('#exampleModal').modal('show');



        }


    @if(Auth::check())
        function finishTimeVideo(slug){

            $.ajax({

                headers: {

                    'X-CSRF-TOKEN':'{{csrf_token()}}'

                },

                url : '{{route('finishTimeVideo')}}',

                type : "post",

                data: {slug : slug},

            });

        }
    @endif


        function netxUrl(slug,series,combo){

            $.ajax({

                headers: {

                    'X-CSRF-TOKEN':'{{csrf_token()}}'

                },

                url : '{{route('nextUrl')}}',

                type : "post",

                data: {slug : slug,series: series,combo: combo},





            }).done(function(data) {

                if (data.status === 1){

                    $('.btn-end-exercise').attr("href", data.url);

                }

            });

        }



        allFunction();

    </script>