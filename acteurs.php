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
    <title>Le Florain: Monnaie Locale et Complementaire de l'Aire de Vie Nanceienne</title>
    <meta name="description" content="Site web dedié a la création de la monnaie locale solidaire et citoyenne pour Nancy et ses environs. Le Florain est la dénomination désignée par les futurs usagés!"/>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="style.css" />
    <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
    <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
  </head>
  <body id="top">
<?php
$header = new Header();
$header->display();
?>

        <!-- Main -->
          <div id="acteurs">
<?php
$header->display_acteurs_nav();
?>

<?php

      $xmlDoc = new DOMDocument();
      $xmlDoc->load("acteurs-cat.xml");

      $x = $xmlDoc->documentElement;
      $racine = $x->getElementsByTagName( "categories" );
      print "<h2>" . $x->getAttribute( "titre" ) . "</h2>";

      print "<section class='paragraphe'>";
      $categories = $x->getElementsByTagName( "categorie" );
      $nb_cat = $categories->length;

      print "<section class='column toc'>";
	   
      for($cat=0; $cat<$nb_cat; $cat++) {
          $categorie = $categories[$cat];
	  if( $categorie->hasAttribute( "type" ) &&
              $categorie->getAttribute( "type" ) != ""
	  ) {
	      $acteurs = $categorie->getElementsByTagName( "acteur" );
              $nb = $acteurs->length;
	      print "<h4 class='toc'><a href='#cat" . $cat . "'>" . $categorie->getAttribute( "type" ) . " (" . $nb . ")</a></h4>";
              $sous_categories = $categorie->getElementsByTagName( "scat" );
              $nb_sous_cat = $sous_categories->length;
              for($scat=0; $scat<$nb_sous_cat; $scat++) {
                  $sous_categorie = $sous_categories[$scat];
	          if( $sous_categorie->hasAttribute( "type" ) &&
                      $sous_categorie->getAttribute( "type" ) != ""
	          ) {
	              $acteurs = $sous_categorie->getElementsByTagName( "acteur" );
                      $nb = $acteurs->length;
	              print "<h5 class='toc'><a href='#cat" . $cat . "scat" . $scat . "'>" . $sous_categorie->getAttribute( "type" ) . " (" . $nb . ")</a></h5>";
	          }
              }
	  }
      }
      print "</section>";

      for($cat=0; $cat<$nb_cat; $cat++) {

          $categorie = $categories[$cat];
    	  if( $categorie->hasAttribute( "type" ) ) {
	      print "<h1 id='cat" . $cat . "'>" . $categorie->getAttribute( "type" ) . "</h1>";
	  }


          $sous_categories = $categorie->getElementsByTagName( "scat" );
          $nb_sous_cat = $sous_categories->length;
          for($scat=0; $scat<$nb_sous_cat; $scat++) {
              $sous_categorie = $sous_categories[$scat];
	      if( $sous_categorie->hasAttribute( "type" ) &&
                  $sous_categorie->getAttribute( "type" ) != ""
	      ) {
	          print "<h2 id='cat" . $cat . "scat" . $scat . "'>" . $sous_categorie->getAttribute( "type" ) . "</h2>";

	      }
	      $acteurs = $sous_categorie->getElementsByTagName( "acteur" );
              $nb = $acteurs->length;

	      print "<section class='column'>";



              $indexes = range(0, $nb-1);
              shuffle($indexes);
              for($pos=0; $pos<$nb; $pos++) {
                $acteur = $acteurs[$indexes[$pos]];
    	        if( $acteur->hasAttribute( "attente" ) ) {
	            continue;
	        }
	        $acteur_class = "commerce";
	        if( $acteur->hasAttribute( "comptoir" ) &&
		    $acteur->getAttribute( "comptoir" ) == "oui" ) {
	            $acteur_class = "comptoir";
	        }

	        $image = $acteur->getAttribute( "image" );
	        $siteweb = "";
	
	        $titre = $acteur->getAttribute( "titre" );
	        $desc = $acteur->getAttribute( "desc" );
	        $adresse = $acteur->getAttribute( "adresse" );

                $p = <<<EOD
                <acteur class="$acteur_class">
                    <h2>$titre</h2>
                    <img src="images/acteurs/$image" alt="$titre" />
EOD;
                print $p;
	        if( $acteur_class == "comptoir" ) {
	            print "<h3>Comptoir de change</h3>";
	        }
	        if( $acteur->hasAttribute( "message" ) ) {
	            print "<p class='message'>" . $acteur->getAttribute( "message" ) . "</p>";
	        }
                $p = <<<EOD
                <p>$desc</p>
                <p>$adresse</p>
EOD;
                print $p;
	        if( $acteur->hasAttribute( "siteweb" ) ) {
	            $siteweb = $acteur->getAttribute( "siteweb" );
                    $p = <<<EOD
                    <a href="http://$siteweb">$siteweb</a>
EOD;
                    print $p;
	        }
                print "</acteur>";
              }
	      print "</section>";
          }
      }

?>
              </section> <!-- Liste -->


          </div>

 
      <footer id="footer">
        <!-- Icons -->
          <ul class="actions">
            <li><a target="_blank" href="https://www.facebook.com/LeFlorain" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
            <li><a target="_blank" href="https://twitter.com/LeFlorain" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
          </ul>
        <!-- Menu -->
          <ul class="menu"> <li>&copy; Le Florain</li> </ul>
      </footer>
      </div>

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
