<?php

namespace FrontOffice\OptimusBundle\Validator\Constraints;
use Symfony\Component\Validator\Context\LegacyExecutionContext;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Validator\ConstraintValidator;


class ContraintValidDateValidator  {
    static public function isDateValid($entity, ExecutionContext  $context)
    {
        // This code block gets executed but $this->getEndDate() is NULL
        
        if( $entity->getDatefin() != null && $entity->getDatedebut() != null && $entity->getDatefin()->getTimestamp() < $entity->getDatedebut()->getTimestamp() ){
            $context->addViolation('End Date cannot be less than Start Date.', array(), null);
        }
    } 
}
