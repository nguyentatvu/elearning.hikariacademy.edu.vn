<?php  $contents = $series->itemsList();
 ?>
<ul class="list-group">
    <?php $i_content = 1; ?>
    @foreach($contents as $content)
    <?php $url = route('mock-exam.instruction', $content->slug) ?>
    <?php $role = getRoleData(Auth::user()->role_id); ?>
    <li class="list-group-item justify-content-between">
        <?php
            $image_path = IMAGE_PATH_EXAMS . $content->image;
            $image_path_thumb = IMAGE_PATH_EXAMS . $content->type.'.png';
            echo '<img src="' . $image_path_thumb . '" class="img-responsive center-block" alt="" style="width: 40px">';
        ?>
        <a href="javascript:void(0);" style="vertical-align: sub" class="ms-2">
            <?php
                switch ($item->category_id) {
                    case '1':
                        switch ($content->type) {
                            case '2':
                                $title = 'TỪ VỰNG - NGỮ PHÁP - ĐỌC HIỂU' . ' (' . $content->dueration . " Phút)";
                                break;
                            case '1':
                                $title = 'NGHE HIỂU (60 phút)';
                                break;
                        }
                        break;
                    case '2':
                        switch ($content->type) {
                            case '2':
                                $title = 'TỪ VỰNG - NGỮ PHÁP - ĐỌC HIỂU' . ' (' . $content->dueration . " Phút)" ;
                                break;
                            case '1':
                                $title = 'NGHE HIỂU (50 phút)';
                                break;
                        }
                        break;
                    case '3':
                       switch ($content->type) {
                           case '2':
                               $title = 'TỪ VỰNG' . '(' . $content->dueration . " Phút)" ;
                               break;
                           case '3':
                               $title = 'NGỮ PHÁP - ĐỌC HIỂU'. '(' . $content->dueration . " Phút)" ;
                               break;
                           case '1':
                               $title = 'NGHE HIỂU (40 phút)';
                               break;
                        }
                        break;
                    case '4':
                       switch ($content->type) {
                           case '2':
                               $title = 'TỪ VỰNG' . '(' . $content->dueration . " Phút)" ;
                               break;
                           case '3':
                               $title = 'NGỮ PHÁP - ĐỌC HIỂU'. '(' . $content->dueration . " Phút)" ;
                               break;
                           case '1':
                               $title = 'NGHE HIỂU (35 phút)';
                               break;
                        }
                        break;
                    case '5':
                       switch ($content->type) {
                           case '2':
                               $title = 'TỪ VỰNG' . '(' . $content->dueration . " Phút)" ;
                               break;
                           case '3':
                               $title = 'NGỮ PHÁP - ĐỌC HIỂU'. '(' . $content->dueration . " Phút)" ;
                               break;
                           case '1':
                               $title = 'NGHE HIỂU (30 phút)';
                               break;
                        }
                        break;
                }

                echo $title;
            ?>
        </a>
        @php
            $examStatus = [
                'not_started' => $finish_current < $i_content,
                'completed' => $finish_current > $i_content,
                'ready' => $finish_current == $i_content
            ];

            $statusLabels = [
                'completed' => [
                    'text' => 'Đã thi',
                    'class' => 'text-danger'
                ],
                'not_started' => [
                    'text' => 'Chưa thi',
                    'class' => 'text-success'
                ]
            ];
        @endphp
        <div class="buttons-right pull-right d-flex align-items-center" style="min-height: 40px;">
            @if($role !== 'parent')
                @if($examStatus['completed'] || $examStatus['not_started'])
                    <span class="fs-6 {{ $examStatus['completed'] ? $statusLabels['completed']['class'] : $statusLabels['not_started']['class'] }}">
                        {{ $examStatus['completed'] ? $statusLabels['completed']['text'] : $statusLabels['not_started']['text'] }}
                    </span>
                @endif

                @if($examStatus['ready'])
                    <a href="{{ $url }}"
                        class="btn btn-outline-primary fs-6 btn-pill hikari-thingay hikari-thingay-{{ $i_content }}"
                        data-content="{{ $i_content }}">
                        Thi ngay
                    </a>
                @endif
            @else
                <a href="{{ $role !== 'parent' ? $url : '#' }}">
                    {{ $content->dueration }} {{ getPhrase('minutes') }}
                </a>
            @endif
        </div>
    </li>
    <?php $i_content++; ?>
    @endforeach
</ul>
<style type="text/css">
    .lesson-list .list-item a.hikari-disable {
        display: none;
    }

    .lesson-list .list-item a.hikari-thingay {
        color: #fff;
    }

    .lesson-list .list-item a {
        display: block;
        -ms-flex: 1;
        flex: 1;
        font-size: 14px;
        line-height: 24px;
        max-width: 100%;
        padding: 5px 0;
        color: #717a86;
        transition: all ease .3s;
        font-weight: 600;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $('.hikari-thingay').on('click',function(){
            $(this)
                .removeClass('btn').removeClass('btn-lg')
                .removeClass('button').removeClass('btn-success')
                .removeAttr('onclick').css('color','blue');
            $(this).text('Đang thi');
        });
    });
</script>

<script src="{{asset('js/plugins/mousetrap.js')}}"></script>
<script>
    window.history.forward();
        function noBack() { window.history.forward(); }
        function checkKeyCode(evt)
        {
            var evt = (evt) ? evt : ((evt) ? evt : null);
            console.log(evt.keyCode);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if(
                evt.keyCode == 123 //F12
                || evt.keyCode==116
                || evt.keyCode==82 || evt.keyCode==9 || evt.keyCode==18 || evt.keyCode==17
                || evt.keyCode == 44 //PRNT SCR
                )
            {
                evt.keyCode=0;
                return false
            }
            else if(evt.keyCode==8)
            {
                evt.keyCode=0;
                return false
            }
        }
        document.onkeydown=checkKeyCode;
</script>
<script TYPE="text/javascript">
    var message="Sorry, right-click has been disabled";
        function clickIE() {if (document.all) {(message);return false;}}
        function clickNS(e) {if
            (document.layers||(document.getElementById&&!document.all)) {
                if (e.which==2||e.which==3) {(message);return false;}}}
                if (document.layers)
                    {document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;}
                else{document.onmouseup=clickNS;document.oncontextmenu=clickIE;}
                document.oncontextmenu=new Function("return false")
</script>
<script TYPE="text/javascript">
    function disableselect(e){
            return false
        }
        function reEnable(){
            return true
        }
        //if IE4+
        document.onselectstart=new Function ("return false")
        //if NS6
        if (window.sidebar){
            document.onmousedown=disableselect
            document.onclick=reEnable
        }
</script>
<script>
    Mousetrap.bind(['ctrl+s', 'ctrl+p', 'ctrl+w', 'ctrl+u'], function(e) {
        if (e.preventDefault) {
            e.preventDefault();
        } else {
            // internet explorer
            e.returnValue = false;
        }
    });
</script>