<?php

namespace Clube\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Clube\SiteBundle\Form\DataTransformer\EntityToIdTransformer;

class EntityHiddenType extends AbstractType
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var ObjectManager
     */
    private $om;
    private $cache;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
        $this->om = $registry->getManager();
        $this->cache = [];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $class = (empty($options['data_class'])) ? $this->getClassFromMetadata($builder->getName(), $builder->getParent()->getDataClass()) : $options['data_class'];

        $transformer = new EntityToIdTransformer($this->om, $class);
        $builder->addViewTransformer($transformer);

        $builder->setAttribute('data_class', $class);
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