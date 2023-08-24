<?php
/**
 * Registration form.
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class UserPasswordType.
 */
class RegistrationType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'email',
            EmailType::class,
            [
                'label' => 'label_email',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 180]),
                    ],
            ]
        )
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'required' => true,
                    'constraints' => [
                        new Length(['min' => 6, 'max' => 191]),
                        new NotBlank(),
                    ] ,
                    'first_options' => ['label' => 'label_password'],
                    'second_options' => ['label' => 'label_confirm_password'],
                ],
            )
            ->add(
                'firstName',
                TextType::class,
                [
                    'label' => 'label_first_name',
                    'required' => true,
                    'constraints' => [
                        new Length(['min' => 3, 'max' => 16]),
                        new NotBlank(),
                    ] ,
                    'attr' => [
                        'minlength' => 3,
                        'maxlength' => 16,
                    ],
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'label' => 'label_last_name',
                    'required' => true,
                    'constraints' => [
                        new Length(['min' => 3, 'max' => 32]),
                        new NotBlank(),
                    ],
                    'attr' => [
                        'minlength' => 3,
                        'maxlength' => 32,
                    ],
                ]
            );
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'user';
    }
}
