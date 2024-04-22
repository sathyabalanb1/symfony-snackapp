<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;



class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('employeename',TextType::class,[
            'constraints'=>[new NotBlank([
                'message' => 'employee name Field cannot be Blank!'
            ]), 
                new Regex([
                    'pattern'=>'/^[a-zA-Z ]+$/i',
                    'message'=>'Alphabets and Spaces only allowed'
                ]),
                new Length([
                    'min' => 3,
                    'minMessage' => 'Minimum 3 characters for employeename',
                    'max' => 25,
                    'maxMessage' => 'Maximum 25 characters are allowed for employeename'
                ])
            ],
            'attr'=> array('class'=>'myformfield','maxlength'=>'25'),
            'required'=>false
            
        ])
       // 'pattern'=>'/[a-zA-Z ]/',
        
        ->add('username',EmailType::class,['required' => false,
                    'translation_domain' => 'messages',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Email ID Field cannot be Blank!'
                        ]),  new Email([
                            'message' => "Your Email ID doesn't seems to be valid!"
                        ]),
                        new Length([
                            'max' => 30,
                            'maxMessage' => 'Maximum 30 characters are allowed for username'
                        ])],
                        'attr'=> array('class'=>'myformfield','maxlength'=>'30'),
                        'label'=>'Email ID'
        ])
                        
                        
                        ->add('password', RepeatedType::class,
                            [ 'type' => PasswordType::class,
                              'invalid_message' => 'The password fields must match.',
                              'options' => ['attr' => array('class' => 'password-field myformfield','maxlength'=>'12')], 
                              'required' => false,
                              'translation_domain' => 'messages',
                              'first_options'  => ['label' => 'trans_reg_pwd'],
                              'second_options' => ['label' => 'trans_reg_rpwd'],
                                
                              'constraints' => [
                                new NotBlank([
                                    'message' => 'Password Field cannot be Blank!'
                                ]),
                                new Length([
                                    'min' => 7,
                                    'minMessage' => 'Minimum 7 characters for password',
                                    'max' => 12,
                                    'maxMessage' => 'Maximum 12 characters are allowed'
                                ]), 
                            ],
                        ]);
                        
          //  ->add('createdtime')
            //->add('modifiedtime')
            //->add('role')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
