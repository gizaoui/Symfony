<?php

namespace App\Form;

use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\String\Slugger\SluggerInterface;

class FormListenerFactory {
    
    public function __construct(private SluggerInterface $slugger)
    {
    }
    
    public function autoSlug(string $field): callable
    {
        return function (PreSubmitEvent $event) use ($field)
        {
            $data = $event->getData();
            if (empty($data['slug']))
            {
                # Méthode classique
                // $slugger = new AsciiSlugger(); # implements SluggerInterface
                // $data['slug'] = strtolower($slugger->slug($data[$field]));
                
                # Méthode par injection de dépendance (via __construct)
                $data['slug'] = strtolower($this->slugger->slug($data[$field]));
                $event->setData($data);
            }
        };
    }
    
    public function timestamps(): callable
    {
        return function (PostSubmitEvent $event)
        {
            $data = $event->getData();
            $data->setUpdateAt(new \DateTimeImmutable());
            if (! $data->getId())
            {
                $data->setCreatedAt(new \DateTimeImmutable());
            }
        };
    }
}
