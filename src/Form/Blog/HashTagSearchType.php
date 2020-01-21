<?php


namespace App\Form\Blog;

use App\Entity\BlogTopicHashTag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class HashTagSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'style' => 'width: 600px',
                    'placeholder' => 'Search by hash tag...',
                ],
                'constraints' => [
                    new Length(['max' => 20]),
                    new NotBlank(),
                    new Callback([$this, 'validateHash'])
                ]
            ]);
    }

    public function validateHash($hashTag, ExecutionContextInterface $context)
    {
        if (strlen($hashTag)<1) {
            $context
                ->buildViolation("Hash tag cannot be empty")
                ->atPath('name')
                ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver -> setDefaults([
           'data_class' => BlogTopicHashTag::class,
        ]);
    }
}
