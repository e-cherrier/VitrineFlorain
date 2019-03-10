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

$message = "";
$page = "";
if(isset($_GET['message'])) $message = $_GET['message'];
if(isset($_GET['page'])) $page = $_GET['page'];

?>

      <section id="accueil" class="main style2 right dark fullscreen">
        <div class="content box style1">
          <header>
	  <h2>Erreur</h2>
           <p><?php if( $message != "" ) { echo( $message); } else { echo("probleme non identifie");} ?></p>
          </header>
        </div>
        <footer>
          <?php if( $page != "" ) { 
             echo( "<a href='". $page . "' class='button style1 up anchored'>Retour</a>");
          } else {
             echo( "<a href='index.php' class='button style1 up anchored'>Accueil</a>");
          }
          ?>
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
