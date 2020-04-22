<?php
namespace AHT\Blog\Controller\Index;

class Save extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;

	protected $_postFactory;

	protected $_postRepository;

	protected $_coreRegistry;

	protected $resultRedirect;

	protected $urlInterface;

	private $_cacheTypeList;
	private $_cacheFrontendPool;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\AHT\Blog\Model\PostFactory $postFactory,
		\AHT\Blog\Model\PostRepository $postRepository, 
		\Magento\Framework\Registry $coreRegistry,
		\Magento\Framework\Controller\ResultFactory $result, 
		\Magento\Framework\App\Cache\TypeListInterface $cacheTypeList, 
		\Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
		)
	{
		$this->_pageFactory = $pageFactory;
		$this->_postFactory = $postFactory;
		$this->_postRepository = $postRepository;
		$this->_coreRegistry = $coreRegistry;
		$this->resultRedirect = $result;
		$this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;

		return parent::__construct($context);
	}

	public function execute()
	{
		$post = $this->_postFactory->create();
		if (isset($_POST['editbtn'])) {
			$post->setId($_POST['editbtn']);
			$post->setName($_POST['name']);
			$post->setUrlKey($_POST['url']);
			$post->setContent($_POST['content']);
			$post->setStatus($_POST['status']);
			$post->setUpdatedAt(date('Y-m-d H:i:s'));
		}
		elseif (isset($_POST['createbtn'])) {
			$post->setName($_POST['name']);
			$post->setUrlKey($_POST['url']);
			$post->setContent($_POST['content']);
			$post->setStatus($_POST['status']);
			$post->setCreatedAt(date('Y-m-d H:i:s'));
			$post->setUpdatedAt(date('Y-m-d H:i:s'));
		}
        
        $this->_postRepository->save($post);
        
         $types = array('config','layout','block_html','collections','reflection','db_ddl','compiled_config','eav','config_integration','config_integration_api','full_page','translate','config_webservice','vertex');
		foreach ($types as $type) {
		    $this->_cacheTypeList->cleanType($type);
		}
		foreach ($this->_cacheFrontendPool as $cacheFrontend) {
		    $cacheFrontend->getBackend()->clean();
		}

		$resultRedirect = $this->resultRedirectFactory->create();
		$resultRedirect->setPath('blog/index/index');
		return $resultRedirect; 
	}
}