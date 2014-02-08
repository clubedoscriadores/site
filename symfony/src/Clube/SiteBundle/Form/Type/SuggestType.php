<?php

namespace Clube\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmTypeGuesser;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Clube\SiteBundle\Form\DataTransformer\ObjectToIdTransformer;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class SuggestType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;
    private $guesser;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om, DoctrineOrmTypeGuesser $guesser)
    {
        $this->om = $om;
        $this->guesser = $guesser;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ObjectToIdTransformer($this->om);
        $builder->addModelTransformer($transformer);

        if($options['data_class'] === null) {

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($transformer, $builder) {

                /* @var $form \Symfony\Component\Form\Form */
                $form = $event->getForm();
                $class = $form->getParent()->getConfig()->getDataClass();
                $property = $form->getName();
                $guessedType = $this->guesser->guessType($class, $property);
                $options = $guessedType->getOptions();

                $transformer->setObjectClass($options['class']);

            });

        } else {

            $transformer->setObjectClass($options['data_class']);

        }
    }

    public function getParent()
    {
        return 'hidden';
    }

    public function getName()
    {
        return 'entity_hidden';
    }

    protected function getClassFromMetadata($name, $parentClass)
    {
        /* @var $md \Doctrine\ORM\Mapping\ClassMetadata */
        $md = $this->getMetadata($parentClass)[0];
        $a = $md->getAssociationMapping($name);
        $class = $a['targetEntity'];

        return $class;
    }

    protected function getMetadata($class)
    {
        if (array_key_exists($class, $this->cache)) {
            return $this->cache[$class];
        }

        $this->cache[$class] = null;
        foreach ($this->registry->getManagers() as $name => $em) {
            try {
                return $this->cache[$class] = array($em->getClassMetadata($class), $name);
            } catch (MappingException $e) {
                // not an entity or mapped super class
            } catch (LegacyMappingException $e) {
                // not an entity or mapped super class, using Doctrine ORM 2.2
            }
        }
    }
}