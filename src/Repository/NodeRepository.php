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

namespace Rampage\Nexus\Ansible\Repository;

use Rampage\Nexus\Entities\Node;
use Rampage\Nexus\Entities\DeployTarget;

use Rampage\Nexus\Repository\NodeRepositoryInterface;
use Rampage\Nexus\Ansible\Repository\HostRepositoryInterface;

use Rampage\Nexus\Exception\LogicException;


/**
 * Decorated node repo
 */
class NodeRepository implements NodeRepositoryInterface
{
    /**
     * The original decorated repo
     *
     * @var NodeRepositoryInterface
     */
    private $decorated;

    /**
     * @var HostRepositoryInterface
     */
    private $hostRepository;

    /**
     * @param NodeRepositoryInterface $decorated
     * @param HostRepositoryInterface $hostRepository
     */
    public function __construct(NodeRepositoryInterface $decorated, HostRepositoryInterface $hostRepository)
    {
        $this->decorated = $decorated;
        $this->hostRepository = $hostRepository;
    }

    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\Repository\NodeRepositoryInterface::findByTarget()
     */
    public function findByTarget(DeployTarget $target)
    {
        $this->decorated->findByTarget($target);
    }

    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\Repository\NodeRepositoryInterface::remove()
     */
    public function remove(Node $node)
    {
        if ($this->hostRepository->isNodeAttached($node)) {
            throw new LogicException('Cannot remove attached nodes in an ansible repository individually');
        }

        return $this->decorated->remove($node);
    }

    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\Repository\NodeRepositoryInterface::save()
     */
    public function save(Node $node)
    {
        return $this->decorated->save($node);
    }

    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\Repository\PrototypeProviderInterface::getPrototypeByData()
     */
    public function getPrototypeByData($data)
    {
        return $this->decorated->getPrototypeByData($data);
    }

    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\Repository\RepositoryInterface::findAll()
     */
    public function findAll()
    {
        $this->decorated->findAll();
    }

    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\Repository\NodeRepositoryInterface::findOne($id)
     */
    public function findOne($id)
    {
        return $this->decorated->findOne($id);
    }

    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\Repository\NodeRepositoryInterface::findByUrl()
     */
    public function findByUrl($url)
    {
        return $this->decorated->findByUrl($url);
    }
}
