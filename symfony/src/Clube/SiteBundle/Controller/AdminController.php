<?php

namespace Clube\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $companies = $em->getRepository('SiteBundle:Company')->findAll();

        $projects = $em->getRepository('SiteBundle:Project')->findAll();

        return $this->render('SiteBundle:Admin:index.html.twig',
            array(
                'companies' => $companies,
                'projects' => $projects,
            )
        );
    }
}