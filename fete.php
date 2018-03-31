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
    <title>Le Florain: C'est la Fete de Lancement!</title>
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

      <section id="fete" class="main style2 right fullscreen">
        <div class="content box style1">
          <header>
	  <h2>Samedi 7 octobre 2017 14h-24h</h2>
	  <h2>Vous etes venus faire vos premiers échanges en Florains!</h2>
           <p>HÔTEL DE VILLE DE NANCY - SALLE CHEPFER</p>
           <p>Entrée libre</p>
          </header>
	  <p> Comptoir de change et point d'information<br/><span style="align:center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#restauration">Restauration</a> en partenariat avec "La Cantoche"</span><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Buvette<br/><br/>
	      14h-19h: Marché varié (bien-être, habillement, alimentation...) et animations<br/>
              19h-24h: Soirée festive et concerts, en partenariat avec Nancy Curieux
          </p>
      <footer>
	<ul class="style2">
	 <li>  <a href="#programme" class="button">Concerts/Animations</a></li>
	 <li>  <a href="#alimentation" class="button">Le marché</a></li>
	 <li>  <a href="#comment" class="button">Comment ca marche?</a></li>
	</ul>
      </footer>
        </div>
        <footer>
          <a href="#comment" class="button style2 down anchored">More</a>
        </footer>
        </div>
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
 Cette monnaie n'est utilisable que dans le réseau des entreprises et associations adhérentes au Florain.</p>
          <h3>Oui, mais à quoi ça sert?</h3>
          <p>A chaque fois que je dépense un Florain, je consomme auprès d'un acteur local qui partage des valeurs de solidarité et de respect des êtres humains et de leur environnement.
<br/>
Je contribue  à renforcer l'économie locale, puisque ces acteurs sont également incités à re-dépenser dans le réseau les Florains qu'ils recoivent.
<br/>
Je favorise ainsi la consommation en circuits-courts auprès d'acteurs engagés.</p>
          <h3>Je me lance le jour de la Fete!</h3>
          <h4>J'adhère à l'association</h4>
          <p>
Coût de l'adhésion annuelle: Adultes: entre 5 et 50 euros. Mineurs de moins de 16 ans: entre 1 et 50 euros. Le montant est au libre choix de chaque personne.
<br/>
Les adhésions prises après le 1er octobre d'une année sont également valables pour l'année suivante.</p>
          <h4>Je convertis mes euros en Florains</h4>
          <p>contre des coupures de 1, 2, 5, 10 et 20 Florains. Les euros ainsi échangés sont placés sur un compte épargne solidaire.  </p>
          <h4>Je fais circuler mes Florains</h4>
<p>Je profite de ce marché festif pour étreiner les échanges en Florains!</p>
            
        </div>
        <a href="#alimentation" class="button style3 down anchored">Next</a>
      </section>

<section id="alimentation" class="carousel style3 primary">
          <header>
            <h2>Marché</h2>
            <p>Alimentation</p>
          </header>
	<div class="reel">

		<?php
                   $xmlDoc = new DOMDocument();
                   $xmlDoc->load("acteurs-cat.xml");

                   $x = $xmlDoc->documentElement;
		   $acteurs = $x->getElementsByTagName( "acteur" );
		   $nb = $acteurs->length;
                   $indexes = range(0, $nb-1);
		   shuffle($indexes);
                   for($pos=0; $pos<$nb; $pos++) {
		      if( ! $acteurs[$indexes[$pos]]->hasAttribute( "fete" ) ) {
	                continue;
	              }
		      if( $acteurs[$indexes[$pos]]->getAttribute( "type" ) != "Alimentation" ) {
	                  continue;
	              }

		      print "<article>\n";

		      print "<a href='". $acteurs[$indexes[$pos]]->getAttribute( "siteweb" ) . "' class='image featured'>\n";
	              print "  <img src='images/acteurs/" . $acteurs[$indexes[$pos]]->getAttribute( "image" ) . "'/></a>\n"; 
		      print "  <header>\n";
                      print "    <h4>" . $acteurs[$indexes[$pos]]->getAttribute( "titre" ) . "</h4>\n"; 
		      print "  </header>\n";
                      print "  <p>" . $acteurs[$indexes[$pos]]->getAttribute( "bref" ) . "</p>\n"; 

		      print "</article>\n";
                   }
                ?> 
        </div>
        <a href="#autre" class="button style2 down anchored">Next</a>
</section>

<section id="autre" class="carousel style3 primary">
          <header>
            <h2>Autres exposants</h2>
          </header>
	<div class="reel">

		<?php
                   $xmlDoc = new DOMDocument();
                   $xmlDoc->load("acteurs-cat.xml");

                   $x = $xmlDoc->documentElement;
		   $acteurs = $x->getElementsByTagName( "acteur" );
		   $nb = $acteurs->length;
                   $indexes = range(0, $nb-1);
		   shuffle($indexes);
                   for($pos=0; $pos<$nb; $pos++) {
		      if( ! $acteurs[$indexes[$pos]]->hasAttribute( "fete" ) ) {
	                continue;
	              }
		      if( $acteurs[$indexes[$pos]]->getAttribute( "type" ) == "Alimentation" ) {
	                  continue;
	              }
		      print "<article>\n";

		      print "<a href='". $acteurs[$indexes[$pos]]->getAttribute( "siteweb" ) . "' class='image featured'>\n";
		      print "<span class='mention'>" . $acteurs[$indexes[$pos]]->getAttribute( "type" ) . "</span>\n";
	              print "  <img src='images/acteurs/" . $acteurs[$indexes[$pos]]->getAttribute( "image" ) . "'/></a>\n"; 
		      print "  <header>\n";
                      print "    <h4>" . $acteurs[$indexes[$pos]]->getAttribute( "titre" ) . "</h4>\n"; 
		      print "  </header>\n";
                      print "  <p>" . $acteurs[$indexes[$pos]]->getAttribute( "bref" ) . "</p>\n"; 

		      print "</article>\n";
                   }
                ?> 
        </div>
          <footer>
               <a  class="button style2 " href="./acteurs.php"><h1>Voir la liste actuelle des professionnels agréés</h1><a>
          </footer>
        <a href="#programme" class="button style2 down anchored">Next</a>
</section>

<section id="programme" class="carousel style3 primary">
          <header>
            <h2>Animations et Concerts</h2>
          <p>En partenariat avec Nancy Curieux</p>
          </header>
	<div class="reel">

		<?php
                   $xmlDoc = new DOMDocument();
                   $xmlDoc->load("animations.xml");

                   $x = $xmlDoc->documentElement;
		   $acteurs = $x->getElementsByTagName( "acteur" );
		   $nb = $acteurs->length;
                   $indexes = range(0, $nb-1);
                   for($pos=0; $pos<$nb; $pos++) {
		      if( $acteurs[$indexes[$pos]]->hasAttribute( "attente" ) ) {
	                continue;
	              }
		      print "<article>\n";

		      print "<a href='". $acteurs[$indexes[$pos]]->getAttribute( "siteweb" ) . "' class='image featured'>\n";
		      print "<span class='mention'>" . $acteurs[$indexes[$pos]]->getAttribute( "type" ) . "</span>\n";
	              print "  <img src='" . $acteurs[$indexes[$pos]]->getAttribute( "image" ) . "'/></a>\n"; 
		      print "  <header>\n";
                      print "    <h4>" . $acteurs[$indexes[$pos]]->getAttribute( "horaire" ) . "</h4>\n"; 
                      print "    <h3>" . $acteurs[$indexes[$pos]]->getAttribute( "nom" ) . "</h3>\n"; 
		      print "  </header>\n";
                      print "  <p>" . $acteurs[$indexes[$pos]]->getAttribute( "desc" ) . "</p>\n"; 

		      print "</article>\n";
                   }
                ?> 
        </div>
        <a href="#restauration" class="button style2 down anchored">Next</a>
</section>

      <section id="restauration" class="main style1 left red fullscreen">
        <div class="content box style1">
          <header>
            <h2>Restauration</h2>
          </header>
          
          <h3>En partenariat avec la Cantoche</h3><br>
          <p class="onecolumn">Buvette avec des produits locaux et BIO<br>Crêpes confectionnées sur place</p>
          <p class="onecolumn">A partir de 19h - repas complet (entrée, plat et dessert) confectionné par la Cantoche à partir de produits issus du réseau d'acteurs du Florain.<br>
            
        </div>
        <a href="#contact" class="button style2 down anchored">Next</a>
      </section>


      <section id="contact" class="main style3 dark secondary">
        <div class="content container">
          <header>
            <h2>Nous contacter.</h2>
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
                  <div class="12u"><textarea name="message" placeholder="Message" rows="6"></textarea></div>
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
            
            <li><a target="_blank" href="https://www.facebook.com/LeFlorain" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
            
            <li><a target="_blank" href="https://twitter.com/LeFlorain" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
        
          
          </ul>

        <!-- Menu -->
          <ul class="menu">
            <li>&copy; Le Florain</li>       </ul>

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
<!--
		<script type="text/javascript"> 

	 		/* une fois le document chargÃ©, executer la fonction suivante */
	 		$(document).ready(function() {  

	 			/* On crÃ©e l'Ã©lÃ©ment html Ã  la fin de la page */
				$('body').append('<a class="crowdf nav fa-angle-up nav scrollTo"> <p><b><font size="+2">Assemblée générale</font></b><br/> <font size="+1"><b> le 3 septembre 2017</b><br/> 15h à la MJC des 3 maisons<br> Nouveaux statuts,<br> projet de gouvernance...<br> Soyons nombreux,<br> parlez en autour de vous.<br> <b><font size="+2">Fête de lancement</font><br> le 7 Octobre 2017</b><br> 14h - minuit<br> <i><font size="-1">Le lieu sera communiqué<br> ultérieurement</font></i><br> le Florain sera émis,<br> de nombreux partenaires<br> seront présents!</p> </a>');

				$('body').append('<a class="phone ban_tel"> <b> <font size="+1"> <font size="+2"> Assemblée générale </font> le 3 septembre 2017 <br/> <font size="+2"> Fête de lancement </font> le 7 Octobre 2017 </font> </b> </a>');

	     		/* au scroll dans la fenÃªtre */
			$(window).scroll(function(){  

				var p = $( "article:last" );
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

				if( posScroll < $('body').height() ) {
				        $('.crowdf').removeClass( "crowdf_right" ).addClass( "crowdf_left" );
				} else {
				        $('.crowdf').removeClass( "crowdf_left" ).addClass( "crowdf_right" );
				}

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
-->

  </body>
</html>
