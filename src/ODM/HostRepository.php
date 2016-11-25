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

use Rampage\Nexus\Ansible\Repository\HostRepositoryInterface;
use Rampage\Nexus\Ansible\Entities\Host;
use Rampage\Nexus\Ansible\Entities\Group;

use Rampage\Nexus\ODM\Repository\AbstractRepository;

use Rampage\Nexus\Repository\DeployTargetRepositoryInterface;
use Rampage\Nexus\Repository\NodeRepositoryInterface;

use Rampage\Nexus\Deployment\NodeInterface;
use Rampage\Nexus\Exception\LogicException;
use Rampage\Nexus\Entities\Node;


/**
 * Host repo implementation
 */
class HostRepository extends AbstractRepository implements HostRepositoryInterface
{
    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\ODM\Repository\AbstractRepository::getEntityClass()
     */
    protected function getEntityClass()
    {
        return Host::class;
    }

    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\Ansible\Repository\HostRepositoryInterface::findByGroup()
     */
    public function findByGroup(Group $group)
    {
        return $this->getEntityRepository()
            ->createQueryBuilder()
            ->field('group')->references($group)
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\Ansible\Repository\HostRepositoryInterface::findDeployableHosts()
     */
    public function findDeployableHosts()
    {
        return new \CallbackFilterIterator($this->findAll(), function(Host $host) {
            return ($host->getNode() !== null);
        });
    }

    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\Ansible\Repository\HostRepositoryInterface::isNodeAttached()
     */
    public function isNodeAttached(Node $node)
    {
        $qb = $this->getEntityRepository()->createQueryBuilder();
        $query = $qb->hydrate(false)
            ->field('node')
            ->references($node)
            ->getQuery();

        return ($query->count());
    }

    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\Ansible\Repository\HostRepositoryInterface::remove()
     */
    public function remove(Host $host)
    {
        $this->removeAndFlush($host);
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\Ansible\Repository\HostRepositoryInterface::save()
     */
    public function save(Host $host)
    {
        $this->persistAndFlush($host);
        return $this;
    }
}
