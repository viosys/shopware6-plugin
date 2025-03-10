<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Shopware6;

use Omikron\FactFinder\Shopware6\Export\Field\FieldInterface;
use Shopware\Core\Framework\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OmikronFactFinder extends Plugin
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->registerForAutoconfiguration(FieldInterface::class)->addTag('factfinder.export.field');
    }
}
