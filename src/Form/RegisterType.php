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

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('employeename')
        ->add('username',EmailType::class,['required' => false,
                    'translation_domain' => 'messages',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Email Field cannot be Blank!'
                        ]),  new Email([
                            'message' => "Your email doesn't seems to be valid!"
                        ]),  ],])
                        ->add('password', RepeatedType::class,
                            [ 'type' => PasswordType::class,
                              'invalid_message' => 'The password fields must match.',
                              'options' => ['attr' => ['class' => 'password-field']], 
                              'required' => false,
                              'translation_domain' => 'messages',
                              'first_options'  => ['label' => 'trans_reg_pwd'],
                              'second_options' => ['label' => 'trans_reg_rpwd'],
                              'constraints' => [
                                new NotBlank([
                                    'message' => 'Password Field cannot be Blank!'
                                ]),
                                new Length([
                                    'min' => 4,
                                    'minMessage' => 'Minimum 4 characters for password',
                                    'max' => 15,
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
