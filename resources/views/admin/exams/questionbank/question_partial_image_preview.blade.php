<?php
    if(!empty($record->question_photo)) { 
?>
        <img src="{{EXAM_UPLOADS.$record->question_photo}}" height="90" width="90"/>
<?php } ?>