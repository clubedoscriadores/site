<?php
namespace Clube\SiteBundle\Twig;
class SiteExtension extends \Twig_Extension
{
   public function getFilters()
    {
        return array(
            'datetime' => new \Twig_Filter_Method($this, 'datetime')
        );
    }

    public function datetime($d, $format = "%B %e, %Y %H:%M")
    {
		setlocale(LC_ALL, "pt_BR", "ptb");
        if ($d instanceof \DateTime) {
            $d = $d->getTimestamp();
        }

        return strftime($format, $d);
    }

    public function getName()
    {
        return 'Helper';
    }
}
