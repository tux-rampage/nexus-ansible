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

namespace Rampage\Nexus\Ansible;

use Rampage\Nexus\Ansible\Entities\Host;
use Rampage\Nexus\Entities\Node;
use Rampage\Nexus\Repository\NodeRepositoryInterface;
use Rampage\Nexus\Ansible\Repository\HostRepositoryInterface;
use Rampage\Nexus\Exception\RuntimeException;

/**
 * Implements the node linking strategy
 */
class LinkNodeStrategy implements LinkNodeStrategyInterface
{
    /**
     * @var NodeRepositoryInterface
     */
    private $nodeRepository;

    /**
     * @var HostRepositoryInterface
     */
    private $hostRepository;

    public function __construct(NodeRepositoryInterface $nodeRepository, HostRepositoryInterface $hostRepository)
    {
        $this->nodeRepository = $nodeRepository;
        $this->hostRepository = $hostRepository;
    }

    /**
     * @param Host $host
     */
    public function ensureNodeLink(Host $host)
    {
        if ($host->getNode() || !$host->getDefaultNodeType()) {
            return;
        }

        $url = sprintf('https://%s:20080/', $host->getName());
        $node = $this->nodeRepository->findByUrl($url);

        if (!$node instanceof Node) {
            $node = new Node($host->getDefaultNodeType());
            $node->setName($host->getName());
            $node->setUrl($url);
            $this->nodeRepository->save($node);
        } else if ($this->hostRepository->isNodeAttached($node)) {
            throw new RuntimeException('Ambiguous configuration. This host should have a node (' . $node->getName() . ') which is already attached.');
        }

        $host->setNode($node);
    }
}
