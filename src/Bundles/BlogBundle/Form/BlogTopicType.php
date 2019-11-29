<?php


namespace App\Bundles\BlogBundle\Form;

use App\Entity\BlogComment;
use App\Entity\BlogTopic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class BlogTopicType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        parent::buildForm($builder, $options); // TODO: Change the autogenerated stub
        $builder
            ->add('name', TextType::class, [
                'label' => 'Blog topic',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Blog text',
                'constraints' => [
                    new NotBlank(),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
//        parent::configureOptions($resolver); // TODO: Change the autogenerated stub
        $resolver -> setDefaults([
            'data_class' => BlogTopic::class,
        ]);
    }

}