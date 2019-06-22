<!DOCTYPE HTML>
<!--
	Lens by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Banque d'images du Florain</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
	</head>
	<body class="is-preload-0 is-preload-1 is-preload-2">

		<!-- Main -->
			<div id="main">

				<!-- Header -->
					<header id="header">
						<h1>Banque d'images du Florain</h1>
					</header>
					<section id="thumbnails">
					<?php

include '../pix_tools.php';
$rep = opendir('.');
while ($file = readdir($rep)) {
    $len = strlen($file);
    if ($len > 5) {
        if ( substr($file, $len - 3) == 'jpg' || substr($file, $len - 3) == 'png') {
            $imgpath = './'.$file;

            if (is_file($imgpath)) {
                $thpath = './thumbs/th_'.$file;
                if (!is_file($thpath)) {
                    makeSmallerImage($imgpath, $thpath, 250, 250);
                }
                $p = <<<EOD
		<article>
		    <a class="thumbnail" href="$imgpath" data-position="center center"><img src="$thpath" alt="" /></a>
	        <h2>$file</h2>
	    </article>
EOD;
                echo $p;
            }
        }
    }
}
?>

					</section>


			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>