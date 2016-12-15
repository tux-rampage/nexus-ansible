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
use Rampage\Nexus\Ansible\Entities\Host;
use Rampage\Nexus\ODM\Mapping\AbstractArrayDriver;
use Rampage\Nexus\Entities\Node;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;

/**
 * Implements the mapping driver
 */
class MappingDriver extends AbstractArrayDriver
{
    /**
     * {@inheritDoc}
     * @see \Rampage\Nexus\ODM\Mapping\AbstractArrayDriver::loadData()
     */
    protected function loadData()
    {
        return [
            Group::class => [
                'fields' => [
                    'id' => $this->identifier(),
                    'name' => $this->field('string'),
                    'label' => $this->field('string'),
                    'deploymentType' => $this->field('string'),
                    'children' => $this->referenceMany(Group::class, null, [
                        'strategy' => ClassMetadataInfo::STORAGE_STRATEGY_ADD_TO_SET
                    ])
                ]
            ],

            Host::class => [
                'fields' => [
                    'id' => $this->identifier(),
                    'name' => $this->field('string'),
                    'node' => $this->referenceOne(Node::class),
                    'groups' => $this->referenceMany(Group::class, null, [
                        'strategy' => ClassMetadataInfo::STORAGE_STRATEGY_ADD_TO_SET,
                    ]),
                ],
                'indexes' => [
                    'uniqName' => [
                        'keys' => ['name' => 'asc'],
                        'unique' => true,
                    ]
                ]
            ],
        ];
    }


}
