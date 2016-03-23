<?php
include("../ClassCRUDetail.php");
include("./ClassUtenti.php");
include("./ClassMessaggi.php");
include("./ClassVilli.php");

$dati_utente['id'] = 1;
$dati_utente['id_proprietario'] = 123;
$dati_utente['nome'] = "wue";
$dati_utente['a'] = "wue";
$dati_utente['v'] = "wue";

$utenti = new Villi();
$utenti->attributes = $dati_utente;
$utenti->update($utenti);

?>