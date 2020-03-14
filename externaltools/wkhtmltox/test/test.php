<?php
$pdfOpt = '../bin/wkhtmltopdf ';
$pdfOpt .= '--page-width 62mm ';
$pdfOpt .= '--page-height 35mm ';
$pdfOpt .= '--margin-top 0mm ';
$pdfOpt .= '--margin-right 0mm ';
$pdfOpt .= '--margin-bottom 0mm ';
$pdfOpt .= '--margin-left 0mm ';
$pdfOpt .= '"test.html" ';
$pdfOpt .= '"test.pdf" ';
system($pdfOpt);


?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Unbenanntes Dokument</title>
</head>

<body>
<a href="test.pdf">Download PDF</a>
</body>
</html>