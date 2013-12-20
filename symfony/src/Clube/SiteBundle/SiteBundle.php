<?php

namespace Clube\SiteBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SiteBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
