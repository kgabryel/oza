<?php

namespace App\Form;

use App\Config\Message\Error\PhotosErrors;
use App\Entity\Photo;
use App\Model\Form\MainPhoto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MainPhotoForm extends UserForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('photo', EntityType::class, [
            'choices' => $options['photos'],
            'invalid_message' => PhotosErrors::INVALID_PHOTO,
            'class' => Photo::class
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MainPhoto::class,
            'csrf_protection' => false,
            'photos' => []
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
