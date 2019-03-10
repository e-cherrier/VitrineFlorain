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
<body onload="timer" id="top">
<?php
$header = new Header();
$header->display();
?>
                      
<?php

$redirection = 'http://www.monnaielocalenancy.fr/slogan_clos.php'; //  redirection quand le compteur arrive à 0
/*******************************************************************************
    * calcul des secondes
    ***************************************************************************/
    
    $dateSrc = '2019-02-22 21:00 GMT';
    $dateTime = strtotime($dateSrc); 

$secondes = $dateTime - time();
?>

<script type="text/javascript">
var temps = <?php echo $secondes;?>;
var timer =setInterval('CompteaRebour()',1000);
function CompteaRebour(){
  temps-- ;
  j = parseInt(temps/86400) ;
  h = parseInt(temps%86400/3600) ;
  m = parseInt((temps%86400)%3600/60) ;
  s = parseInt((temps%86400)%3600%60) ;
  document.getElementById('minutes').innerHTML= j + ' j,  ' +
                                                (h<10 ? "0"+h : h) + ' h,  ' +
                                                (m<10 ? "0"+m : m) + ' mn, ' +
                                                (s<10 ? "0"+s : s) + ' s ';
if ((j == 0 && s <= 0 && m ==0 && h ==0)) {
   clearInterval(timer);
   url = "<?php echo $redirection;?>"
   Redirection(url)
}
}
function Redirection(url) {
setTimeout("window.location=url", 500)
}
</script>



      <section id="contact" class="main style3 dark secondary">
        <div class="content container">
          <header>
            <h2>Votre slogan pour le Florain</h2>
            <p >Quelques mots pour montrer notre identité, une phrase rythmée pour définir les valeurs que font circuler nos billets!<br/>
            C'est un appel à votre sens créatif et à votre imagination. Nous sommes déjà pressés de vous lire!</p>
            <h1 class="red"><div>Lors de notre Assemblée Générale du 03 Mars,</div>un vote sera effectué parmi une sélection de vos nombreuses propositions!</h1>
          </header>
          <div class="box container 75%">
<h1>Le sondage est ouvert encore
<div id="minutes" style="font-size: 36px;"></div></h1><br/>

          <!-- Contact Form -->
              <form method="post" action="envoyer_slogan.php">
                <div class="row 50%">
                  <div class="12u"><textarea name="message" placeholder="votre slogan (Proposez en plusieurs en remplissant autant de formulaires)" rows="2"></textarea></div>
                </div>
                <div class="row 50%">
                  <div class="6u 12u(mobile)"><h4>Veuillez entrer le résultat de un plus un:</h4></div>
                  <div class="6u 12u(mobile)"><input type="text" name="calcul" id="calcul" placeholder="Un robot ne sais pas !"></div>
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
      <script src="assets/js/main.js"></script>
      <script src="assets/js/main2.js"></script>

  </body>
</html>
                     

                      
                      
                      
                      
                      
                      
                      
                      

                            