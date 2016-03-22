<?php
// src/TdS/PlatformBundle/Beta/BetaHTML.php

namespace TdS\MarathonBundle\Saison;

use TdS\MarathonBundle\Entity\Saison;
use Symfony\Component\HttpFoundation\Response;

class SaisonHTML{
  // Méthode pour ajouter le « bêta » à une réponse
  public function displaySaison(Response $response,Saison $saison)
  {
    $content = $response->getContent();


    // Code à rajouter
    $html = '<h1 class="man"> Saison '.$saison->getTitre().' !</h1>';

    // Insertion du code dans la page, dans le premier <h1>
    $content = preg_replace(
      '#<div class="flex-item-center saison-title">(.*?)</div>#iU',
      '<div class="flex-item-center saison-title">$1'.$html.'</div>',
      $content,
      1
    );

    // Modification du contenu dans la réponse
    $response->setContent($content);

    return $response;
  }
}