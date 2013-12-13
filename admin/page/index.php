<?php
/**
 *Created by Konstantin Kolodnitsky
 * Date: 25.11.13
 * Time: 14:57
 */
class page_index extends Page{
    function initMainPage(){

      $this->add('Text')->set('CSS used: '.$this->api->locateURL('css','style.css'));


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

            $this->add('Button')->set('Dialog')->js('click')->univ()
                ->dialogURL('Are you sure?',$this->api->url('./test'),array('width'=>400,'height'=>500)) ;
    }
    function page_test() {
        $this->add('LoremIpsum');
    }
}
