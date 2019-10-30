<?php

namespace App\EventListener;

use App\Entity\Affiliates;
use Doctrine\ORM\Event\LifecycleEventArgs;

class AffiliateTokenListener
{
    /**
     * @param LifecycleEventArgs
     */
    public function prePersist(LifecycleEventArgs $args)
//    public function prePersist()
    {
//        $var = 'test';
//        dump($var);
//        die();
        $entity = $args->getEntity();

        if (!$entity instanceof Affiliates) {
            return;
        }

        if (!$entity->getToken()) {
            $entity->setToken(\bin2hex(\random_bytes(10)));
        }
    }


}