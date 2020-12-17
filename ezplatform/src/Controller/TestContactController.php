<?php

namespace App\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;

class TestContactController extends Controller
{
    public function fullViewAction(ContentView $view){

        $content = $view->getContent();

        $name = $content->getFieldValue("name");
        $firstname = $content->getFieldValue("firstname");

        $view->addParameters([
           "fullname" =>  $name . " " . $firstname
        ]);

        return $view;

    }

}