<?php

namespace AHT\Blog\Block\Adminhtml\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    protected $_wysiwygConfig;
    protected $_systemStore;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    )
    {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('post__form');
        $this->setTitle(__('Information'));
    }

    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('blog_post');

        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data']]
        );

        $fieldset = $form->addFieldset('add_post_form', ['legend' => __('Post')]);

        if ($model->getId()) {
            $fieldset->addField('post_id', 'hidden', ['name' => 'post_id']);
        }

        $fieldset->addField(
            'name',
            'text',
            [
                'label' => __('Name'),
                'name' => 'name',
                'required' => true,
                'value' => $model->getName()
            ]
        );

        $fieldset->addField(
            'url_key',
            'text',
            [
                'label' => __('Url Key'),
                'name' => 'url_key',
                'required' => true,
                'value' => $model->getUrlKey()
            ]
        );

        $fieldset->addField(
            'image',
            'image',
            [
                'label' => __('Image'),
                'name' => 'image',
                'required' => true,
                'value' => $model->getImage()
            ]
        );

        $wysiwygConfig = $this->_wysiwygConfig->getConfig();
        $fieldset->addField(
            'content',
            'editor',
            ['name' => 'content', 'label' => __('Content'), 'title' => __('Content'), 'required' => true, 'config' => $wysiwygConfig]
        );

        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'name' => 'status',
                'required' => false,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}