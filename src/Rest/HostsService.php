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

use Rampage\Nexus\Ansible\Entities\Host;
use Rampage\Nexus\Ansible\Repository\HostRepositoryInterface;

use Rampage\Nexus\Exception\Http\BadRequestException;

use Rampage\Nexus\Repository\RestService\GetableTrait;
use Rampage\Nexus\Repository\RestService\PutableTrait;
use Rampage\Nexus\Repository\RestService\PostableTrait;
use Rampage\Nexus\Repository\RestService\DeletableTrait;


/**
 * Implements the service contract for hosts
 */
class HostService
{
    use GetableTrait;
    use PostableTrait;
    use DeletableTrait;
    use PutableTrait {
        putEntity as private doPutEntity;
    }

    /**
     * @var GroupsService
     */
    private $groupsService;

    /**
     * @param HostRepositoryInterface $repository
     */
    public function __construct(HostRepositoryInterface $repository, GroupsService $groupsService)
    {
        $this->repository = $repository;
        $this->groupsService = $groupsService;
    }

    /**
     * @param array $data
     * @return array
     */
    private function prepareData(array $data)
    {
        if (isset($data['groups'])) {
            $mapper = function($groupData) {
                if (is_array($groupData)) {
                    $groupData = isset($groupData['id'])? $groupData['id'] : null;
                }

                if (!is_string($groupData)) {
                    return null;
                }

                return $this->groupsService->get($groupData);
            };

            $data['groups'] = array_filter(array_map($mapper, $data['groups']));
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\Action\AbstractRestApi::createEntity()
     */
    private function createNewEntity(array $data)
    {
        if (!isset($data['id'])) {
            throw new BadRequestException('Missing identifier', BadRequestException::UNPROCESSABLE);
        }

        $host = new Host($data['id']);
        $host->exchangeArray($this->prepareData($data));

        return $host;
    }

    /**
     * @param int|string $id
     * @param array $data
     * @return object|null
     */
    private function putEntity($id, array $data)
    {
        return $this->doPutEntity($id, $this->prepareData($data));
    }
}
