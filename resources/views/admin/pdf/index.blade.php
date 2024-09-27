<!doctype html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>PDF</title>
<style>
@font-face{
    font-family: ipag;
    font-style: normal;
    font-weight: normal;
    src:url('{{ storage_path('fonts/ipag.ttf')}}');
}
body {
font-family: ipag;
}
</style>
</head><body>
    <p>
    	@php
    		echo $data['u'];
    	@endphp
    	123
	</p>

</body></html>