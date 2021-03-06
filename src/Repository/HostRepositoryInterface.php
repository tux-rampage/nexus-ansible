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

use Rampage\Nexus\Repository\RepositoryInterface;
use Rampage\Nexus\Ansible\Entities\Host;
use Rampage\Nexus\Ansible\Entities\Group;
use Rampage\Nexus\Entities\Node;
use Rampage\Nexus\Deployment\NodeInterface;

interface HostRepositoryInterface extends RepositoryInterface
{
    /**
     * @param Group $group
     * @return Host[]
     */
    public function findByGroup(Group $group);

    /**
     * Finds hosts that can act as deploy nodes
     *
     * @return Host[]
     */
    public function findDeployableHosts();

    /**
     * Check if the given node is attached to any host
     *
     * @param Node $node
     * @return bool
     */
    public function isNodeAttached(NodeInterface $node);

    /**
     * @param Host $host
     */
    public function save(Host $host);

    /**
     * @param Host $host
     */
    public function remove(Host $host);
}
