<?php
 
// Get values from the form
$calcul=$_POST['calcul'];
$monmessage= trim($_POST['message']);

if( strlen( $monmessage ) == 0 )
{
  echo "Desole! Veuillez saisir votre message.";
} else {
if( substr_count( $monmessage, "http") != 0 ) {
  echo "Filtre Anti Spam: Votre message ne doit pas contenir de lien, merci.";
  echo "veuillez supprimer l'entete http.";
} else
if( $calcul != "2" && $calcul != "deux" ) {
  echo "Filtre Anti Robots:";
  echo "Veuillez saisir le resultat de 'un plus un' en chiffre. SVP.";
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
    echo "Erreur! Veuillez Remplir les Champs Requis.";
  }
 
} }
 
?>
