<?php
include 'nav.php';
?>
<!--
  Big Picture by HTML5 UP
  html5up.net | @n33co
  Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
  <head>
    <title>Le Florain: Monnaie Locale Citoyenne et Complémentaire à Nancy et environs</title>
    <meta name="description" content="Site web dedié à la création de la monnaie locale solidaire et citoyenne pour Nancy et ses environs. Le Florain est la dénomination désignée par les futurs usagés!"/>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/style_accueil.css" />
    <link rel="stylesheet" href="assets/css/nav.css" />
    <link rel="stylesheet" href="assets/css/bs_carousel.css" />
    <link rel="stylesheet" href="style.css" />

    <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
    <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>-->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <style>
ol.carousel-indicators li {
  background: #d7da23;
}

ol.carousel-indicators li.active {
  background: #615f5f;
}
</style>
  </head>
  <body id="top">

<?php
$header = new Header();
$header->display();
?>

<section id="accueil" class="main style1 left red fullscreen">
  <div class="content box style1">
          
    <header>
      <h2>COVID-19</h2>
      <h4>Solidarité avec les commerces locaux</h4>
    </header>
    
    <div class="anniv menu" >
      <div>
        <p>La crise sanitaire qui s’ajoute à la crise climatique et sociale vient accentuer l’importance de la résilience économique des territoires locaux. Un certain nombre de commerces et associations adhérents au Florain traversent actuellement des difficultés bien compréhensibles.

        <br/><br/>La crise économique et financière qui s’amorce confirme notre analyse : oui la monnaie locale est un des outils essentiels de résilience des territoires !
        </p>
      </div>
    </div>
      <img src="http://beta.monnaielocalenancy.fr/wp-content/uploads/Image-Solidarit%C3%A9-Florain-300x300.png"/>

  </div>
  <footer>
    <a href="#agrees" class="button style2 down anchored">More</a>
  </footer>
</section>




      <section id="agrees" class="main styleAcceuil right dark fullscreen">
        <div class="content box style1">
          <br/><h2> Les derniers acteurs agréés </h2>
<div id="myCarousel" class="carousel slide">

    <?php

      $xmlDoc = new DOMDocument();
      $xmlDoc->load('acteurs-cat.xml');
      $x = $xmlDoc->documentElement;

      $nouveaux = array();
      $idx = 0;

      $acteurs = $x->getElementsByTagName('acteur');
      $nb_acteurs = $acteurs->length;

      $today = new DateTime();
      $today->setTimestamp(time());
      $oneMonthAgo = $today->sub(DateInterval::createFromDateString('4 month'));

      for ($a = 0; $a < $nb_acteurs; ++$a) {
          $acteur = $acteurs[$a];
          $date = $acteur->getAttribute('date');
          $aggDate = DateTime::createFromFormat('d-m-Y', $date);
          if ($oneMonthAgo < $aggDate) {
              $nouveaux[$idx] = $acteur;
              $idx = $idx + 1;
          }
      }

      // <!-- Indicators -->
      echo "<ol class='carousel-indicators'>\n";
      echo "<li data-target='#myCarousel' data-slide-to='0' class='active'></li>\n";
      for ($a = 1; $a < count($nouveaux); ++$a) {
          echo "<li data-target='#myCarousel' data-slide-to='".$a."' ></li>\n";
      }
      echo "</ol>\n";

      $indexes = range(0, count($nouveaux) - 1);
      shuffle($indexes);
      // <!-- Wrapper for slides -->
      echo "<div class='carousel-inner'>\n";
      $acteur = $nouveaux[$indexes[0]];
      $image = $acteur->getAttribute('image');
      $titre = $acteur->getAttribute('titre');
      $bref = $acteur->getAttribute('bref');
      $desc = $acteur->getAttribute('desc');
      $lenmax = 260;
      if (strlen($desc) > $lenmax) {
          $desc = substr($desc, 0, $lenmax - 7).' [...]';
      }
      $adresse = $acteur->getAttribute('adresse');
      $siteweb = $acteur->getAttribute('siteweb');
      echo "  <div class='item active'>\n";
      echo "     <img src='images/acteurs/".$image."' alt='".$titre."'/>\n";
      echo "     <div>\n";
      echo "       <h3 align='left'>".$bref.'</h3>';
      echo "       <h1 align='center'>".$titre.'</h1>';
      echo '       <p >'.$desc."</p>\n";
      echo "       <div class='coordonnees'>\n";
      echo '         <p >'.$adresse."<br/>\n";
      echo "         <a href='http://".$siteweb."'>".$siteweb."</a></p>\n";
      echo "       </div>\n";
      echo "     </div>\n";
      echo "  </div>\n";
      for ($a = 1; $a < count($nouveaux); ++$a) {
          $acteur = $nouveaux[$indexes[$a]];
          $image = $acteur->getAttribute('image');
          $titre = $acteur->getAttribute('titre');
          $bref = $acteur->getAttribute('bref');

          $desc = $acteur->getAttribute('desc');
          if (strlen($desc) > $lenmax) {
              $desc = substr($desc, 0, $lenmax - 7).' [...]';
          }
          $adresse = $acteur->getAttribute('adresse');
          $siteweb = $acteur->getAttribute('siteweb');
          echo "  <div class='item'>\n";
          echo "    <img src='images/acteurs/".$image."' alt='".$titre."'/>\n";
          echo "     <div>\n";
          echo "       <h3 align='left'>".$bref.'</h3>';
          echo "      <h1 align='center'>".$titre.'</h1>';
          echo '      <p >'.$desc."</p>\n";
          echo "      <div class='coordonnees'>\n";
          echo '        <p >'.$adresse."<br/>\n";
          echo "        <a href='http://".$siteweb."'>".$siteweb."</a></p>\n";
          echo "      </div>\n";
          echo "     </div>\n";
          echo "  </div>\n";
      }
      echo "</div>\n";
    ?>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  </div>
  
  </div>
        <footer>
          <a href="#recap" class="button style2 down anchored">More</a>
        </footer>
  </section>

      <section id="recap" class="main style2 right dark fullscreen">
        <div class="content box style1">
          
  <header>
<h2>Le Florain</h2>
<h4>La monnaie locale du sud de la Meurthe et Moselle</h4>
          </header>
<h3>Depuis la fete de lancement le 7 octobre 2017, le Florain c'est:</h3>

<h4>Un réseau de plus de 200 professionnels,</h4>
<h4>12 comptoirs de change,</h4>
<h4>110.000 Florains en circulation,</h4>
<h4>des groupes locaux à Toul et Lunéville</h4>
<br/>
<h3>Vous aussi, entrez dans la dynamique !</h3>
<br/>
      <footer>
	<ul class="style2">
  <li>  <a href="#readhesion" class="button style2">(ré)adhésion</a></li>
	 <li>  <a href="#contact" class="button style2">Contact</a></li>
	 <li>  <a href="http://beta.monnaielocalenancy.fr/lettredinformations" class="button style2">Recevoir les info</a></li>
	</ul>
      </footer>
  </div>
        <footer>
          <a href="#readhesion" class="button style2 down anchored">More</a>
        </footer>
      </section>

      <!--
      <section id="accueil" class="main style2 right dark fullscreen">
        <div class="content box style1">
          <header>
<h2>Le Florain</h2>
<h4>La monnaie locale de Nancy et ses alentours</h4>
          </header>
<h3>Depuis la fete de lancement le 7 octobre 2017, le Florain c'est:</h3>

<h4>Plus de 500 adhérents</h4>
<h4>Un réseau de plus de 100 professionnels,</h4>
<h4>8 comptoirs de change,</h4>
<h4>1000 Florains de plus par semaine dans le circuit.</h4>
<br/>
<h3>Vous aussi, entrez dans la dynamique !</h3>
<br/>
      <footer>
	<ul class="style2">
	 <li>  <a href="acteurs.php" class="button style2">Où utiliser mes Florains?</a></li>
	 <li>  <a href="http://beta.monnaielocalenancy.fr/que-puis-je-faire/" class="button style2">Bénévolat</a></li>
	 <li>  <a href="#contact" class="button style2">Contact Pros</a></li>
	 <li>  <a href="http://beta.monnaielocalenancy.fr/lettredinformations" class="button style2">Recevoir les info</a></li>
	</ul>
      </footer>
        </div>

      	<div id="slider_news" class="slider base box red">
          <h3> Les dernières nouvelles</h3>
        <table>
        <php
        require_once '../wp.monnaielocalenancy.fr/wp-load.php';
        // Connexion a la base de donnees
        try {
            $str = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
            $bdd = new PDO($str, DB_USER, DB_PASSWORD);
        } catch (Exception $e) {
            die('Erreur : '.$e->getMessage());
        }

        // Recuperation des 3 derniers messages
        $reponse = $bdd->query("SELECT guid, post_title, post_content FROM wp_posts WHERE post_status='publish' ORDER BY post_date DESC LIMIT 3") or die(print_r($bdd->errorInfo()));

        // Affichage de chaque message (toutes les donnï¿½es sont protï¿½gï¿½es par htmlspecialchars)
        while ($donnees = $reponse->fetch()) {
            $post_content = $donnees['post_content'];
            $xmlDoc = new DOMDocument();
            $xmlDoc->loadHTML($post_content);
            $x = $xmlDoc->documentElement;
            $images = $x->getElementsByTagName('img');
            if (count($images) > 0) {
                echo '<tr>';
                echo ' <td>';
                echo " <a href='".$donnees['guid']."'>";
                echo '  <h2>'.iconv('CP1252', 'UTF-8', $donnees['post_title']).'</h2>';
                $src = $images[0]->getAttribute('src');
                echo "  <img src='".$src."' />";
                echo ' </a>';
                echo ' </td>';
                echo '</tr>';
            }
        }

        $reponse->closeCursor();
        ?>
          </table>
        </div>

        <footer>
          <a href="#monnaie" class="button style2 down anchored">More</a>
        </footer>
      </section>
-->

      <section id="readhesion" class="main style2 right red fullscreen">
        <div class="content box style1">
          <header>
          <h2>Campagne de ré-adhésion 2020.</h2>
          </header>
          <p>Qui dit nouvelle année dit ré-adhésion ! Nous vous invitons donc, si vous n'avez pas encore votre carte "2020", à vous rendre dans un <a href="http://www.monnaielocalenancy.fr/change.php">bureau de change</a> pour renouveler votre adhésion.<br/><b class="dark">&nbsp;NOUVEAU!&nbsp;</b> Vous pouvez également le faire en ligne en suivant <a href="https://www.helloasso.com/associations/le-florain/adhesions/le-florain-formulaire-d-adhesion-utilisateurs">ce lien!</a></p>
          <p>Comme les années passées, nous avons souhaité maintenir un montant minimum d'adhésion relativement modeste (5 €) pour permettre à chacun et chacune de s'inscrire dans la dynamique du Florain. Si vous en avez la possibilité et l'envie, n'hésitez surtout pas à adhérer pour un montant supérieur (maximum 50 €), les adhésions étant la principale source de financement de l'association !</p>
          <h4>le 1 % associatif se poursuit!</h4>
          <p>Lors de votre adhésion vous serez invité à <a href="http://www.monnaielocalenancy.fr/doc/UnPourCentAsso.pdf">choisir une association</a> qui recevra en fin d'année l'équivalent de 1% de ce que vous avez changé (des conversions Euros-Florains que vous aurez effectuées en 2020), sans que cela vous coûte quoi que ce soit. Tout est expliqué en détail <a href="http://beta.monnaielocalenancy.fr/2019/01/02/le-1-associatif-une-nouveaute-pour-2019">sur cet article.</a></p>
        </div>
        <footer>
          <a href="#monnaie" class="button style2 down anchored">More</a>
        </footer>
      </section>


    <!-- monnaie -->
      <section id="monnaie" class="main style1 left dark fullscreen">
        <div class="content box style1">
          <header>
            <h2>Pourquoi une monnaie locale?</h2>
          </header>

          <h3>Monnaie locale et acte de liberté citoyenne.</h3><br>
          <p class="onecolumn">Une monnaie locale citoyenne permet de relocaliser l'économie, de rendre les citoyens maîtres de leurs échanges, acteurs du fonctionnement de leur économie et libres d'en définir les valeurs.
</p>
          <footer><a target="_blank" class="button icon first" href="#comment">Et concretement ?</a><footer>
        </div>
        <a href="#comment" class="button style2 down anchored">Next</a>
      </section>


      <section id="comment" class="main style2 left fullscreen">
        <div class="content box style1 ">
          <header>
            <h2>Comment ca marche ?</h2>
          </header>
	        <h3>Le Florain, qu'est-ce que c'est ?</h3>
          <p>Le Florain est une Monnaie Locale Citoyenne  et Complémentaire à l'Euro qui circule sur Nancy et le Sud de la Meurthe-et-Moselle depuis octobre 2017.
          <br/>
          Sa valeur est très simple:
          1 Florain =  1 Euro.
          <br/>
          Cette monnaie n'est utilisable que dans le réseau des entreprises et associations adhérentes
          au Florain. A consulter <a href="acteurs.php">ici</a>.</p>
          <h3>Oui, mais à quoi ça sert?</h3>
          <p>A chaque fois que je dépense un Florain, je consomme auprès d'un acteur local qui partage des valeurs de solidarité et de respect des êtres humains et de leur environnement.
<br/>
Je contribue à renforcer l'économie locale, puisque ces acteurs sont également incités à re-dépenser dans le réseau les Florains qu'ils recoivent.
<br/>
Je favorise ainsi la consommation en circuits-courts auprès d'acteurs engagés.</p>
<p>Tous les euros échangés sont placés sur un compte de réserve à la <a href="https://www.lanef.com">Nef</a>, une coopérative financière qui partage les valeurs du Florain.
La Nef s'engage à investir, sur le territoire du Florain, le double de la valeur du fonds de réserve dans des projets correspondants à nos valeurs.
          <h3>Je me lance!</h3>
          <h4>J'adhère à l'association</h4>
          <p>
          Coût de l'adhésion annuelle: Adultes: entre 5 et 50 euros. Mineurs de moins de 16 ans: entre 1 et 50 euros. Le montant est au libre choix de chaque personne.
          <span style="float:right;"><a href="https://www.helloasso.com/associations/le-florain/adhesions/le-florain-formulaire-d-adhesion-utilisateurs" class="button styleDon">J'adhère en ligne</a><span>

          <br/>
          <h4>Je convertis mes euros en Florains</h4>
          <p>contre des coupures de 1, 2, 5, 10 et 20 Florains dans les comptoirs de change à retrouver <a href="change.php">ici</a></p>
          <h4>Je fais circuler mes Florains dans le réseau:  </h4>
          <p>
          - à consulter en ligne <a href="acteurs.php">ici</a><br/>
          - ou des annuaires locaux à imprimer
          <a href="annuaire.php?edition=Lun%C3%A9ville">Lunéville</a>,
          <a href="annuaire.php?edition=Nancy">Nancy</a>,
          <a href="annuaire.php?edition=Pont-%C3%A0-Mousson">Pont-à-Mousson</a> et 
          <a href="annuaire.php?edition=Toul">Toul</a><br/>
          - un <a href="annuaire.php?type=Livret">catalogue</a> complet et détaillé<br/>
          - une <a href="carte.php">carte interactive </a>
          </p>
            
        </div>
        <a href="#medias" class="button style3 down anchored">Next</a>
      </section>

      <!-- One -->
      <section id="medias" class="main style1 dark fullscreen">
      <div class="content box style2">

        <h4>Notre Monnaie Locale, le Florain est en circulation!</h4>
        <h6>Reportage France3 8 octobre 2017</h6>
	<a href="https://vimeo.com/237366914" width="50%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen><img src="images/caisse.jpg" height="250"/></a>
        <br/>
        <h4>Interview de Steven sur France3 Lorraine</h4>
        <h6>(29 juin 2017)</h6>
	<a href="https://player.vimeo.com/video/224298093" width="50%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen><img src="images/F3Steven.jpg"/></a>
                        </div>
      <a href="#contact" class="button style1 down anchored">Next</a>


      <!--
      <div id="fb-root"></div>
      <script>(function(d, s, id) {
               var js, fjs = d.getElementsByTagName(s)[0];
               if (d.getElementById(id)) return;
               js = d.createElement(s);
        js.id = id;
               js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.3";
        fjs.parentNode.insertBefore(js, fjs);
        }
        (document, 'script', 'facebook-jssdk'))
        ;
      </script>
      <div class="fb-video" data-allowfullscreen="1" data-href="/112087685834450/videos/vb.112087685834450/151470908562794/?type=3">
        <div class="fb-xfbml-parse-ignore">
          <blockquote cite="https://www.facebook.com/112087685834450/videos/151470908562794/">
            <a href="https://www.facebook.com/112087685834450/videos/151470908562794/">France 3 Lorraine - 19/20h - Forum Ouvert</a>
            <p>Le chantier citoyen sur la monnaie locale, c&#039;est une affaire qui roule ! Comme le témoigne le 19/20h de France 3 Lorraine du 18 janvier sur le Forum Ouvert.</p>
            Posté par <a href="https://www.facebook.com/Monnaie-Locale-Nancy-112087685834450/">Monnaie Locale Nancy</a> sur mardi 19 janvier 2016
      </blockquote>
  </div>
</div>



        </div>
        -->
      </section>

      <section id="contact" class="main style3 dark secondary">
        <div class="content container">
          <header>
            <h2>Nous contacter.</h2>
            <p>Utilisez ce formulaire pour toute demande d'informations.</a></strong>.</p>
          </header>
          <div class="box container 75%">

          <!-- Contact Form -->
              <form method="post" action="envoyer.php">
                <div class="row 50%">
                  <div class="6u 12u(mobile)"><input type="text" name="name" id="name" placeholder="Nom"></div>
                  <div class="6u 12u(mobile)"><input type="text" name="prenom" id="prenom" placeholder="Prénom"></div>
                </div>
                <div class="row 50%">
                  <div class="6u 12u(mobile)"><input type="text" name="phone" id="phone" placeholder="Téléphone"></div>
                  <div class="6u 12u(mobile)"><input type="email" name="email" id="email" placeholder="Email"></div>
                </div>
                <div class="row 50%">
                  <div class="12u"><textarea name="message" placeholder="Message" rows="4"></textarea></div>
                </div>
                <div class="row 50%">
                  <div class="6u 12u(mobile)"><h4>Veuillez entrer le résultat de un plus un:</h4></div>
                  <div class="6u 12u(mobile)"><input type="text" name="calcul" id="calcul" placeholder="Un robot ne sait pas !"></div>
                </div>
                <div class="row">
                  <div class="12u">
                    <ul class="actions">
                      <li><input type="submit" name="envoyer" value="Envoyer"></li>
                    </ul>
                  </div>
                </div>
              </form>

          </div><br><br><br>
        </div><a href="#top" class="button style2 down anchored">Next</a>
      </section>


      <footer id="footer">

        <!-- Icons -->
          <ul class="actions">

            <li><a rel="noopener noreferrer" target="_blank" href="https://www.facebook.com/LeFlorain" class="icon fa-facebook"><span class="label">Facebook</span></a></li>

            <li><a rel="noopener noreferrer" target="_blank" href="https://twitter.com/LeFlorain" class="icon fa-twitter"><span class="label">Twitter</span></a></li>


          </ul>

        <!-- Menu -->
          <ul class="menu">
            <li>&copy; Le FLorain</li><li>  </ul>

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
      
    <script>
    	function hideMessage() {
          $('.crowdf').fadeOut(600);
          $('.phone').fadeOut(600);
          $('.crowdf').removeClass( "crowdf" );
          $('.phone').removeClass( "phone" );
      };
		</script>
		<script type="text/javascript">
 
 
	 		/* une fois le document charge, executer la fonction suivante */
	 		$(document).ready(function() {
        
	 			/* On cree l'element html a la fin de la page 
				$('body').append('<a class="crowdf nav fa-angle-up nav scrollTo"> <p><b><font size="+2">Assemblée générale</font></b><br/> <font size="+1"><b> le 3 septembre 2017</b><br/> 15h à la MJC des 3 maisons<br> Nouveaux statuts,<br> projet de gouvernance...<br> Soyons nombreux,<br> parlez en autour de vous.<br> <b><font size="+2">Fête de lancement</font><br> le 7 Octobre 2017</b><br> 14h - minuit<br> <i><font size="-1">Le lieu sera communiqué<br> ultérieurement</font></i><br> le Florain sera émis,<br> de nombreux partenaires<br> seront présents!</p> </a>');
				$('body').append('<a class="phone ban_tel"> <b> <font size="+1"> <font size="+2"> Assemblée générale </font> le 3 septembre 2017 <br/> <font size="+2"> Fête de lancement </font> le 7 Octobre 2017 </font> </b> </a>');

$('body').append('<footer class="crowdf"><p>&nbsp;Contribuez à faire grandir le Florain.&nbsp;&nbsp;<span style="color:red;" class=" icon fa-angle-double-left" onclick="hideMessage()"/></p><a href="https://www.zeste.coop/fr/faire-grandir-le-florain" class="button styleDon">Financement participatif sur Zeste</a></footer>');
  $('body').append('<footer class="phone ban_tel"><p>&nbsp;Contribuez à faire grandir le Florain.&nbsp;&nbsp;<span style="color:red;" class=" icon fa-angle-double-left" onclick="hideMessage()"/></p><a href="https://www.zeste.coop/fr/faire-grandir-le-florain" class="button styleDon">Financement participatif sur Zeste</a></footer>');
*/
$('body').append('<footer class="crowdf"><p>&nbsp;Le Florain a besoin de votre soutien.&nbsp;&nbsp;<span style="color:red;" class=" icon fa-angle-double-left" onclick="hideMessage()"/></p><a href="https://www.helloasso.com/associations/le-florain/adhesions/le-florain-formulaire-d-adhesion-utilisateurs" class="button styleDon">J\'adhère</a><a href="https://www.helloasso.com/associations/le-florain/formulaires/1/widget" class="button styleDon">Je fais un don</a></footer>');
  $('body').append('<footer class="phone ban_tel"><p>&nbsp;Le Florain a besoin de votre soutien.&nbsp;&nbsp;<span style="color:red;" class=" icon fa-angle-double-left" onclick="hideMessage()"/></p><a href="https://www.helloasso.com/associations/le-florain/adhesions/le-florain-formulaire-d-adhesion-utilisateurs" class="button styleDon">J\'adhère</a><a href="https://www.helloasso.com/associations/le-florain/formulaires/1/widget" class="button styleDon">Je fais un don</a></footer>');


        /* au scroll dans la fenetre */
			  $(window).scroll(function(){

				var p = $( "section:last" );
        var offset = p.offset();
	  		var posScroll = $(document).scrollTop();

				if(
					offset.top + 500 > posScroll &&
					offset.top - 500 < posScroll
				) {
		        $('.crowdf').fadeOut(600);
	        	$('.phone').fadeOut(600);
			      return;
				}

        $('.crowdf').removeClass( "crowdf_right" ).addClass( "crowdf_left" );

/*
				if( posScroll < $('body').height() ) {
				    $('.crowdf').removeClass( "crowdf_right" ).addClass( "crowdf_left" );
				} else {
				    $('.crowdf').removeClass( "crowdf_left" ).addClass( "crowdf_right" );
				}
  */      

				if( $('body').width() > 737 )  {
	        	$('.crowdf').fadeIn(600);
		        $('.phone').fadeOut(600);
		        $('.nophone').fadeIn(600);
					  $('.ban_nophone').width( $('.box').width() * .7 );
				}
				else {
		        $('.crowdf').fadeOut(600);
	        	$('.phone').fadeIn(600);
					  $('.ban_tel').width( $('body').width() );
	        	$('.nophone').fadeOut(600);
				}
        });
        
		});

		</script>
    <!--
     <script>
        var Images = new Array('images/accueil.png', 'images/cerclevertueux.png');
        var Pointeur = 0;
        window.setInterval('ImageSlider()', 5000 );
        function ImageSlider(){
         $('.slide').attr( 'src', Images[Pointeur % Images.length ] );
         Pointeur++;
        }
    </script>
    -->
  </body>
</html>
