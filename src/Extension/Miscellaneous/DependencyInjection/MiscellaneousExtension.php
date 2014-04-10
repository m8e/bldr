<?php

/**
 * This file is part of bldr
 *
 * (c) Aaron Scherer <aequasi@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE
 */

namespace Bldr\Extension\Miscellaneous\DependencyInjection;

use Bldr\DependencyInjection\AbstractExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Aaron Scherer <aequasi@gmail.com>
 */
class MiscellaneousExtension extends AbstractExtension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $container->setDefinition(
            'bldr_miscellaneous.sleep',
            new Definition('Bldr\Extension\Miscellaneous\Call\SleepCall')
        )
            ->addTag('bldr');
        $container->setDefinition(
            'bldr_miscellaneous.service',
            new Definition('Bldr\Extension\Miscellaneous\Call\ServiceCall')
        )
            ->addTag('bldr');
    }
}
