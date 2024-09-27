                       <?php    
                       // $image_path = PREFIX.(new App\ImageSettings())->getExamImagePath(); 
                       // echo "<pre>";
                       // print_r ($cau_so . '-------------' .$exam_record->type);
                       // echo "</pre>";
                       ?>
                       <div class="questions questions-withno">
                        <table style="width:98%">
                              <tbody>
                                @if ($cau_so == 1)
                                <tr class="hik-table-tr-question">
                                    <td class="hik-table-tr-question-number" width="2%">
                                      <i class="fa fa-star"></i>
                                    </td>
                                    <td class="hik-table-tr-question-question" style="color: #2196f3; font-size: 16px; font-weight: 600;">
                                       {{ change_furigana($meta['subject']->subject_code) }}              
                                  </td> 
                                </tr>

                                <?php if (!empty($question->topics_parent_description)) { ?>
                                <tr class="hik-table-tr-question">
                                    <td class="hik-table-tr-question-number" width="2%">
                                      
                                    </td>
                                    <td class="hik-table-tr-question-question" style="padding-left: 8px;">
                                       {{ change_furigana($question->topics_parent_description) }}              
                                  </td> 
                                </tr>
                                <?php } ?>
                                <?php if (!empty($question->topics_child_description)) { ?>
                                <tr class="hik-table-tr-question">
                                    <td class="hik-table-tr-question-number" width="2%">

                                    </td>
                                    <td class="hik-table-tr-question-question" style="padding-left: 8px;">
                                       {{ change_furigana($question->topics_child_description) }}              
                                  </td> 
                                </tr>
                                <?php } ?>
                                @endif


                                <?php if (!empty($question->explanation)) { ?>
                          
                                <?php  
                                  $stt_explanation = '';
                                  if ($category == 3) {
                                    switch ($question_number) {
                                        case 24:
                                            $stt_explanation = '(1)';
                                            break;
                                        case 25:
                                            $stt_explanation = '(2)';
                                            break;
                                        case 26:
                                            $stt_explanation = '(3)';
                                            break;
                                        case 27:
                                            $stt_explanation = '(4)';
                                            break;
                                    } 
                                    //echo '<tr class="hik-table-tr-question">' .$stt_explanation . '</tr>';
                                  }
                                  
                                  if ($category == 4) {
                                    switch ($question_number) {
                                        case 26:
                                            $stt_explanation = '<p>(1)</p>';
                                            break;
                                        case 27:
                                            $stt_explanation = '<p>(2)</p>';
                                            break;
                                        case 28:
                                            $stt_explanation = '<p>(3)</p>';
                                            break;
                                        case 29:
                                            $stt_explanation = '<p>(4)</p>';
                                            break;
                                    } 
                                   // echo '<tr class="hik-table-tr-question">' .$stt_explanation . '</tr>';
                                  }

                                  if ($category == 5) {
                                    switch ($question_number) {
                                        case 27:
                                            $stt_explanation = '<p>(1)</p>';
                                            break;
                                        case 28:
                                            $stt_explanation = '<p>(2)</p>';
                                            break;
                                        case 29:
                                            $stt_explanation = '<p>(3)</p>';
                                            break;
                                       
                                    } 
                                    //echo '<tr class="hik-table-tr-question">' .$stt_explanation . '</tr>';
                                  }
                                ?>
                                
                                <tr class="hik-table-tr-question">
                                    <td class="hik-table-tr-question-number" width="2%">
                                    </td>
                                    <td class="hik-table-tr-question-question" style="padding-left: 8px;">
                                        <?php echo $stt_explanation; ?>          
                                  </td> 
                                </tr>
                                <tr class="hik-table-tr-question">
                                    <td class="hik-table-tr-question-number" width="2%">
                                      
                                    </td>
                                    <td class="hik-table-tr-question-question" style="padding-left: 8px;">
                                       {{ change_furigana($question->explanation) }}              
                                  </td> 
                                </tr>
                                <?php } ?>
                                <tr class="hik-table-tr-question">
                                    <td class="hik-table-tr-question-number" width="2%">
                                      <?php if($exam_record->type == 1) {?>
                                            <span class="question_number" style="border: 1px solid; padding: 0px 8px;">{{$so_cau}}</span>
                                      <?php } else {?>
                                      <span class="question_number" style="border: 1px solid; padding: 0px 8px;">{{$question_number}}</span>
                                      <?php } ?>
                                    </td>
                                    <td class="hik-table-tr-question-question" style="padding-left: 8px;">
                                        <?php if (!empty($question->question_file)) { ?>
                                          <?php $src = SITE_URL.$question->question_file; ?>
                                            <div style="width: 400px; padding: 20px 0px;">
                                                    <audio controls="" class="audio-controls">
                                                          <source src="<?php echo $src; ?>" type="audio/ogg">
                                                          <source src="<?php echo $src; ?>" type="audio/mpeg">
                                                    </audio>
                                            </div>
                                        <?php }?>
                                       {{ change_furigana($question->question) }}              
                                  </td> 
                                </tr>
                              </tbody>

                        </table>
                      </div>
                      <hr>
                      <style type="text/css">
                        .questions.questions-withno .question-numbers {
                            position: absolute;
                            left: 0;
                            top: 10px;
                            font-weight: 700;
                            padding: 0px 10px;
                            border: 1px solid;
                            font-size: 16px;
                            border-radius: 8px;
                        }
                        .questions {
                            font-size: 18px;
                        }
                         table td img {
                            width: none !important;
                        } 
                      </style>