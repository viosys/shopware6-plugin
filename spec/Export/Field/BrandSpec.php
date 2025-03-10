<?php

namespace spec\Omikron\FactFinder\Shopware6\Export\Field;

use Omikron\FactFinder\Shopware6\Export\Field\FieldInterface;
use PhpSpec\ObjectBehavior;
use Shopware\Core\Content\Product\Aggregate\ProductManufacturer\ProductManufacturerEntity as Manufacturer;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity as Product;

class BrandSpec extends ObjectBehavior
{
    function it_is_a_field()
    {
        $this->shouldHaveType(FieldInterface::class);
    }

    function it_has_a_name()
    {
        $this->getName()->shouldReturn('Brand');
    }

    function it_does_not_fail_if_the_brand_is_not_present(Product $product, Manufacturer $manufacturer)
    {
        $this->shouldNotThrow()->during('getValue', [$product]);
        $this->getValue($product)->shouldReturn('');

        $product->getManufacturer()->willReturn($manufacturer);
        $this->shouldNotThrow()->during('getValue', [$product]);
        $this->getValue($product)->shouldReturn('');

        $manufacturer->getName()->willReturn('ACME Inc.');
        $this->shouldNotThrow()->during('getValue', [$product]);
        $this->getValue($product)->shouldReturn('ACME Inc.');
    }

    function it_gets_the_value_from_the_manufacturer(Product $product, Manufacturer $manufacturer)
    {
        $product->getManufacturer()->willReturn($manufacturer);
        $manufacturer->getName()->willReturn('FACT-Finder');
        $this->getValue($product)->shouldReturn('FACT-Finder');
    }
}
