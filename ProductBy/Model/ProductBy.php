<?php

namespace MyModules\ProductBy\Model;
use MyModules\ProductBy\Api\ProductByInterface;

class ProductBy implements ProductByInterface
{

  protected $request;


 /**
  * @var \Magento\Framework\Api\SearchCriteriaBuilder
  */
 protected $searchCriteriaBuilder;

 /**
  * @var \Magento\Catalog\Api\ProductRepositoryInterface
  */
 protected $productRepository;

 /**
  * @var \Magento\Framework\Api\FilterBuilder
  */
 protected $filterBuilder;

 /**
  * @var \Magento\Framework\Api\Search\FilterGroup
  */
 protected $filterGroup;

 protected $resourceConnection;
     /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;


 public function __construct(
   \Magento\Framework\App\RequestInterface $request,
   \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
   \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
   \Magento\Framework\Api\FilterBuilder $filterBuilder,
   \Magento\Framework\Api\Search\FilterGroup $filterGroup,
   \Magento\Framework\App\ResourceConnection $resourceConnection,
   \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory)
 {
   $this->request = $request;
   $this->productRepository = $productRepository;
   $this->searchCriteria = $searchCriteria;
   $this->filterBuilder = $filterBuilder;
   $this->filterGroup = $filterGroup;
   $this->resourceConnection = $resourceConnection;
   $this->productCollectionFactory = $productCollectionFactory;
 }

 /**
  * {@inheritdoc}
  */
 public function getProductByUrl($urlKey)
{
   $this->filterGroup->setFilters([
   $this->filterBuilder->setField('url_key')->setConditionType('eq')
        ->setValue($urlKey)->create()]);
   $this->searchCriteria->setFilterGroups([$this->filterGroup]);
   $products = $this->productRepository->getList($this->searchCriteria);
   if (!$products) {
     return null;
   }
   $items = $products->getItems();
   foreach ($items as $item) {
     return $item;
   }
 }

 /**
  * {@inheritdoc}
  */
 public function getProductById($id)
 {
	return $this->productRepository->getById($id);
 }

 /**
  * {@inheritdoc}
  */
  public function skuProductByUrl($urlKey)
  {
    // $objectManager     = \Magento\Framework\App\ObjectManager::getInstance();
    // $collection        = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
    // $productcollection = $collection->setPageSize(1)->addAttributeToSelect('url_key')
    // ->addAttributeToFilter('url_key', $urlKey);
    // return $productcollection->getData();
    // $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
    // $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
    // $connection = $resource->getConnection();

    // $tableName1 = $this->getTableName('catalog_product_entity');
    // $tableName2 = $this->getTableName('catalog_product_entity_varchar');

    $url =  "'". $urlKey. "'";
    // $query = 'SELECT email FROM ' . $tableName1 'WHERE' ;
    // $query = 'SELECT sku FROM '. $tableName1 . t1 'INNER JOIN' . $tableName2 . t2 ON t1.entity_id = t2.entity_id WHERE t2.value = . $urlKey;
    $query2 = 'SELECT sku FROM catalog_product_entity t1 INNER JOIN `catalog_product_entity_varchar` t2 using(entity_id)  WHERE t2.value ='. $url;
    
    // return $query2;

    /**
    * Execute the query and store the email from table in $results
    */
    $results = $this->resourceConnection->getConnection()->fetchCol($query2);
    // // // echo "<pre>";print_r($results);
    return $results;

  }

  public function getTablename($tableName)
  {
      /* Create Connection */
      $connection  = $this->resourceConnection->getConnection();
      $tableName   = $connection->getTableName($tableName);
      return $tableName;
  }


 /**
  * {@inheritdoc}
  */
  public function getcategorybyurl($urlKey)
  {
    $objectManager     = \Magento\Framework\App\ObjectManager::getInstance();
    $collection        = $objectManager->create('Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');
    $productcollection = $collection->create()->setPageSize(1)->addAttributeToSelect('url_key')
	    ->addAttributeToFilter('url_key', $urlKey);
    return $productcollection->getData();
  }

 /**
  * {@inheritdoc}
  */
  public function getBestSeller(){
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $productCollection = $objectManager->create('Magento\Reports\Model\ResourceModel\Report\Collection\Factory'); 
    $collection = $productCollection->create('Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection'); 


      // $collection->setPeriod("'". $period. "'");
      $collection->setPeriod('year');

      $arr = array(118134, 118135);
       $res=array();
      foreach ($arr as $item) {
          // print_r($item->getData('product_id'));
          // array_push($res, $this->productRepository->getById($item));
          //  return $res;
          print_r($this->productRepository->getById($item));
          }
          
      }

  /**
  * {@inheritdoc}
  */
  public function getAlsoViewed($id){

    $collection = $this->productCollectionFactory->create()
    ->joinTable(
        ['alsoviewed' => 'alsoviewed_relation'],
        'related_product_id=entity_id',
        [
            'alsoviewed_weight'   => 'weight',
            'alsoviewed_position' => 'position',
        ],
        [
            'product_id' => ['in' => $id],
            'status'     => 1
        ],
        'inner'
    )
    ->addAttributeToSort('alsoviewed_position', 'ASC')
    ->addAttributeToSort('alsoviewed_weight', 'DESC');
    $collection->load();
    return $collection;
    
if (count($id) > 1) {
    $collection->addAttributeToFilter('entity_id', ['nin' => $id]);
    // prevent "Item with the same id already exist" error
    $collection->getSelect()->group('e.entity_id');
}

return $collection;
      }
      

  /**
  * {@inheritdoc}
  */
  public function uploadProductImage($sku){
    
    $values = $this->request->getPostValue('types');
    
    $typeValue = json_decode($values,true);
    if(!isset($typeValue) || count($typeValue)== 0)
    {
      $typeValue = ["image"];
    }

    $link = $this->request->getPostValue('link');
    // return $link;
    if(isset($link) && strlen($link) > 0  )
    {
      $pathinfo = pathinfo($link);
      //  return $pathinfo['extension']; 
      $images = file_get_contents($link);
    }
    else
    {
      $images = $this->request->getFiles('images');
    }
    // return $images;
    $objectManager     = \Magento\Framework\App\ObjectManager::getInstance();
  
    // $filesystem        = $objectManager->create('Magento\Framework\Filesystem');
    // return $filesystem;
    // /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDir */
    // $mediaDir = $objectManager->get('Magento\Framework\Filesystem')
                    // ->getDirectoryWrite(DirectoryList::MEDIA);
    // return $mediaDir;
    // // $mediaDir = $filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();
    // $mediapath = $this->_mediaBaseDirectory = rtrim($mediaDir, '/');
    // return $mediapath;

    // return $images['tmp_name'];
    // return $this->filesystem;
    $fileUploaderFactory  = $objectManager->create('Magento\MediaStorage\Model\File\UploaderFactory');

    // $baseDir = Mage::getBaseDir();
    // return $baseDir;
    // if (isset($images['tmp_name']) && strlen($images['tmp_name']) > 0) {
      try {
        if(isset($link) && strlen($link) > 0  )
        {
          file_put_contents( 'pub/media/catalog/product/'.strtok(basename($link),'.').'.'.$pathinfo['extension'], $images ); 
          $productid = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product')->getIdBySku($sku);

          if (!$productid) {
            throw new NoSuchEntityException(__('Requested product doesn\'t exist'));
          }
          $product = $this->productRepository->getById($productid);
          $product->setAttributeSetId($product->getDefaultAttributeSetId());
          
          $product->addImageToMediaGallery('catalog/product/'.strtok(basename($link),'.').'.'.$pathinfo['extension'] ,$typeValue, false, false);
          $product->save();
          unlink('pub/media/catalog/product/'.strtok(basename($link),'.').'.'.$pathinfo['extension']);
          return $product;
        }
        else
        {
          $uploader = $fileUploaderFactory->create(['fileId' => $images]);
          $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
          $uploader->setAllowRenameFiles(true);
          // $lastFolder = substr($images['name'],0,1);
          $path = 'pub/media/catalog/product/';
          // $data['images'] = $images['name'];
          // return $path;
          $result = $uploader->save($path);
          // echo $result['file']; 
          // return $result['file'];

          $productid = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product')->getIdBySku($sku);

          if (!$productid) {
            throw new NoSuchEntityException(__('Requested product doesn\'t exist'));
          }
          $product = $this->productRepository->getById($productid);
          $product->setAttributeSetId($product->getDefaultAttributeSetId());
          
          $product->addImageToMediaGallery('catalog/product/'.$result['file'] ,$typeValue, false, false);
          $product->save();
          unlink('pub/media/catalog/product/'.$result['file']);
          return $product;
        }
      // $product->save();
      // return $product;
      // $this->productRepository->save($product)
      // return $this->productRepository->getById('118116');

      }
      catch (\Exception $e) {
        return $e->getMessage();
      }
  // }
  // else{
  //   return "Cant upload image";
  // }

  }



}
