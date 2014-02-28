<?php

namespace Clube\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Clube\SiteBundle\Entity\VideoPrize;
use Clube\SiteBundle\Form\VideoPrizeType;

/**
 * VideoPrize controller.
 *
 * @Route("/premiacao/video")
 */
class VideoPrizeController extends Controller
{

    /**
     * Lists all VideoPrize entities.
     *
     * @Route("/", name="premiacao_video")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SiteBundle:VideoPrize')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new VideoPrize entity.
     *
     * @Route("/", name="premiacao_video_create")
     * @Method("POST")
     * @Template("SiteBundle:VideoPrize:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new VideoPrize();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setCreateDate(new \DateTime("now"));
            $place = $em->getRepository('SiteBundle:Project')->find($entity->getProject()->getId())->getVideoPrizes()->count();
            $place++;
            $entity->setPrizePlace($place);

            $em->persist($entity);
            $em->flush();

            $project = $em->getRepository('SiteBundle:Project')->find($entity->getProject()->getId());
            $totalAmount = $this->_sumTotalAmount($project);
            $project->setTotalPrize($totalAmount);
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('projetos_show_2', array('id' => $entity->getProject()->getId(), 'aba' => 'premiados')));
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
    * Creates a form to create a VideoPrize entity.
    *
    * @param VideoPrize $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(VideoPrize $entity)
    {
        $form = $this->createForm(new VideoPrizeType(), $entity, array(
            'action' => $this->generateUrl('premiacao_video_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new VideoPrize entity.
     *
     * @Route("/new", name="premiacao_video_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('SiteBundle:Project')->find($id);

        if ($project == null)
            throw new NotFoundHttpException("Page not found");

        $entity = new VideoPrize();
        $entity->setProject($project);
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a VideoPrize entity.
     *
     * @Route("/{id}", name="premiacao_video_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteBundle:VideoPrize')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VideoPrize entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing VideoPrize entity.
     *
     * @Route("/{id}/edit", name="premiacao_video_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteBundle:VideoPrize')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VideoPrize entity.');
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
    * Creates a form to edit a VideoPrize entity.
    *
    * @param VideoPrize $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(VideoPrize $entity)
    {
        $form = $this->createForm(new VideoPrizeType(), $entity, array(
            'action' => $this->generateUrl('premiacao_video_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing VideoPrize entity.
     *
     * @Route("/{id}", name="premiacao_video_update")
     * @Method("PUT")
     * @Template("SiteBundle:VideoPrize:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteBundle:VideoPrize')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VideoPrize entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setCreateDate(new \DateTime("now"));
            $em->flush();

            $project = $em->getRepository('SiteBundle:Project')->find($entity->getProject()->getId());
            $totalAmount = $this->_sumTotalAmount($project);
            $project->setTotalPrize($totalAmount);
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('projetos_show_2', array('id' => $entity->getProject()->getId(), 'aba' => 'premiados')));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a VideoPrize entity.
     *
     * @Route("/{id}", name="premiacao_video_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SiteBundle:VideoPrize')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find VideoPrize entity.');
            }

            $projectId = $entity->getProject()->getId();

            $em->remove($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('projetos_show_2', array('id' => $projectId, 'aba' => 'premiados')));
        }

        return $this->redirect($this->generateUrl('projetos'));
    }

    /**
     * Creates a form to delete a VideoPrize entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('premiacao_video_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
