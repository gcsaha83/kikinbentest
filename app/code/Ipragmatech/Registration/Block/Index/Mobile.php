<?php
/**
 * Copyright © 2015 Ipragmatech . All rights reserved.
 */
namespace Ipragmatech\Registration\Block\Index;

use Ipragmatech\Registration\Block\BaseBlock;

class Mobile extends BaseBlock
{
    // public $hello='Hello World';
    public function getTitle()
    {
        return "Ipragmaech Block";
    }
   public function getPostData()
   {      
       return $postData =  $this->getRequest()->getParams('mobile');
   }
}
