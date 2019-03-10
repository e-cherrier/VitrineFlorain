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
    <title>Le Florain: Monnaie Locale Citoyenne et Complémentaire à Nancy et environs</title>
    <meta name="description" content="Site web dedié à la création de la monnaie locale solidaire et citoyenne pour Nancy et ses environs. Le Florain est la dénomination désignée par les futurs usagés!"/>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/nav.css" />
    <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
    <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  </head>
  <body id="top">
<?php
$header = new Header();
$header->display();
?>

      <section id="accueil" class="main style2 right dark fullscreen">
        <div class="content box style1">
          <header>
	  <h2>Votre message a bien été envoyé</h2>
           <p>nous allons vous répondre prochainement</p>
          </header>
      <footer>
	<ul class="style2">
	 <li>  <a href="http://beta.monnaielocalenancy.fr/que-puis-je-faire/" class="button">Bénévolat</a></li>
	 <li>  <a href="http://beta.monnaielocalenancy.fr/lettredinformations" class="button">Recevoir les info</a></li>
	</ul>
      </footer>
        </div>
        <footer>
          <a href="index.php" class="button style2 up anchored">Accueil</a>
        </footer>
        </div>
      </section>
    
      <footer id="footer">

        <!-- Icons -->
          <ul class="actions">
            
            <li><a target="_blank" href="https://www.facebook.com/LeFlorain" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
            
            <li><a target="_blank" href="https://twitter.com/LeFlorain" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
        
          
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
