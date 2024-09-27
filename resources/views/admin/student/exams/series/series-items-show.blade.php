 <?php  $contents = $series->itemsList();   
 ?>
 <ul class="list-group">
    <?php $i_content = 1; 
    ?>
    @foreach($contents as $content)                    
    <?php 
    $url = URL_STUDENT_TAKE_EXAM.$content->slug;
    //$paid = ($item->is_paid && !isItemPurchased($item->id, 'combo')) ? TRUE : FALSE;
    ?>
    <?php $role = getRoleData(Auth::user()->role_id); ?>
    <?php //if($paid) $url = '#'; ?>
    <li class="list-group-item justify-content-between">
        <?php 
            $image_path = IMAGE_PATH_EXAMS.$content->image;
            $image_path_thumb = IMAGE_PATH_EXAMS.$content->type.'.png';
            echo '<img src="'.$image_path_thumb.'" class="img-responsive center-block" alt="" style="width: 40px">';
            

        ?> 
        <a  href="javascript:void(0);" >

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
    <span class="buttons-right pull-right">
        @if($role!='parent')
        <?php if ($finish_current > $i_content) { 
            echo '<span style="color: red; font-size: 18px;">Đã thi</span>';    
        } ?>
        <?php if ($finish_current < $i_content) { 
            echo '<span style="color: green; font-size: 18px;">Chưa thi</span>';    
        } ?>
        <?php if ($finish_current == $i_content) { ?>
        <a href="{{$url}}" class="btn btn-outline-primary btn-pill hikari-thingay hikari-thingay-<?php echo $i_content; ?>" data-content="<?php echo $i_content; ?>"> 
        Thi ngay
        </a>
    <?php } ?>
    @else
    <a 
    @if($role!='parent')
    href="{{$url}}"
    @endif
    > {{$content->dueration}} {{getPhrase('minutes')}}</a>
    @endif
</span> </li>
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
<script src="/Themes/themeone/assets/js/jquery-1.12.1.min.js"></script>
<script type="text/javascript">
    jQuery( document ).ready(function() {
            // $('ul.hikari-bodethi li:first-child a').removeClass('hikari-disable');
            $('.hikari-thingay').on('click',function(){
                // $(this).addClass('hikari-disable');
                $(this).removeClass('btn').removeClass('btn-lg').removeClass('button').removeClass('btn-success').removeAttr('onclick').css('color','blue');
                $(this).text('Đang thi');
                // var data_content = $(this).data('content');
                // data_content = data_content + 1;    
                // // alert(data_content);
                // $('.hikari-thingay-'+ data_content).removeClass('hikari-disable');
            });
        });
    </script>

    <script src="{{admin_asset('js/mousetrap.js')}}"></script>
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
    <SCRIPT TYPE="text/javascript"> 
        var message="Sorry, right-click has been disabled"; 
        function clickIE() {if (document.all) {(message);return false;}} 
        function clickNS(e) {if 
            (document.layers||(document.getElementById&&!document.all)) { 
                if (e.which==2||e.which==3) {(message);return false;}}} 
                if (document.layers) 
                    {document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;} 
                else{document.onmouseup=clickNS;document.oncontextmenu=clickIE;} 
                document.oncontextmenu=new Function("return false") 
    </SCRIPT> 
    <SCRIPT TYPE="text/javascript"> 
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
    </SCRIPT>
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
