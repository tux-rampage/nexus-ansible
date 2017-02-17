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

namespace Rampage\Nexus\Ansible\Rest;

use Rampage\Nexus\Ansible\Entities\Group;
use Rampage\Nexus\Ansible\Repository\GroupRepositoryInterface;

use Rampage\Nexus\Exception\Http\BadRequestException;

use Rampage\Nexus\Repository\RestService\GetableTrait;
use Rampage\Nexus\Repository\RestService\PutableTrait;
use Rampage\Nexus\Repository\RestService\PostableTrait;
use Rampage\Nexus\Repository\RestService\DeletableTrait;


/**
 * Implements the service contract for groups
 */
class GroupsService
{
    use GetableTrait;
    use PutableTrait;
    use PostableTrait;
    use DeletableTrait;

    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\Action\AbstractRestApi::__construct()
     */
    public function __construct(GroupRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $data
     * @throws BadRequestException
     * @return \Rampage\Nexus\Ansible\Entities\Group
     */
    private function createNewEntity(array $data)
    {
        if (!isset($data['name'])) {
            throw new BadRequestException('Missing group name', BadRequestException::UNPROCESSABLE);
        }

        $group = new Group($data['name']);
        $group->exchangeArray($data);

        return $group;
    }
}
