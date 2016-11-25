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

use Rampage\Nexus\Ansible\Entities\Group;
use Rampage\Nexus\Ansible\Repository\GroupRepositoryInterface;
use Rampage\Nexus\ODM\Repository\AbstractRepository;


/**
 * Implements the group repository
 */
class GroupRepository extends AbstractRepository implements GroupRepositoryInterface
{
    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\ODM\Repository\AbstractRepository::getEntityClass()
     */
    protected function getEntityClass()
    {
        return Group::class;
    }

    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\Ansible\Repository\GroupRepositoryInterface::remove()
     */
    public function remove(Group $group)
    {
        $this->removeAndFlush($group);
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\Ansible\Repository\GroupRepositoryInterface::save()
     */
    public function save(Group $group)
    {
        $this->persistAndFlush($group);
        return $this;
    }
}
