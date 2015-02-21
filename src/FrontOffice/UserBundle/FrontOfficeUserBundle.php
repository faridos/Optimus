<?php

namespace FrontOffice\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FrontOfficeUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
