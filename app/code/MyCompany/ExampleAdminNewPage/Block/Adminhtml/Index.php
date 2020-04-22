<?php 
namespace MyCompany\ExampleAdminNewPage\Block\Adminhtml;

class Index extends \Magento\Framework\View\Element\Template{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
    {
        parent::__construct($context);
    }
    public function getBlogInfo(){
        return __('AHT Blog module');
    }
    public function getPosts(){
        $post = $this->_postFactory->create();
        $collection = $post->getCollection();
        return $collection;
    }
}