 <?php  
    $contents = $series->itemsList();
    
 ?>



 <ul class="lesson-list list-unstyled clearfix hikari-bodethi">
    <?php $i_content = 1; 
    ?>
    @foreach($contents as $content)                    
    <?php 
    /*echo "<pre>";
    print_r ($content);
    echo "</pre>";*/
    $url = URL_START_EXAM.$content->slug;
    // $url = 'exams/student/start-exam/'.$content->slug;
    ?>
    <?php $role = getRoleData(Auth::user()->role_id); ?>
    <li class="list-item">
        <?php 
            $image_path = IMAGE_PATH_EXAMS.$content->image;
            $image_path_thumb = IMAGE_PATH_EXAMS.$content->type.'.png';
            echo '<img src="'.$image_path_thumb.'" class="img-responsive center-block" alt="" style="width: 40px">';
            
            

        ?> 
        <a  href="javascript:void(0);" >

            <?php  
                switch ($content->type) {
                    case '2':
                        $title = 'TỪ VỰNG';
                        break;
                    case '3':
                        $title = 'NGỮ PHÁP - ĐỌC HIỂU';
                        break;
                    case '1':
                        $title = 'NGHE HIỂU';
                        break;
                }
            ?>
            {{ $title }} 
            <?php if ($content->type != 1) { 
                echo '(' . $content->dueration . " Phút)"; } 
            else {
                if ($item->category_id == 3) {
                    echo "(40 Phút)"; 
                } 
                if ($item->category_id == 4) {
                    echo "(35 Phút)"; 
                }
                if ($item->category_id == 5) {
                    echo "(30 Phút)"; 
                }
                
            }?>


        </a>  

        <?php 
            $count_question = DB::table('questionbank_quizzes')->where('quize_id','=',$content->id)->count();
            if ($count_question == $content->total_questions) {
                echo '<span class="label label-success">Đủ '.$content->total_questions.' câu</span>';
            } else {
                echo '<span class="label label-warning">Chưa đủ: '.$count_question.'/'. $content->total_questions . ' câu</span>';
            }
            if ($content->type == 1) {
                $question_audio = DB::table('questionbank_quizzes')
                                    ->join('questionbank','questionbank.id','=','questionbank_quizzes.questionbank_id')
                                    ->select(['questionbank.id','questionbank.question_file'])
                                    ->where('quize_id','=',$content->id)
                                    ->get();
                $check_file = 1;
                foreach ($question_audio as $key => $value) {

                    $filename = $_SERVER['DOCUMENT_ROOT'] . $value->question_file;

                    if (!file_exists($filename)){
                        $check_file = 0;
                    }
                }

                if ($check_file == 1) {
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-success">Đủ file mp3</span>';
                } else {
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-warning">Chưa đủ</span>';
                }
                // echo "<pre>";
                // print_r ($question_audio);
                // echo "</pre>";
            }

         ?>
    <span class="buttons-right pull-right">
      <a href="{{$url}}" target="_blank">Kiểm tra</a>
    </span> 
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
    font-size: 16px;
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
