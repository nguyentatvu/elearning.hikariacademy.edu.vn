<?php
    $src = "";
	if(!empty($record->question_file)) { ?>
        <?php $src = SITE_URL.$record->question_file; ?>
        <audio controls class="audio-controls">
            <source src="{{$src}}" type="audio/ogg">
            <source src="{{$src}}" type="audio/mpeg">
        </audio>
    <?php } 
        