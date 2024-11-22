<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['attr' => ['class' => 'form-control mb-2'], 'required' => false])
            ->add('category', EntityType::class,  ['class' => Category::class, 'attr' => ['class' => 'form-control mb-2']])
            ->add('tags', EntityType::class,  ['class' => Tag::class, 'attr' => ['class' => 'form-control mb-2'], 'multiple' => true])
            ->add('body', TextareaType::class, ['attr' => ['class' => 'form-control mb-2']])
            ->add('image', FileType::class, ['attr' => ['class' => 'form-control mb-2'], 'data_class' => null, 'required' => false, 'mapped' => false])
            ->add('Save', SubmitType::class,[
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
