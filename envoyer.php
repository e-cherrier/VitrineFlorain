<?php
 
// Get values from the form
$name=trim($_POST['name']);
$prenom=trim($_POST['prenom']);
$phone=$_POST['phone'];
$email=$_POST['email'];
$calcul=$_POST['calcul'];
$monmessage= trim($_POST['message']);

if( substr_count( $name, $prenom ) != 0 || substr_count( $prenom, $name ) ) {
  print "<script>document.location.href='erreur.php?message=Filtre Anti Spam: noms identiques</p>&page=index.php#contact';</script>";
} else
if( strlen( $monmessage ) == 0 )
{
  print "<script>document.location.href='erreur.php?message=Desole! Veuillez saisir votre message&page=index.php#contact';</script>";
} else {
if( substr_count( $monmessage, "http") != 0 ) {
  print "<script>document.location.href='erreur.php?message=Filtre Anti Spam: Votre message ne doit pas contenir de lien, merci.<p>veuillez supprimer l'entete http.</p>&page=index.php#contact';</script>";

} else
if( $calcul != "2" && $calcul != "deux") {
  print "<script>document.location.href='erreur.php?message=Filtre Anti Robots:<p>Veuillez saisir le resultat de un plus un. SVP.</p>&page=index.php#contact';</script>";
} else {

  $to = "contact@florain.fr";
  $subject = "[Formulaire monnaie citoyenne] demande de renseignements";
  $message = " Name: " . $name . "\r\n Prenom: " . $prenom . "\r\n Telephone: " . $phone . "\r\n Email: " . $email . "\r\n message: " . $monmessage;
  
  
  $from = "florain.fr";
  $headers = "From:" . $from . "\r\n";
  $headers .= "Content-type: text/plain; charset=UTF-8" . "\r\n"; 
  
  if(@mail($to,$subject,$message,$headers))
  {
    print "<script>document.location.href='merci.php';</script>";
  } else {
    print "<script>document.location.href='erreur.php?message=Veuillez Remplir les Champs Requis.&page=index.php#contact';</script>";
  }
 
} }
 
?>
