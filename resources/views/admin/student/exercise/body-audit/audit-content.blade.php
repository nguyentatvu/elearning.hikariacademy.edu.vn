<?php
 $array_char = ['a','b','c','d'];
?>
@if(isset($record))



    <div class="paragraph-les paragraph-les-cuttom jp-font vue-sticky-el">
        {!! nl2br ($record[0]->mota) !!}
    </div>




    @foreach ($record as $value)
        <div  class="wp-block-type d-flex justify-content-between">
            <div class="block-type block-type-cuttom {{(isset($value->correct) ? ($value->correct ==1 ? "correct-status ": "incorrect-status") ." none-clicks" : "")}}  ">
                <div class="title-block-type " style="color: #fff;">
                    <a>Câu số {{$value->cau}}</a>
                </div>
                <div class="list-select-les ">
                    @foreach($value->answers as $keyanswers => $answers )
                        <div class="item-check-select {{$value->display == 1 ?"width-25" :""}} {{(isset($value->check) && $value->dapan == ((int)$keyanswers +1) ? "correct-answer" : "")}}   {{(isset($value->check) && $value->check == ((int)$keyanswers +1)  ? (isset($value->correct) ? ($value->correct ==1 ? "correct-answer": "incorrect-answer"): ""): "")}}">
                            <div class="form-check">
                                <span class="font-weight-bold">{!! $array_char[$keyanswers] !!}</span>
                                <input {{(isset($value->check) && $value->check == ((int)$keyanswers +1)  ? "checked": "")}}  type="radio" name="quest_{{$value->id}}" id="answers_{{$value->cau}}_{{$keyanswers}}" class="form-check-input " value="{{((int)$keyanswers +1)}}">
                                <label  for="answers_{{$value->cau}}_{{$keyanswers}}" class="form-kana text-type">
                                                                <span class="fa-stack icon-input icon-incorrect">
                                                                    <i class="fa fa-square-o fa-stack-1x"></i>
                                                                    <i class="fa fa-times fa-stack-1x fa-inner-close"></i>
                                                                </span>
                                    <span  class="icon-input icon-no-checked ">
                                                                    <i  aria-hidden="true" class="fa fa-square-o "></i>
                                                                </span>
                                    <span  class="icon-input icon-checked ">
                                                                    <i  aria-hidden="true" class="fa fa-check-square-o "></i>
                                                                </span>
                                    <span  class="icon-input icon-correct">
                                                                        <i  aria-hidden="true" class="fa fa-check-square-o"></i>
                                                                </span>
                                    <span  class="text-label jp-font"><p>{!! $array_char[$keyanswers] !!} {!! $answers !!}</p></span>
                                </label>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach


@endif