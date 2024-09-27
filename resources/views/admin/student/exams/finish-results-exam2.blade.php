
<!DOCTYPE html>
<html>
<head>
<!-- CSS only -->
<link href=
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

.noborder, .noborder * {
  height:0px;
  line-height: 0px;
  padding:0px;
  margin:0px;
  outline:0px;
  border:0px;
  overflow: hidden;
}

</style>
</head>
<body background="{{env('IMAGE_PATH')}}/shoujou_waku_A4.jpg">
	<div class="container-fluid">
		<div style="text-align: center;" ><img src="{{env('IMAGE_PATH')}}/logo-elearning.png" alt="logo" class="cs-logo" style="width: 140px;"></div>
		<div style="text-align: center;">
			<h4>HIKARI ACADEMY 日本語試験</h4>
			<h4>認定結果及び成績に関する証明書</h4>
		</div>
		<div style="text-align: center;">
			<h4>HIKARI ACADEMY TEST</h4>
			<h4>CERTIFICATE OF RESULT AND SCORES</h4>
		</div>
		<div style="text-align: center;">
			<h5>HIKARI ACADEMY 株式会社が{{$start_date_ja}}に実施した日本語試験に関し、</h5>
			<h5>認定結果及び成績を次のとおり証明します。</h5>
		</div>
		<div style="text-align: center;">
			<h5>This is to certify the result and the scores of Hikari Academy - Japanese Test</h5>
			<h5>given on {{$start_date_en}} administered by Hikari Academy</h5>
		</div>
	</div>

	<table class="table table-bordered" style="width: 100%; margin-top: 60px">
		<tbody >
		<tr>
			<td>氏名&nbsp;Name</td>
			<td>{{$name}}</td>
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

	<table class="table table-bordered" style="width: 100%; margin-top: 40px" id="table-result">
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
	
	<table class="table table-bordered" style="width: 50%; margin-top: 40px; text-align: center;">
		<tbody>
			<tr>
				<td colspan="3">得点区分別得点<br>Scores by Scoring Section</td>
				<td rowspan="2">総合得点 <br/>Total scores</td>
			</tr>
			<tr>
				<td colspan="2">言語知識（文字・語業・文法） <br/> Language Knowledge <br/>(Vocabulary・Grammar)</td>
				<td>聴解 <br/>Listening</td>
				
			</tr>
			<tr>
				<td colspan="2">{{$quiz1}}/120</td>			
				<td>{{$quiz3}}/60</td>
				<td>{{$total}}/180</td>
			</tr>
			<tr class="noborder">
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</tbody>
	</table>
	
		<div class="table table-borderless" style="width: 80%; margin-top: 60px;text-align: center;" id="">
			<div style="border-top: none; font-size: 12px">主催者</div>
			<div style="border-top: none; font-size: 12px">Administrator</div>
		</div>
	
		<div class="table table-borderless" style="width: 80%; margin-top: 160px;text-align: center;" id="">
			<div style="border-top: none; font-size: 12px">Hikari Academy 株式会社</div>
			<div style="border-top: none; font-size: 12px">Hikari Academy Joint Stock Company</div>
		</div>
</body>
</html>