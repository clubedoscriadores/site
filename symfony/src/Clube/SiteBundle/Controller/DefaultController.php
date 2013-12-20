<?php

namespace Clube\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Clube\SiteBundle\Entity\Contact;
use Clube\SiteBundle\Form\ContactType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;
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
	
	public function contatoAction(Request $request)
    {
		$em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new ContactType(), new Contact(), array(
            'action' => $this->generateUrl('site_contato'),
        ));
		
		$form->handleRequest($request);

		if ($form->isValid()) {
			$contact = $form->getData();
			$contact->setCreateDate(new DateTime("now"));
			$em->persist($contact);
			$em->flush();

			return $this->redirect($this->generateUrl('site_homepage', array('send' => 'contact')));
		}

        return $this->render(
            'SiteBundle:Default:contato.html.twig',
            array('form' => $form->createView())
        );
    }
}
