<?php
/**
 * Copyright (c) 2016 Axel Helmert
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Axel Helmert
 * @copyright Copyright (c) 2016 Axel Helmert
 * @license   http://www.gnu.org/licenses/gpl-3.0.txt GNU General Public License
 */

namespace Rampage\Nexus\Ansible\ODM;

use Doctrine\Common\EventSubscriber as EventSubscriberInterface;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events;
use Rampage\Nexus\Ansible\Entities\Host;
use Rampage\Nexus\Entities\Node;

/**
 * Implements an event subscriber
 */
class EventSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     * @see \Doctrine\Common\EventSubscriber::getSubscribedEvents()
     */
    public function getSubscribedEvents()
    {
        return [
            Event::postLoad
        ];
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postLoad(LifecycleEventArgs $event)
    {
        $object = $event->getObject();
        $objectManager = $event->getDocumentManager();

        if (!$object instanceof Host) {
            return;
        }

        if (!$object->getNode() && $object->getDefaultNodeType()) {
            $node = new Node($object->getDefaultNodeType());
            $node->setName($object->getName());
            $node->setUrl(sprintf('https://%s:20080/', $object->getName()));
            $object->setNode($node);
            $objectManager->flush([$node, $object]);
        }
    }

}