<?php

namespace CsvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CsvBundle:Default:index.html.twig');
    }
}
