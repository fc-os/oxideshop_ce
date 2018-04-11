<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopCommunity\Internal\Common\DataMapper;

/**
 * @internal
 */
interface IdentifiableObjectMapperInterface extends ObjectMapperInterface
{
    /**
     * @param object $object
     *
     * @return array
     */
    public function getPrimaryKey($object);
}
