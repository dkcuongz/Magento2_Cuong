<?php

namespace AHT\Blog\Controller\Adminhtml\Post;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \AHT\Blog\Controller\Adminhtml\Post
{
    protected $_postFactory;
    protected $_sessionFactory;
    protected $_filesystemFactory;
    protected $_cache;

    public function __construct(\Magento\Backend\App\Action\Context $context, 
    \Magento\Framework\Registry $coreRegistry, 
    \AHT\Blog\Model\PostRepository $postRepository, 
    \AHT\Blog\Model\PostFactory $postFactory, 
    \Magento\Backend\Model\Session $sessionFactory, 
    \Magento\Framework\Filesystem $filesystemFactory,
    \Magento\PageCache\Model\Cache\Type $cache)
    {
        $this->_coreRegistry = $coreRegistry;
        $this->_postRepository = $postRepository;
        $this->_postFactory = $postFactory;
        $this->_sessionFactory = $sessionFactory;
        $this->_filesystemFactory = $filesystemFactory;
        $this->_cache = $cache;
        parent::__construct($context);
    }
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('post_id');
            $model = $this->_postFactory->create()->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This item no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                $uploader = $this->_objectManager->create(
                    'Magento\MediaStorage\Model\File\Uploader',
                    ['fileId' => 'image']
                );
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'svg', 'JPG', 'JPEG', 'GIF', 'PNG', 'SVG']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setAllowCreateFolders(true);
                $uploader->setFilesDispersion(true);
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                if ($uploader->checkAllowedExtension($ext)) {
                    $path = $this->_filesystemFactory->getDirectoryRead(DirectoryList::MEDIA)
                        ->getAbsolutePath('blog/images/');
                    $uploader->save($path);
                    $fileName = $uploader->getUploadedFileName();
                    if ($fileName) {
                        $data['image'] = 'blog/images' . $fileName;
                    }
                } else {
                    $this->messageManager->addError(__('Disallowed file type.'));
                    return $this->redirectToEdit($model, $data);
                }
            } else {
                if (isset($data['image']['delete']) && $data['image']['delete'] == 1) {
                    $data['image'] = '';
                } else {
                    unset($data['image']);
                }
            }
            $model->setData($data);
            try {
                $this->_postRepository->save($model);
                $this->messageManager->addSuccess(__('You saved the item.'));
                $this->_sessionFactory->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['post_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_sessionFactory->setFormData($data);
                return $resultRedirect->setPath('*/*/edit', ['post_id' => $this->getRequest()->getParam('post_id')]);
            }
        }
        return $this->cache->clean(\Zend_Cache::CLEANING_MODE_ALL, array('config','layout','block_html','collections','reflection','db_ddl','compiled_config','eav','config_integration','config_integration_api','full_page','translate','config_webservice','vertex'));
        return $resultRedirect->setPath('*/*/');
    }
}
