<?php
namespace AHT\Blog\Block;
class Create extends \Magento\Framework\View\Element\Template
{
	private $postFactory;
	private $postRepository;
	public function __construct(\Magento\Framework\View\Element\Template\Context $context, \AHT\Blog\Model\PostFactory $postFactory, \AHT\Blog\Model\PostRepository $postRepository)
	{
		parent::__construct($context);
		$this->postFactory = $postFactory;
		$this->postRepository = $postRepository;
	}
}