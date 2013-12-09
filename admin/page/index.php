<?php
/**
 *Created by Konstantin Kolodnitsky
 * Date: 25.11.13
 * Time: 14:57
 */
class page_index extends Page{
    function init(){
        parent::init();


        $m = $this->add('Model');
        $m->setSource('Array',array('hello','world','blah'));

        $m->addField('name');

        $this->add('CRUD')->setModel($m);

    }
}
