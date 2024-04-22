<?php

namespace App\Form;

use App\Entity\Snacks;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;


class SnackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('snackname',
            TextType::class,[
                'constraints'=>[new Regex([
                    'pattern'=>'/^[a-zA-Z ]+$/i',
                    'message' => 'Alphabets and Spaces Only Allowed!'
                ]),
                    new NotBlank([
                        'message' => 'Snackname Field cannot be Blank!'
                    ])
                ],
                'required'=>false,
                'attr'=> array('class'=>'myformfield')
            ]
            )
          //  ->add('createdtime')
          //  ->add('modifiedtime')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Snacks::class,
        ]);
    }
}
