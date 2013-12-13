<?php
/**
 *Created by Konstantin Kolodnitsky
 * Date: 25.11.13
 * Time: 14:57
 */
class page_index extends Page{
    function initMainPage(){


        $m = $this->add('Model');
        $m->addField('id')->system(true);
        $m->addField('name');
        $m->setSource('Session');//,array('hello','world','blah'));


        $cr=$this->add('CRUD');
        $cr->setModel($m);
        //$cr->grid->addPaginator(5);

        $this->add('View_Box')->add('LoremIpsum')->setLength(1,30);;

        $menu = $this->add('View_Button')->set('Button with jQuery MENU')
            ->addMenu();
            $menu->addMenuItem('one');
            $menu->addMenuItem('one');
            $menu->addMenuItem('one');

        $menu = $this->add('View_Button')->set('Button with jQuery MENU')
            ->addPopover()
            ->add('Lister')
            ->setModel('Model')
            ->setSource('Array',array('one','two','three','four'));

            $this->add('Button')->set('Dialog')->js('click')->univ()
                ->dialogURL('Are you sure?',$this->api->url('./test'),array('width'=>400,'height'=>500)) ;
    }
    function page_test() {
        $this->add('LoremIpsum');
    }
}
