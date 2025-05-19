<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email</title>
</head>
<body>
    <p><span>Chào bạn</span></p>

    <p>
        <span>Có comment mới từ học viên: </span>
        <strong>{{ $data['student_name'] }}</strong>
    </p>

    <p>
        <span>Nội dung comment: </span>
        <strong>{{ $data['comment'] }}</strong>
    </p>

    <p>
        <span>Khoá học: </span>
        <strong>{{ $data['course_name'] }}</strong>
    </p>

    <p>
        <span>Bài học: </span>
        <strong>{{ $data['lesson_name'] }}</strong>
        <a href="{{ $data['lesson_link'] }}">[Link bài học]</a>
    </p>

    <p><span>Trân trọng</span></p>
</body>
</html>