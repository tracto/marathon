<?php

namespace TdS\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TdSUserBundle extends Bundle{
	public function getParent(){
		return 'FOSUserBundle';
	}
}

?>
