<?php


namespace App\Form\Blog;

use App\Entity\BlogImage;
use App\Entity\BlogTopic;
use App\Entity\BlogTopicHashTag;
use App\Service\FileUploader;
use App\Utils\HashTagsNormalizer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

class TopicType extends AbstractType
{

    /**
     * @var
     */
    private $tags;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'       => 'Topic',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Text',
                'attr'  => [
                    'class' => 'tinymce',
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])

//            ->add('hash', TextType::class, [
//                'label'       => 'HashTag',
//                'constraints' => [
//                    new NotBlank(),
//                    new Regex('/^#/'),
//                    new Length(['max' => 100]),
//                    new Callback([$this, 'validateHashTags'])
//                ]
//            ])

            ->add('hash', ChoiceType::class, [
                'choices'  => [],
                'multiple' => true,
                'attr' => [
                    'class' => 'select2-example'
                ]
            ])

            ->add('images', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'multiple' => true,
                'constraints' => [
                    new Assert\All([
                        new Image(),
                    ]),
                ]
            ]);

//        $builder->get('blogImages')
//            ->addViewTransformer(new CallbackTransformer(
//                function ($imageAsString) {
//                    if ($imageAsString) {
//                        return;
//                    }
//                },
//                function ($imageAsFile) {
//                    $image = new BlogImage();
//                    $fileName = [];
//                    if ($imageAsFile instanceof File) {
//                        $image->setName($imageAsFile->getFilename(). '.' .$imageAsFile->guessExtension());
//                        $fileName[] = $image;
//                    }
//                    return $fileName;
//                }
//            ));

//        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
//            /** @var BlogTopic $data */
//            $data = $event->getData();
//            $tags = '';
//            foreach ($data->getBlogTopicHashTags()->toArray() as $tag) {
//                $tags .= "#{$tag->getName()} ";
//            }
//
//            $data->setHash($tags);
//        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            /** @var BlogTopic $data */
            $data  = $event->getData();
            $topic = $event->getForm()->getData();

            if (!(isset($data['hash']))) {
                $data['hash'] = "";
            }

            $topic->setHash($data['hash']);
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var BlogTopic $data */
            $data  = $event->getData();
            $form  = $event->getForm();

            $tags = [];
            if (is_array($data->getHash())) {
                foreach ($data->getHash() as $tag) {
                    $tags[$tag] = $tag;
                }
            } else {
                $tags[$data->getHash()] = $data->getHash();
            }

            $form->add('hash', ChoiceType::class, [
                'choices'  => $tags,
                'multiple' => true,
                'empty_data' => $tags,
                'attr' => [
                    'class' => 'select2-example'
                ]
            ]);
        });
    }

//    public function validateHashTags($hashTag, ExecutionContextInterface $context)
//    {
//        $hashes = HashTagsNormalizer::normalizeArray($hashTag);
//        if (count($hashes) > BlogTopic::HASH_TAGS_LIMIT) {
//            $context
//                ->buildViolation("Too many tags.")
//                ->atPath('hash')
//                ->addViolation();
//        }
//    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver -> setDefaults([
            'data_class' => BlogTopic::class,
//            'no_validate' => false,
        ]);
    }
}
