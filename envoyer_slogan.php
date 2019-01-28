<?php
 
// Get values from the form
$calcul=$_POST['calcul'];
$monmessage= trim($_POST['message']);

if( strlen( $monmessage ) == 0 )
{
  print "<script>document.location.href='erreur.php?message=Desole! Veuillez saisir votre slogan&page=slogan.php';</script>";
} else {
if( substr_count( $monmessage, "http") != 0 ) {
  print "<script>document.location.href='erreur.php?message=Filtre Anti Spam: Votre message ne doit pas contenir de lien, merci.</p><p>veuillez supprimer l'entete http.&page=slogan.php';</script>";
} else
if( $calcul != "2" && $calcul != "deux" ) {
  print "<script>document.location.href='erreur.php?message=Filtre Anti Robots:</p><p>Veuillez saisir le resultat de un plus un. SVP.&page=slogan.php';</script>";
} else {

  $filename = "les_slogans.html";
  $contents = file_get_contents( $filename );
  file_put_contents( $filename, $contents . "\r\n<li>" . $monmessage . "</li>" );

  $to = "groupe_com@florain.fr";
  $subject = "[Formulaire Florain] Proposition de Slogan";
  $message = "Ma proposition: " . $monmessage;
  
  $from = "florain.fr";
  $headers = "From:" . $from . "\r\n";
  $headers .= "Content-type: text/plain; charset=UTF-8" . "\r\n"; 
  
  if(@mail($to,$subject,$message,$headers))
  {
    print "<script>document.location.href='merci_slogan.php';</script>";
  } else {
    print "<script>document.location.href='erreur.php?message=Veuillez Remplir les Champs Requis.&page=slogan.php';</script>";
  }
 
} }
 
?>
