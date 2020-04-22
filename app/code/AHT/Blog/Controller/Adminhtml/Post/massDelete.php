<?php

namespace AHT\Blog\Controller\Adminhtml\Post;

class massDelete extends \AHT\Blog\Controller\Adminhtml\Post
{
    protected $_postFactory;

    public function __construct(\Magento\Backend\App\Action\Context $context, 
    \Magento\Framework\Registry $coreRegistry, 
    \AHT\Blog\Model\PostRepository $postRepository, 
    \AHT\Blog\Model\PostFactory $postFactory)
    {
        $this->_coreRegistry = $coreRegistry;
        $this->_postRepository = $postRepository;
        $this->_postFactory = $postFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $ids = $this->getRequest()->getPost('ids');
        if (!is_array($ids)) {
            $this->messageManager->addError(__('Please select item(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $model = $this->_postFactory->create()
                        ->load($id)
                        ->delete();
                }
                $this->messageManager->addSuccess(__('Total of %1 record(s) were successfully deleted.', count($ids)));

            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}