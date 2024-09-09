<?php

namespace App\Normaliser;

use App\Entity\Recipe;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Normalizer;
use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PaginationNormaliser implements NormalizerInterface
{

   public function __construct(
      #[Autowire(service: 'serializer.normalizer.object')]
      private readonly NormalizerInterface $normalizer
   ) {}

   public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
   {
      if (!($object instanceof PaginationInterface)) {
         throw new \RuntimeException();
      }

      // 'items' => $this->normalizer->normalize($object->getItems(), $format, $context),
      return [
         'items' => array_map(fn (Recipe $recipe) => $this->normalizer->normalize($recipe, $format, $context), $object->getItems()),
         'total' => $object->getTotalItemCount(),
         'page' => $object->getCurrentPageNumber(),
         'lastPage' => ceil($object->getTotalItemCount() / $object->getItemNumberPerPage())
      ];
   }

   public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
   {
      return $data instanceof PaginationInterface && $format === 'json';
   }

   public function getSupportedTypes(?string $format): array
   {
      return [
         PaginationInterface::class => true
      ];
   }
}
