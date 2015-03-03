<?php

namespace FrontOffice\RatingBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FrontOfficeRatingBundle extends Bundle
{
    function getParent() {
       return 'DCSRatingBundle';
     }
}
