<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWord extends Constraint {

    // Création des attribus 'public' au niveau des constructeurs
    // Le constructeur est redéfinit et doit se claquer sur le constructeur parent :
    // function __construct(mixed $options = null, 
    //                      ?array $groups = null, 
    //                      mixed $payload = null) ...
    public function __construct(
        // Message affiché en cas d'erreurs
        public string $message = 'The value "{{ banWord }}" is not valid.',
        // 
        public array $banWords = ['spam', 'viagra'],
        ?array $groups = null,
        mixed $payload = null ) {
          // ~ super() en Java
          parent::__construct(null, $groups, $payload);
    }
 }