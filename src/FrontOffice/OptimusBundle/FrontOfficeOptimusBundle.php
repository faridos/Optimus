<?php

namespace FrontOffice\OptimusBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FrontOfficeOptimusBundle extends Bundle
{
     function getParent() {
       return 'SlyRelationBundle';
     }
}
