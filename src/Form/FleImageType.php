<?php

namespace App\Form;

use App\Entity\FleImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class FleImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', VichImageType::class, [
				'label' => false,
				'required' => false,
				'attr' => [
					'class' => 'image-file',
					'placeholder' => 'Choisir un fichier',
				]
			])
            ->add('title', null, [
                'label' => 'Titre'
            ])
            ->add('description')
		;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FleImage::class,
        ]);
    }
}
