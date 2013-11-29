<?php
/**
 *Created by Konstantin Kolodnitsky
 * Date: 25.11.13
 * Time: 14:57
 */
class page_index extends Page{
    function init(){
        parent::init();
        $this->add('LoremIpsum');
    }
}