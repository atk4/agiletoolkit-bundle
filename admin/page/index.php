<?php
/**
 *Created by Konstantin Kolodnitsky
 * Date: 25.11.13
 * Time: 14:57
 */
class page_index extends Page {

    function init() {
        parent::init();
        $this->contrl = $this->add('Controller_Bla');
    }

    function initMainPage(){

        $this->contrl->addButton($this);

      $this->add('Text')->set('CSS used: '.$this->api->locateURL('css','style.css'));


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

class Controller_Bla extends AbstractController {
    function init() {
        parent::init();
        $this->i = $this->add('sandbox/Controller_InstallAddon');
    }
    function addButton($view) {
        $self = $this;
        $trace_view = $view->add('View')->set('')->addStyle('font-size','11px');

        $f = $view->add('Form');
        $f->addClass('stacked');
        $f->addField('Line','id','Addon ID');
        $f->addSubmit('Install Addon');
        $f->onSubmit(function($f) use ($self,$trace_view) {
            $js=array();
            $id = trim($f->get('id'));
            if ($id=='') $js[] = $f->js()->atk4_form('fieldError','id',$self->api->_('required'));

            if (count($js)) {
                $f->js(null,$js)->execute();
            }

            try {
                $self->i->installAddon($id);
            } catch (sandbox\Exception_BadAPIRequest $e) {
                $f->js()->atk4_form('fieldError','id',$e->getMessage())->execute();
            } catch (sandbox\Exception_NotFullAPIRespond $e) {
                $f->js()->atk4_form('fieldError','id',$self->api->_('API respond contains not all required data'))->execute();
            }

            $respond = '<h4>Composer trace:</h4>';
            foreach ($self->i->last_composer_trace as $line) {
                $respond = $respond . nl2br($line) . '<br>';
            }

            $f->js(null,'$("#'.$trace_view->name.'").html("'.$respond .'")')->univ()->alert('installed')->execute();
        });
    }
}
