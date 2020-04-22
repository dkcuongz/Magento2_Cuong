<?php
namespace Blog\Post\Block\Adminhtml\Contact\Edit;
use Magento\Search\Controller\RegistryConstants;
class GenericButton
{
    protected $urlBuilder;
    protected $registry;
    public function __construct(
       \Magento\Backend\Block\Widget\Context $context,
       \Magento\Framework\Registry $registry
    )
    {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->registry = $registry;
    }
    public function getId()
    {
        $contact = $this->registry->registry('contact');
        return $contact ? $contact->getId() : null;
    }
    public function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}