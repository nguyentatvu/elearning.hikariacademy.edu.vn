                        <div class="row">
                            <div class="col-md-12">
                                <form>
                                        


                                        <ul class="optional-questions ">
                                            <?php 
                                            $options = json_decode($question->answers); 
                                            $correct_answers = $question->correct_answers;
                                            $index = 1;
                                            foreach ($options as $option) {
                                                $correct_answer_class = '';
                                                if($correct_answers == $index) {
                                                    $correct_answer_class = 'correct-answer';
                                                }
                                                $submitted_value = '';
                                                if($user_answers && count($user_answers))  {
                                                    if($user_answers[0] == $index)
                                                        $submitted_value = 'checked';
                                                }
                                                ?>
                                                <li class="col-md-6 {{$correct_answer_class}} answer_radio">
                                                    <?php echo '<span class="index_number">'. $index . '</span>'; ?> <input type="radio" name="option" id="radio1" disabled {{$submitted_value}}>
                                                    <label for="radio1"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> 
                                                        <span class="language_l1">{{ change_furigana($option->option_value) }}</span>
                                                        @if(isset($option->optionl2_value))
                                                        <span class="language_l2" style="display: none;">{!! $option->optionl2_value !!}</span>
                                                        @else
                                                        <span class="language_l2" style="display: none;">{!! $option->option_value !!}</span>
                                                        @endif
                                                    </label>
                                                </li>



                                                <?php  $index++;
                                            } ?>
                                        </ul>
                                </form>
                            </div>
                        </div>
                        <style type="text/css">
                            li.answer_radio {
                                position: relative;
                                /*padding-bottom: 30px*/
                            }
                            span.index_number {
                                position: absolute;
                                left: 24px;
                                top: 3px;
                            }
                            td.answer_radio label {
                                position: absolute;
                                top: 0px;
                            }
                            span.language_l1 {
                                line-height: 40px;
                            }
                        </style>
                    