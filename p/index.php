<html style="height: 100%;"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<title>Tout savoir sur Le Florain</title>

<meta name="description" content="TODO">
<meta name="author" content="TODO">

<style>
.componentContainer {
    position: absolute;
    line-height: normal;
    /*-webkit-transform-origin: 135% 135%;
    -moz-transform-origin: 135% 135%;
    transform-origin: 135% 135%;*/
}

.transformContainer {
    -webkit-transform-origin: 0 0;
    -moz-transform-origin: 0 0;
    transform-origin: 0 0;
}

.bg {
    width: 100%;
    height: 100%;
}
</style>

<link rel="stylesheet" href="presentation_files/default-reset.css">
<link href="presentation_files/main.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="presentation_files/default.css" id="theme">
<link href="presentation_files/web-fonts.css" rel="stylesheet" type="text/css">
<link href="presentation_files/backgroundClasses.css" rel="stylesheet" type="text/css">
<link href="presentation_files/surfaceClasses.css" rel="stylesheet" type="text/css">

<link rel="shortcut icon" href="http://strut.io/editor/preview_export/favicon.png" type="image/png">
<link rel="apple-touch-icon" href="http://strut.io/editor/preview_export/apple-touch-icon.png" type="image/png">

<style>
.reveal.themedArea {
    display: block;
    position: absolute;
    top: 0px;
    left: 0px;
    z-index: 0;
    width: 100%;
    height: 100%;
}
</style>

<script async="" src="presentation_files/analytics.js"></script><script type="text/javascript" src="presentation_files/dataset-shim.js"></script><style></style>
<script type="text/javascript" src="presentation_files/impress.js"></script>

<script src="presentation_files/onready.js"></script>
<script src="presentation_files/loadPresentation.js"></script>
<script>
ready(function() {
    if (document.getElementById('launched-placeholder'))
        loadPresentation();

    if (!window.presStarted) {
        startPres(document, window);
        impress().init();   
    }

    if ("ontouchstart" in document.documentElement) { 
        document.querySelector(".hint").innerHTML = 
            "<p>Utilisez la barre d'espace, les flèches et ESC</p>";
    }
});
</script>
</head>
<body class="impress-supported impress-enabled" style="height: 100%; overflow: hidden;">
<style type="text/css">
/*.example {
	border: 4px groove orange;
}*/

a {
    color: #ccdc3e;
    target: "_blank";
}
a:after {
  content:" *";
}
a:hover {
    color: yellow;
}

ul {
    margin-left: 40px;
}

ul.sub {
    font-size: 30px;
}

.strut-surface .florain {
  color: #70706f;
  //background-color: #d7da23;
  //background-color: #70706f;
  background-color: #ccdc3e;
  //color: #ccdc3e;
}

.strut-surface .index {
  background-color: #443C4D;
  color: #ccdc3e;
}

.strut-surface .titre1, .strut-surface .titre2 {
  font-weight: bold;
  color: #ccdc3e;
  //color: #d7da23;
  //color: #70706f;
  font-family: "Ubuntu", sans-serif
}
.strut-surface .titre1 {
  font-size: 60px;
  text-indent: 0em;
}
.strut-surface .titre2 {
  font-size: 50px;
  text-indent: 0em;
}

.strut-surface .texte {
  color: #ccdc3e;
  //color: #d7da23;
  //color: #70706f;
  font-family: "Ubuntu", sans-serif
}
</style>
<style>

</style>
<style>

	.strut-surface {
		background-image: url(../images/Billets.jpg);
	}

</style>
<!-- This is a work around / hack to get the user's browser to download the fonts 
 if they decide to save the presentation. -->
<div style="visibility: hidden; width: 0px; height: 0px">
<img src="presentation_files/Lato-Bold.woff">
<img src="presentation_files/HammersmithOne.woff">
<img src="presentation_files/Droid-Sans-Mono.woff">
<img src="presentation_files/Gorditas-Regular.woff">
<img src="presentation_files/FredokaOne-Regular.woff">
<img src="presentation_files/Ubuntu.woff">
<img src="presentation_files/Ubuntu-Bold.woff">
<img src="presentation_files/PressStart2P-Regular.woff">
<img src="presentation_files/Lato-BoldItalic.woff">
<img src="presentation_files/AbrilFatface-Regular.woff">
<img src="presentation_files/Lato-Regular.woff">
</div>

<div class="fallback-message">
    <p>Your browser <b>doesn't support the features required</b> by impress.js, so you are presented with a simplified version of this presentation.</p>
    <p>For the best experience please use the latest <b>Chrome</b>, <b>Safari</b> or <b>Firefox</b> browser.</p>
</div>

<div class="bg strut-surface">
<div class="bg innerBg strut-slide-7">
<div id="impress" style="position: absolute; transform-origin: left top 0px; transition: all 0ms ease-in-out 0ms; transform-style: preserve-3d; top: 50%; left: 50%; transform: perspective(2000px) scale(0.5);"><div style="position: absolute; transform-origin: left top 0px; transition: all 0ms ease-in-out 0ms; transform-style: preserve-3d; transform: rotateZ(0deg) rotateY(0deg) rotateX(0deg) translate3d(-12151.5px, -6589.44px, 0px);">
	
<?php
  $xmlDoc = new DOMDocument();
  $xmlDoc->load('slides.xml');
  $x = $xmlDoc->documentElement;
  $slides = $x->getElementsByTagName('slide');
  $nb = $slides->length;

  $pi = 3.1415729;
  $delta = $pi * 2 / ($nb - 1);
  $rayon = 5300;
  $alpha = $pi;

  $ox = 7250;
  $oy = 5000;

  for ($pos = 0; $pos < $nb; ++$pos) {
      $slide = $slides[$pos];

      $width = $slide->getAttribute('width');
      $height = $slide->getAttribute('height');
      if ($pos > 0) {
          $w = (int) substr($width, 0, strlen($width) - 2);
          $h = (int) substr($height, 0, strlen($height) - 2);
          $alpha = $alpha + $delta;
          $datax = $ox + $rayon * cos($alpha) *1.2 + $w / 2;
          $datay = $oy + $rayon * sin($alpha) *.9+ $h / 2;
      } else {
          $datax = $slide->getAttribute('data-x');
          $datay = $slide->getAttribute('data-y');
      }

      $datascale = $slide->getAttribute('data-scale');

      $style = $slide->getAttribute('style');

      $title = $slide->getElementsByTagName('title')[0]->nodeValue;
      $text = $slide->getElementsByTagName('text')[0]->nodeValue;

      $datastate = 'strut-slide-'.$pos;
      $id = 'step-'.($pos + 1);

      $steelfish = "'Steelfish'";
      $ubuntu = "'Ubuntu'";

      echo '<div class="step future" data-state="'.$datastate.'" data-x="'.$datax.'" data-y="'.$datay.'" data-scale="'.$datascale.'" id="'.$id."\"\n";
      echo ' style="'.$style."\">\n";
      echo ' <div class="bg-solid-lavender slideContainer '.$datastate.'" style="width:'.$width.'; height:'.$height.";\">\n";

      echo "  <div class=\"componentContainer florain\" style=\"top: 38px; left: 41px; -webkit-transform:   ; -moz-transform:   ; transform:   ; width: px; height: px;\">\n";
      echo "   <div class=\"transformContainer\" style=\"-webkit-transform: scale(1, 1); -moz-transform: scale(1, 1); transform: scale(1, 1)\">\n";
      echo "    <div style=\"font-size: 128px;\" class=\"antialias\">\n";
      echo '     <table width="'.$width.'" cellspacing="20" cellpadding="20" border="1"><tbody><tr>';
      echo '      <td class="florain" valign="top"><font face="'.$steelfish.'", sans-serif\">&nbsp;Le Florain</font></td>';
      echo '      <td valign="top"><span  style="font-size: 58px;"><font color="#ffffff"><b>'.$title.'</b></font></span></td>';
      echo '     </tr></tbody></table>';
      echo "  </div></div></div>\n\n";

      echo "  <div class=\"componentContainer\" style=\"top: 43px; left: 948px; width: 148.7947444174683px; height: 149.45312824232437px;\">\n";
      echo "   <div class=\"transformContainer\" style=\"-webkit-transform: scale(, ); -moz-transform: scale(, ); transform: scale(, )\">\n";
      echo "    <img src=\"presentation_files/logo-monnaie.svg\" style=\"width: 100%; height: 100%\">\n";
      echo "  </div></div>\n\n";

      echo "  <div class=\"componentContainer index\" style=\"top: ".((int)$height-77)."px; left: ".((int)$width-105)."px;\">\n";
      echo "   <div class=\"transformContainer\" style=\"-webkit-transform: scale(, ); -moz-transform: scale(, ); transform: scale(, )\">\n";
      echo "    <div style=\"font-size: 64px; align:center;\" class=\"antialias\">\n";
      echo "    <p>&nbsp;".($pos+1)."</p>\n";
      echo "  </div></div></div>\n\n";

      /*
      echo "<div class=\"componentContainer titre1\" style=\"top: 250px; left: 153px; -webkit-transform:   ; -moz-transform:   ; transform:   ; width: px; height: px;\">\n";
      echo "<div class=\"transformContainer\" style=\"-webkit-transform: scale(1, 1); -moz-transform: scale(1, 1); transform: scale(1, 1)\">\n";
      echo "<div style=\"font-size: 36px;\" class=\"antialias\">\n";
      echo '<font face="'.$ubuntu.', sans-serif">'.$title."<br></font>\n";
      echo "</div></div></div>\n\n";
      */

      echo "<div class=\"componentContainer texte\" style=\"top: 250px; left: 100px; -webkit-transform:   ; -moz-transform:   ; transform:   ; width: px; height: px;\">\n";
      echo "<div class=\"transformContainer\" style=\"-webkit-transform: scale(1, 1); -moz-transform: scale(1, 1); transform: scale(1, 1)\">\n";
      echo "<div style=\"font-size: 36px;\" class=\"antialias\">\n";
      echo '<font face="'.$ubuntu.', sans-serif">'.$text."<br/></font>\n";
      echo "</div></div></div>\n\n";

      echo "</div></div>\n\n\n";
  }
?>

	<div id="overview" class="step future" data-state="strut-slide-overview" data-x="7680" data-y="5306.88" data-scale="9" style="position: absolute; transform: translate(-50%, -50%) translate3d(7680px, 5306.88px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale(9); transform-style: preserve-3d;"></div>
	
</div></div>
<div class="hint">
    <p>Utilisez la barre d'espace, les flèches et ESC</p>
</div>
</div>
</div></body></html>
