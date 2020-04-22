<?php

namespace AHT\Blog\Controller\Adminhtml\Post;

class Edit extends \AHT\Blog\Controller\Adminhtml\Post
{
    protected $_coreRegistry = null;
    protected $_postRepository;
    protected $_postFactory;
    protected $_sessionFactory;

    public function __construct(\Magento\Backend\App\Action\Context $context, 
    \Magento\Framework\Registry $coreRegistry, 
    \AHT\Blog\Model\PostRepository $postRepository, 
    \AHT\Blog\Model\PostFactory $postFactory, 
    \Magento\Backend\Model\Session $sessionFactory)
    {
        $this->_coreRegistry = $coreRegistry;
        $this->_postRepository = $postRepository;
        $this->_postFactory = $postFactory;
        $this->_sessionFactory = $sessionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_postFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('blog/*/');
                return;
            }
        }

        $data = $this->_sessionFactory->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('blog_post', $model);

        $this->_initAction()->_addBreadcrumb(
            $id ? __('Edit %1', $model->getName()) : __('New Item'),
            $id ? __('Edit %1', $model->getName()) : __('New Item')
        )->_addContent(
            $this->_view->getLayout()->createBlock('AHT\Blog\Block\Adminhtml\Edit')
        );
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Posts'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            $model->getId() ? $model->getName() : __('New Item')
        );
        $this->_view->renderLayout();
    }
}
