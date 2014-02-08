<?php

namespace Clube\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Clube\SiteBundle\Entity\Idea;
use Clube\SiteBundle\Form\IdeaType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Idea controller.
 *
 * @Route("/ideia")
 */
class IdeaController extends Controller
{

    /**
     * Lists all Idea entities.
     *
     * @Route("/", name="ideia")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SiteBundle:Idea')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Idea entity.
     *
     * @Route("/", name="ideia_create")
     * @Method("POST")
     * @Template("SiteBundle:Idea:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $usr = $this->get('security.context')->getToken()->getUser();
        $userProfile = $em->getRepository('SiteBundle:User')->find($usr->getId());

        $entity = new Idea();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid() && $userProfile != null) {
            $entity->setUser($userProfile);
            $entity->setCreateDate(new \DateTime("now"));

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ideia_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Idea entity.
    *
    * @param Idea $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Idea $entity)
    {
        $form = $this->createForm(new IdeaType(), $entity, array(
            'action' => $this->generateUrl('ideia_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Idea entity.
     *
     * @Route("/new", name="ideia_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('SiteBundle:Project')->find($id);

        if ($project == null)
            throw new NotFoundHttpException("Page not found");

        $maxIdeas = $project->getMaxIdeas();

        $usr = $this->get('security.context')->getToken()->getUser();
        $total = $em->getRepository('SiteBundle:User')->find($usr->getId())->getIdeas()->count();

        $maxIdeas = $maxIdeas - $total;

        $entity = new Idea();
        $entity->setProject($project);
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'maxIdeas' => $maxIdeas
        );
    }

    /**
     * Finds and displays a Idea entity.
     *
     * @Route("/{id}", name="ideia_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteBundle:Idea')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Idea entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Idea entity.
     *
     * @Route("/{id}/edit", name="ideia_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteBundle:Idea')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Idea entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Idea entity.
    *
    * @param Idea $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Idea $entity)
    {
        $form = $this->createForm(new IdeaType(), $entity, array(
            'action' => $this->generateUrl('ideia_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Idea entity.
     *
     * @Route("/{id}", name="ideia_update")
     * @Method("PUT")
     * @Template("SiteBundle:Idea:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteBundle:Idea')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Idea entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ideia_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Idea entity.
     *
     * @Route("/{id}", name="ideia_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SiteBundle:Idea')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Idea entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ideia'));
    }

    /**
     * Creates a form to delete a Idea entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ideia_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
