<?php
include('nav.php');
?>
<!--
  Big Picture by HTML5 UP
  html5up.net | @n33co
  Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
  <head>
    <title>Le Florain: Documents publics</title>
    <meta name="description" content="Site web dedié à la création de la monnaie locale solidaire et citoyenne pour Nancy et ses environs. Le Florain est la dénomination désignée par les futurs usagés!"/>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="style.css" />

    <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
    <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  </head>
  <body id="top">
<?php
$header = new Header();
$header->display();
?>


		<?php
        $xmlDoc = new DOMDocument();
        $xmlDoc->load("documents.xml");

        $x = $xmlDoc->documentElement;
        $cats = $x->getElementsByTagName( "category" );
        $nb_cat = $cats->length;
        for($c=0; $c<$nb_cat; $c++) {

          $cat = $cats[$c];
          $section_id = $cat->getAttribute( "id" );
          $section_title = $cat->getAttribute( "title" );
          $section_subtitle = $cat->getAttribute( "subtitle" );
          print "<section id=\"" . $section_id . "\" class=\"carousel documents wide style3 primary\">";
          print "<header>";
          print "  <h2>" . $section_title . "</h2>";
          print "  <p>" . $section_subtitle . "</p>";
          print "</header>";
	        print "<div class=\"reel\">";

          $docs = $cat->getElementsByTagName( "doc" );
          $nb_doc = $docs->length;
          for($d=0; $d<$nb_doc; $d++) {

              $doc = $docs[$d];

              print "<article>\n";
              print "<a href='". $doc->getAttribute( "url" ) . "'>\n";
              if($doc->hasAttribute( "img" )) {
                print "<img width='100%' src='" . $doc->getAttribute( "img" ) . "'/>";
              }
              print "  <header  class='image featured'>\n";
              print "    <h4>" . $doc->getAttribute( "name" ) . "</h4>\n";
              print "  </header>\n";
              print "  <p>" . $doc->getAttribute( "desc" ) . "</p>\n";
              print " </a>\n";
              print "</article>\n";
          }

          $next_section_id = "top";
          if( $c < $nb_cat - 1 ) {
            $next_section_id = $cats[$c+1]->getAttribute( "id" );
          }
          print "</div>";
          print "<a href=\"#" . $next_section_id . "\" class=\"button style2 down anchored\">Next</a>";
          print "</section>";
        }
    ?>

      <footer id="footer">
        <!-- Icons -->
          <ul class="actions">
            <li><a target="_blank" href="https://www.facebook.com/LeFlorain" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
            <li><a target="_blank" href="https://twitter.com/LeFlorain" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
          </ul>
        <!-- Menu -->
          <ul class="menu">
            <li>&copy; Le Florain</li>
          </ul>
      </footer>

    <!-- Scripts -->
      <script src="assets/js/jquery.min.js"></script>
      <script src="assets/js/jquery.poptrox.min.js"></script>
      <script src="assets/js/jquery.dropotron.min.js"></script>
      <script src="assets/js/jquery.scrolly.min.js"></script>
      <script src="assets/js/jquery.scrollex.min.js"></script>
      <script src="assets/js/jquery.onvisible.min.js"></script>
      <script src="assets/js/skel.min.js"></script>
      <script src="assets/js/util.js"></script>
      <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
      <script src="assets/js/main.js"></script>
      <script src="assets/js/main2.js"></script>


  </body>
</html>
