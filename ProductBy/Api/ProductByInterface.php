<?php

namespace MyModules\ProductBy\Api;

interface ProductByInterface
{
 /**
  * GET product identified by its URL key
  *
  * @api
  * @param string $urlKey
  * @return \Magento\Catalog\Api\Data\ProductInterface
  * @throws \Magento\Framework\Exception\NoSuchEntityException
  */
 public function getProductByUrl($urlKey);

 /**
  * GET product identified by its id
  *
  * @api
  * @param string $id
  * @return \Magento\Catalog\Api\Data\ProductInterface
  * @throws \Magento\Framework\Exception\NoSuchEntityException
  */
 public function getProductById($id);

 /**
  * GET product identified by its URL key
  *
  * @api
  * @param string $urlKey
  * @return \Magento\Catalog\Api\Data\ProductInterface
  * @throws \Magento\Framework\Exception\NoSuchEntityException
  */
  public function skuProductByUrl($urlKey);

   /**
  * GET product identified by its URL key
  *
  * @api
  * @param string $urlKey
  * @return \Magento\Catalog\Api\Data\ProductInterface
  * @throws \Magento\Framework\Exception\NoSuchEntityException
  */
  public function getcategorybyurl($urlKey);
  
  /**
  * GET best seller products 
  *
  * @api
  * @param string $period
  * @return \Magento\Catalog\Api\Data\ProductInterface | any
  * @throws \Magento\Framework\Exception\NoSuchEntityException
  */
  public function getBestSeller();
    /**
  * GET best seller products 
  *
  * @api
  * @param int $id
  * @return \Magento\Catalog\Api\Data\ProductInterface | any
  * @throws \Magento\Framework\Exception\NoSuchEntityException
  */
  public function getAlsoViewed($id);

    /**
  * image products 
  *
  * @api
  * @param string $sku
  * @return \Magento\Catalog\Api\Data\ProductInterface | any
  * @throws \Magento\Framework\Exception\NoSuchEntityException
  */
  public function uploadProductImage($sku);
}

