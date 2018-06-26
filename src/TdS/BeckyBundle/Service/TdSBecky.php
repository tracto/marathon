<?php

namespace TdS\BeckyBundle\Service;

use TdS\BeckyBundle\Entity\Becky;
use Symfony\Component\HttpFoundation\Request;

class TdSBecky {

  public function getInfosBecky($id)
  {
    $em = $this->getDoctrine()->getManager();
      
    $becky = $em
        ->getRepository('TdSBeckyBundle:Becky')
        ->findOneBy(array('id' => $id));

    return $becky;
  }
}