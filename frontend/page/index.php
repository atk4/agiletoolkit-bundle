<?php
class page_index extends Page
{
    function init()
    {
        parent::init();

        $this->add('Form_Test');
        $this->add('LoremIpsum');

    }
}

class Form_Test extends Form {
    function init() {
        parent::init();
//        $this->add('\\rvadym\\x_tinymce\\Controller_TinyMCE');


        $this->addField('Text','text');
    }
}