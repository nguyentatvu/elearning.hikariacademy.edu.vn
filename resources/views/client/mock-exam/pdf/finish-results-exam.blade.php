
<!DOCTYPE html>
<html>
<head>
<!-- CSS only -->
<title>Certificate</title>
<style>
.container-fluid {
  padding-right: 15px;
  padding-left: 15px;
  margin-right: auto;
  margin-left: auto;
}

.h4, h4 {
    font-size: 1.125rem;
}

.h5, h5 {
    font-size: 1rem;
}
.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
    margin-bottom:  1px;
    font-weight: 100;
    line-height: 1.1;
	margin-top: 1px;
}

h1, h2, h3, h4, h5, h6 {
    margin-top: 0;
}
table, th, td {
  border-collapse: collapse;
  border: 2px solid #e0e8f3;
  width: 50%;
  padding: 5px 10px;
}

.th-100 {
	border: 1px solid #e0e8f3;
  	width: 100% !important;
}

body {
    margin: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.5;
    color: #4b5d73;
    text-align: left;
    background-color: #f2f5f9;
    font-family: 'Nunito', sans-serif;
}

.mt-4 {
	margin-top: 4px;
}

</style>
</head>
<body>
	<div class="container-fluid">
		<div style="text-align: center;" >
			<img src="{{ asset('/images/Logo-hikari.png') }}" alt="logo" class="cs-logo" style="width: 140px;">
		</div>
		<div style="text-align: center; margin-top: 15px;">
			<h4>HIKARI ACADEMY 日本語試験</h4>
			<h4>認定結果及び成績に関する証明書&ensp;&ensp;</h4>
		</div>
		<div style="text-align: center;" class="mt-4">
			<h4>HIKARI ACADEMY TEST</h4>
			<h4>CERTIFICATE OF RESULT AND SCORES</h4>
		</div>
		<div style="text-align: center; margin-top: 15px;">
			<h5>HIKARI ACADEMY 株式会社が{{$start_date_ja}}に実施した日本語試験に関し、</h5>
			<h5>認定結果及び成績を次のとおり証明します。</h5>
		</div>
		<div style="text-align: center;" class="mt-4">
			<h5>This is to certify the result and the scores of Hikari Academy - Japanese Test</h5>
			<h5>given on {{$start_date_en}} administered by Hikari Academy</h5>
		</div>
	</div>

	<table class="table table-bordered" style="width: 100%; margin: 60px auto 0;">
		<tbody >
		<tr>
			<td>氏名&nbsp;Name</td>
			<td>{{removeAccents($name)}}</td>
		</tr>
		<tr>
			<td>生年月日&nbsp;Date of Birth</td>
			<td>{{$date_of_birth}}</td>
		</tr>
		<tr>
			<td>件所&nbsp;Address</td>
			<td>{{$address}}</td>
		</tr>
		</tbody>
	</table>

	<table class="table table-bordered" style="width: 100%; margin: 40px auto 0;" id="table-result">
		<tbody>
		<tr>
			<td>レべル&nbsp;Level</td>
			<td>N{{$level}}</td>
		</tr>
		<tr>
			<td>結果&nbsp;Result</td>
			<td>{{$status_ja}}&nbsp;{{$status_en}}</td>
		</tr>
		<tr>
			<td>受験地&nbsp;Test site</td>
			<td>https://elearning.hikariacademy.edu.vn/</td>
		</tr>
		</tbody>
	</table>

	<table class="table table-bordered" style="width: 335px; margin-top: 40px;">
		<tbody>
			<tr>
				<td colspan="3">得点区分別得点<br>Scores by Scoring Section</td>
				<td rowspan="2">総合得点 <br/>Total scores</td>
			</tr>
			<tr>
				<td style="width: 180px;">言語知識（文字・語業・文法） <br/> Language Knowledge <br/>(Vocabulary・Grammar)</td>
				<td>読解 <br/>Reading</td>
				<td>聴解 <br/>Listening</td>
			</tr>
			<tr>
				<td style="width: 180px;">{{$quiz1}}/60</td>
				<td>{{$quiz2}}/60</td>
				<td>{{$quiz3}}/60</td>
				<td>{{$total}}/180</td>
			</tr>
		</tbody>
	</table>

		<div class="table table-borderless" style="width: 80%; margin: 60px auto 0;text-align: center;" id="">
			<div style="border-top: none; font-size: 12px">主催者</div>
			<div style="border-top: none; font-size: 12px">Administrator</div>
		</div>

		<div class="table table-borderless" style="width: 80%; margin: 160px auto 0;text-align: center;" id="">
			<div style="border-top: none; font-size: 12px">Hikari Academy 株式会社</div>
			<div style="border-top: none; font-size: 12px">Hikari Academy Joint Stock Company</div>
		</div>
</body>
</html>