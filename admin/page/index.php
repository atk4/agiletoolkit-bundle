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

        $this->add('View_Box')->add('LoremIpsum')->setLength(1,30);;

        $menu = $this->add('View_Button')->set('Button with jQuery MENU')
            ->addMenu();
            $menu->addMenuItem('one');
            $menu->addMenuItem('one');
            $menu->addMenuItem('one');

        $menu = $this->add('View_Button')->set('Button with jQuery MENU')
            ->addPopover()
            ->add('HelloWorld');
    }
}
