<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Review\Dao;

use OxidEsales\EshopCommunity\Internal\Common\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Internal\Common\DataMapper\IdentifiableObjectMapperInterface;
use OxidEsales\EshopCommunity\Internal\Review\DataObject\ProductRating;
use OxidEsales\EshopCommunity\Internal\Common\Exception\InvalidObjectIdDaoException;

/**
 * Class ProductRatingDao
 * @internal
 * @package OxidEsales\EshopCommunity\Internal\Review\Dao
 */
class ProductRatingDao implements ProductRatingDaoInterface
{
    /**
     * @var QueryBuilderFactoryInterface
     */
    private $queryBuilderFactory;

    /**
     * @var IdentifiableObjectMapperInterface
     */
    private $mapper;

    /**
     * @param QueryBuilderFactoryInterface      $queryBuilderFactory
     * @param IdentifiableObjectMapperInterface $mapper
     */
    public function __construct(
        QueryBuilderFactoryInterface        $queryBuilderFactory,
        IdentifiableObjectMapperInterface   $mapper
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
        $this->mapper = $mapper;
    }

    /**
     * @param ProductRating $productRating
     */
    public function update(ProductRating $productRating)
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder
            ->update('oxarticles')
            ->set('OXRATING', ':OXRATING')
            ->set('OXRATINGCNT', ':OXRATINGCNT')
            ->where('OXID = :OXID')
            ->setParameters([
                'OXRATING'      => $productRating->getRatingAverage(),
                'OXRATINGCNT'   => $productRating->getRatingCount(),
                'OXID'          => $productRating->getProductId(),
            ]);

        $queryBuilder->execute();
    }

    /**
     * @param string $productId
     *
     * @return ProductRating
     */
    public function getProductRatingById($productId)
    {
        $this->validateProductId($productId);

        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder
            ->select([
                'OXID',
                'OXRATING',
                'OXRATINGCNT'
            ])
            ->from('oxarticles')
            ->where('oxid = :productId')
            ->setMaxResults(1)
            ->setParameter('productId', $productId);

        return $this->mapper->map(
            new ProductRating(),
            $queryBuilder->execute()->fetch()
        );
    }

    /**
     * @param string $productId
     *
     * @throws InvalidObjectIdDaoException
     */
    private function validateProductId($productId)
    {
        if (empty($productId) || !is_string($productId)) {
            throw new InvalidObjectIdDaoException();
        }
    }
}
