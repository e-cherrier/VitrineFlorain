<?php
 
// Get values from the form
$name=$_POST['name'];
$prenom=$_POST['prenom'];
$phone=$_POST['phone'];
$email=$_POST['email'];
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
if( $calcul != "2" ) {
  echo "Filtre Anti Robots:";
  echo "Veuillez saisir le resultat de 'un plus un'. SVP.";
} else {

  $to = "contact@monnaielocalenancy.fr";
  $subject = "[Formulaire monnaie citoyenne] demande de renseignements";
  $message = " Name: " . $name . "\r\n Prenom: " . $prenom . "\r\n Telephone: " . $phone . "\r\n Email: " . $email . "\r\n message: " . $monmessage;
  
  
  $from = "MonnaieLocaleNancy.fr";
  $headers = "From:" . $from . "\r\n";
  $headers .= "Content-type: text/plain; charset=UTF-8" . "\r\n"; 
  
  if(@mail($to,$subject,$message,$headers))
  {
    print "<script>document.location.href='merci.php';</script>";
  } else {
    echo "Erreur! Veuillez Remplir les Champs Requis.";
  }
 
} }
 
?>
