<?php

/**
 * Created by Konstantin Kolodnitsky
 * Date: 25.11.13
 * Time: 14:57
 */
class page_index extends Page {

    public $title='Dashboard';

    function init() {
        parent::init();
        $this->add('View_Box')
            ->setHTML('Welcome to your new Web App Project. Get started by opening '.
                '<b>admin/page/index.php</b> file in your text editor and '.
                '<a href="http://book.agiletoolkit.org/" target="_blank">Reading '.
                'the documentation</a>.');

        $cr=$this->add('CRUD', ['form_class'=>'Tabz']);
        $cr->setModel('MyModel');

        $cr->form->addTab('More Info')->add('LoremIpsum');

    }

}

class Tabz extends Tabs{
    function init(){
        parent::init();

        $this->main_tab = $this->addTab('Main');
        $this->form = $this->main_tab->add('Form');




    }

    function setModel($foo,$bar){
        $this->model = $this->form->setModel($foo,$bar);




        $self = $this;

        $p=$this->add('VirtualPage');
        $this->addTabURL($p->getURL(),'Ajax');

        $p->set(function($p) use($self) {
            $p->add('HelloWorld');
            // NOT WORKING YET
            $p->add('Text')->set('Record ID='.$self->model->id);
        });


        return $this->model;

    }

    function addSubmit($x='OK'){
        return $this->form->addSubmit($x);
    }
    function onSubmit($x){
        return $this->form->onSubmit($x);
    }
}

class Model_MyModel extends Model {
    function init(){
        parent::init();

        $this->addField('name');
        $this->addField('surname');

        $this->setSource('Array',[
            ['id'=>1, 'name'=>'john', 'surname'=>'smith'],
            ['id'=>2, 'name'=>'aoeu', 'surname'=>'poter'],
            ['id'=>3, 'name'=>'aoseuh', 'surname'=>'smaoeuh'],
            ['id'=>4, 'name'=>'nthnthnth', 'surname'=>'sth'],
            ['id'=>5, 'name'=>'nthnth', 'surname'=>'seeee'],
            ['id'=>6, 'name'=>'eehhhhh', 'surname'=>'siiiimith'],

        ]);
    }
}
