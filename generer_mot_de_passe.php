<?php
$mot_de_passe="VITEETGOURMANDcompteadministrateur1738*";
$mot_de_passe_hash = password_hash($mot_de_passe,PASSWORD_DEFAULT);
echo $mot_de_passe_hash;
