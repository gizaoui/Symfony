<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWord extends Constraint {
    
    public function __construct( # @formatter:off
                                 public string $message = 'The value "{{ banWord }}" is not valid.', 
                                 public array $banWords = ['spam', 'viagra'], 
                                 ?array $groups = null, 
                                 mixed $payload = null)
                                 # @formatter:on
    {
        parent::__construct(null, $groups, $payload);
    }
}



