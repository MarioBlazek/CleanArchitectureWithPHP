<?php

namespace CleanPhp\Invoicer\Service\InputFilter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator\EmailAddress;

class CustomerInputFilter extends InputFilter
{
    public function __construct()
    {
        $name = (new Input('name'))
            ->setRequired(true);

        $email = (new Input('email'))
            ->setRequired(true);

        $email->getValidatorChain()
            ->attach(new EmailAddress());

        $this->add($name);
        $this->add($email);
    }
}
