 <?php  $contents = $series->itemsList();   

 ?>
 <ul class="lesson-list list-unstyled clearfix hikari-bodethi">
        <?php $i_content = 1; ?>
        @foreach($contents as $content)                    
        <?php 
            $url = URL_STUDENT_TAKE_EXAM.$content->slug;
            $paid = ($item->is_paid && !isItemPurchased($item->id, 'combo')) ? TRUE : FALSE;
        ?>
             <?php $role = getRoleData(Auth::user()->role_id); ?>
         <?php if($paid) $url = '#'; ?>
        <li class="list-item">
            <?php 
                if($content->image)
                {
                    $image_path = IMAGE_PATH_EXAMS.$content->image;
                    $image_path_thumb = IMAGE_PATH_EXAMS.$content->image;
                    echo '<img src="'.$image_path_thumb.'" class="img-responsive center-block" alt="" style="width: 30px">';
                    // echo '<img src="http://elearning.hikariacademy.edu.vn/public/uploads/exams/categories/30-examimage.png" alt="img">';

                } 
            ?> 
            <a  href="javascript:void(0);" 
                @if($paid)
                onclick="showMessage('Vui lòng mua khóa luyện thi để tiếp tục');" 
                @else
                   @if($role=='student')
                    onclick="" 
                   @endif
                @endif
            >
             
            {{ change_furigana_text($content->title)}}
            
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

                <a  
                href="javascript:void(0);" class="btn button btn-lg btn-success hikari-thingay hikari-thingay-<?php echo $i_content; ?>" data-content="<?php echo $i_content; ?>"
                 @if($paid)
                    onclick="showMessage('Vui lòng mua khóa luyện thi để tiếp tục');" 
                @else
                    onclick="showInstructions('{{$url}}');" 
                @endif
                > 
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
            font-size: 16px;
            line-height: 24px;
            max-width: 100%;
            padding: 5px 0;
            color: #717a86;
            transition: all ease .3s;
            font-weight: 600;
        }
    </style>
    <script src="http://elearning.hikariacademy.edu.vn/Themes/themeone/assets/js/jquery-1.12.1.min.js"></script>

    <script type="text/javascript">
        jQuery( document ).ready(function() {
            
            // $('ul.hikari-bodethi li:first-child a').removeClass('hikari-disable');

            $('.hikari-thingay').on('click',function(){
                $(this).addClass('hikari-disable');
                // var data_content = $(this).data('content');
                // data_content = data_content + 1;    
                // // alert(data_content);
                // $('.hikari-thingay-'+ data_content).removeClass('hikari-disable');
            });
        });
    </script>

