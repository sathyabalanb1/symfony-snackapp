<?php

namespace App\Form;

use App\Entity\Vendor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;



class VendorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('vendorname',TextType::class,[
            'constraints'=>[new NotBlank([
                'message' => 'employee name Field cannot be Blank!'
            ]),
                new Regex([
                    'pattern'=>'/^[a-zA-Z ]+$/i',
                    'message'=>'Alphabets and Spaces only allowed'
                ])
            ],
            'attr'=> array('class'=>'myformfield'),
            'required'=>false
        ])
        ->add('vendorlocation',TextareaType::class,[
            'constraints'=>[new NotBlank([
                'message' => 'Vendorlocation Should Not Be Blank!'
            ])
            ],
            'attr'=> array('class'=>'myformfield'),
            'required'=>false
            
        ])
        ->add('cnumber',TextType::class,['label'=>'Contact Number',
            'constraints'=>[new NotBlank([
                'message'=>'Contact Number Should Not be Blank'
            ]),
                new Regex([
                    'pattern'=>'/^[0-9]+$/i',
                    'message'=>'Numerals Only Allowed'
                ]),
                new Length([
                    'min' => 10,
                    'max' => 10,
                    'exactMessage' => 'This value should have exactly Ten Digits'
                ])
            ],
            'attr'=> array('class'=>'myformfield'),
            'required'=>false
            
            
        ])
            //->add('createdtime')
            //->add('modifiedtime')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vendor::class,
        ]);
    }
}
