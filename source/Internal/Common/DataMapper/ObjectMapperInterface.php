<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopCommunity\Internal\Common\DataMapper;

/**
 * @internal
 */
interface ObjectMapperInterface
{
    /**
     * @param object $object
     * @param array  $data
     *
     * @return object
     */
    public function map($object, $data);

    /**
     * @param object $object
     *
     * @return array
     */
    public function getData($object);
}
