<?php

namespace Clube\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Clube\SiteBundle\Entity\IdeaPrize;
use Clube\SiteBundle\Form\IdeaPrizeType;

/**
 * IdeaPrize controller.
 *
 * @Route("/premiacao/ideia")
 */
class IdeaPrizeController extends Controller
{

    /**
     * Lists all IdeaPrize entities.
     *
     * @Route("/", name="premiacao_ideia")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SiteBundle:IdeaPrize')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new IdeaPrize entity.
     *
     * @Route("/", name="premiacao_ideia_create")
     * @Method("POST")
     * @Template("SiteBundle:IdeaPrize:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new IdeaPrize();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setCreateDate(new \DateTime("now"));
            $place = $em->getRepository('SiteBundle:Project')->find($entity->getProject()->getId())->getIdeaPrizes()->count();
            $place++;
            $entity->setPrizePlace($place);

            $em->persist($entity);
            $em->flush();

            $project = $em->getRepository('SiteBundle:Project')->find($entity->getProject()->getId());
            $totalAmount = $this->_sumTotalAmount($project);
            $project->setTotalPrize($totalAmount);
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('premiacao_ideia_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    private function _sumTotalAmount($project)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT SUM(e.prizeAmount) AS balance FROM Clube\SiteBundle\Entity\IdeaPrize e " .
            "WHERE e.project = ?1";
        $totalAmount = $em->createQuery($dql)
            ->setParameter(1, $project)
            ->getSingleScalarResult();

        $dql = "SELECT SUM(e.prizeAmount) AS balance FROM Clube\SiteBundle\Entity\VideoPrize e " .
            "WHERE e.project = ?1";
        $totalAmount += $em->createQuery($dql)
            ->setParameter(1, $project)
            ->getSingleScalarResult();

        return $totalAmount;
    }

    /**
    * Creates a form to create a IdeaPrize entity.
    *
    * @param IdeaPrize $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(IdeaPrize $entity)
    {
        $form = $this->createForm(new IdeaPrizeType(), $entity, array(
            'action' => $this->generateUrl('premiacao_ideia_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new IdeaPrize entity.
     *
     * @Route("/new", name="premiacao_ideia_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('SiteBundle:Project')->find($id);

        if ($project == null)
            throw new NotFoundHttpException("Page not found");

        $entity = new IdeaPrize();
        $entity->setProject($project);
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a IdeaPrize entity.
     *
     * @Route("/{id}", name="premiacao_ideia_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteBundle:IdeaPrize')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find IdeaPrize entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing IdeaPrize entity.
     *
     * @Route("/{id}/edit", name="premiacao_ideia_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteBundle:IdeaPrize')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find IdeaPrize entity.');
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
    * Creates a form to edit a IdeaPrize entity.
    *
    * @param IdeaPrize $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(IdeaPrize $entity)
    {
        $form = $this->createForm(new IdeaPrizeType(), $entity, array(
            'action' => $this->generateUrl('premiacao_ideia_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing IdeaPrize entity.
     *
     * @Route("/{id}", name="premiacao_ideia_update")
     * @Method("PUT")
     * @Template("SiteBundle:IdeaPrize:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteBundle:IdeaPrize')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find IdeaPrize entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('premiacao_ideia_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a IdeaPrize entity.
     *
     * @Route("/{id}", name="premiacao_ideia_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SiteBundle:IdeaPrize')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find IdeaPrize entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('premiacao_ideia'));
    }

    /**
     * Creates a form to delete a IdeaPrize entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('premiacao_ideia_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
