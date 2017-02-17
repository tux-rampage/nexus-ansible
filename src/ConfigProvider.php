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

use Rampage\Nexus\Repository\NodeRepositoryInterface;
use Rampage\Nexus\Middleware\RestfulServiceMiddleware;

/**
 * Config provider for ansible module
 */
class ConfigProvider
{
    /**
     * @var bool
     */
    private $useDoctrineODM = true;

    /**
     * @param bool $useDoctrineODM
     */
    public function __construct($useDoctrineODM = null)
    {
        $this->useDoctrineODM = ($useDoctrineODM !== null)? $useDoctrineODM : class_exists('Rampage\Nexus\ODM\ConfigProvider');
    }

    /**
     * Provides the configuration
     */
    public function getConfig()
    {
        $config = [
            'ui' => [
                'modules' => [
                    'ansible' => 'nexus.ui.ansible'
                ]
            ],
            'dependencies' => [
                'delegators' => [
                    NodeRepositoryInterface::class => ServiceFactory\NodeRepositoryDelegator::class
                ],
                'aliases' => [
                    InventoryProviderInterface::class => InventoryProvider::class,
                    LinkNodeStrategyInterface::class =>  LinkNodeStrategy::class,
                ],
            ],
            'commands' => [
                'ansible:inventory' => Command\InventoryCommand::class,
            ],
            'rest' => [
                'routes' => [
                    'ansible/hosts' => [
                        'name' => 'ansible/hosts',
                        'path' => '/ansible/hosts[/{id}]',
                        'middleware' => RestfulServiceMiddleware::getMiddlewareServiceName(Rest\HostService::class),
                        'allow_methods' => [ 'GET', 'PUT', 'POST', 'DELETE', 'OPTIONS' ],
                    ],

                    'ansible/groups' => [
                        'name' => 'ansible/groups',
                        'path' => '/ansible/groups[/{id}]',
                        'middleware' => RestfulServiceMiddleware::getMiddlewareServiceName(Rest\GroupsService::class),
                        'allow_methods' => [ 'GET', 'PUT', 'POST', 'DELETE', 'OPTIONS' ],
                    ]
                ]
            ]
        ];

        if ($this->useDoctrineODM) {
            $config['dependencies'] = array_merge_recursive($config['dependencies'], [
                'aliases' => [
                    Repository\GroupRepositoryInterface::class => ODM\GroupRepository::class,
                    Repository\HostRepositoryInterface::class => ODM\HostRepository::class,
                ]
            ]);

            $config['odm']['mappingDrivers'] = [
                __NAMESPACE__ . '\Entities' => ODM\MappingDriver::class,
            ];
        }

        return $config;
    }

    /**
     * @return string[][]
     */
    public function __invoke()
    {
        return $this->getConfig();
    }
}
