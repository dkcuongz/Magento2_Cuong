<?php

namespace AHT\Blog\Controller\Adminhtml\Post;

class NewAction extends \AHT\Blog\Controller\Adminhtml\Post
{
    public function execute()
    {
        $this->_forward('edit');
    }
}