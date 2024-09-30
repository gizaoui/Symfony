<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BanWordValidator extends ConstraintValidator {

    public function validate(mixed $value, Constraint $constraint): void {
 
      // La contraite est valide. le paramètre $value est une chaîne de caractère.
      if (null === $value || '' === $value) {
          return;
      }
 
       // Les mots banis  ['spam', 'viagra'] seront déclaré en minuscule
       $value = strtolower($value);
 
       // Boucle définis dans l'attrubus de la classe 'BanWord' 
       foreach ($constraint->banWords as $banWord) {
          // Si la chaîne '$value' contient l'un des mots banis.
          if (str_contains($value, $banWord)) {
 
             // Exécuté lors de la présence d'une erreur
             $this->context->buildViolation($constraint->message)
                // L'attribue '$message' de la classe 'BanWord'
                // attend à présent le paramètre '{{ banWord }}'
                // et non {{ value }}
                ->setParameter('{{ banWord }}', $banWord)
                ->addViolation(); // Indique la présence d'un problème.
          }
      }
    }
 }
 
