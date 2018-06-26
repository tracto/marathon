<?php
namespace TdS\CoreBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use TdS\MarathonBundle\Entity\Image;

class CacheImageListener{
	protected $cacheManager;

	public function __construct($cacheManager){
	    $this->cacheManager = $cacheManager;
	}

	public function postUpdate(LifecycleEventArgs $args){
	    $entity = $args->getEntity();

	    if ($entity instanceof Image) {
	        // clear cache of thumbnail
	        $this->cacheManager->remove($entity->getUploadDir());
	    }
	}

	// when delete entity so remove all thumbnails related 
	public function preRemoveUpdate(LifecycleEventArgs $args){
	    $entity = $args->getEntity();

	    if ($entity instanceof Image) {

	        $this->cacheManager->remove($entity->getWebPath());
	    }
	}
}

?>