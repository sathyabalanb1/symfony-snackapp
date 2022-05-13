<?php

namespace App\Form;

use App\Entity\Snackassignment;
use App\Entity\Snacks;
use App\Entity\Vendor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssignmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('presentdate', DateType::class,['widget'=>'single_text','format'=>'yyyy-MM-dd','translation_domain' => 'messages',
            'label'=>'trans_assign_date','data'   => new \DateTime(),
                'attr'   => ['min' => ( new \DateTime() )->format('Y-m-d')]],'')
           // ->add('createdtime')
           // ->add('modifiedtime')
        ->add('snack',EntityType::class,['class'=>Snacks::class, 'choice_label'=>'snackname',
               'translation_domain' => 'messages',
               'label'  =>'trans_assign_snack'     
        ]) 
            ->add('vendor',EntityType::class,['class'=>Vendor::class, 'choice_label'=>'vendorname','label'=>'trans_assign_vendor'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Snackassignment::class,
        ]);
    }
}
