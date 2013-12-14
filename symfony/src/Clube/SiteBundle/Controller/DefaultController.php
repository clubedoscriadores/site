<?php

namespace Clube\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SiteBundle:Default:index.html.twig');
    }
	
	public function criadoresAction()
    {
        return $this->render('SiteBundle:Default:criadores.html.twig');
    }
	
	public function empresasAction()
    {
        return $this->render('SiteBundle:Default:empresas.html.twig');
    }
	
	public function comofuncionaAction()
    {
        return $this->render('SiteBundle:Default:comofunciona.html.twig');
    }
}
