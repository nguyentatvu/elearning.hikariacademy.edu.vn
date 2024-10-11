<?php
    $answers = json_decode($question->answers);
?>
<!-- Hiện thị câu trả lời ngang -->
<?php if ($question->question_show_type == 0) { ?>
<table style="width:98%">
    <?php $i = 1;?>
    @foreach($answers as $answer)
        <?php if ($i == 1 || $i == 3) { echo "<tr>"; }?>
        <td style="width: 3%"><span><?php echo $i; ?></span></td>
        <td style="width: 47%; padding-left: 10px;">
          {{ change_furigana ($answer->option_value) }}
          @if($answer->has_file)
            <img src="{{$image_path.$answer->file_name}}" width="150">
          @endif
        </td>
        <?php if ($question->total_answers == $i) {
            echo "</tr>";
            break;
        }?>
        <?php if ($i == 2 || $i == 4) { echo "</tr>"; }?>
    <?php $i++; ?>
    @endforeach
</table>
<?php } ?>
<!-- Hiện thị câu trả lời dọc -->
<?php if ($question->question_show_type == 1) { ?>
<table style="width:98%">
        <?php $i = 1;?>
        @foreach($answers as $answer)
            <tr>
                <td style="width: 3%"><span><?php echo $i; ?></span></td>
                <td style="width: 97%; padding-left: 10px;">
                  {{ change_furigana ($answer->option_value) }}
                  @if($answer->has_file)
                    <img src="{{$image_path.$answer->file_name}}" width="150">
                  @endif
                </td>
            </tr>
        <?php if ($question->total_answers == $i) {
            break;
        }?>
        <?php $i++; ?>
        @endforeach
</table>
<?php } ?>
