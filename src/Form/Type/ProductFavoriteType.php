<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Form\Type;

use PrestaShopBundle\Form\Admin\Type\CustomContentType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductFavoriteType extends TranslatorAwareType
{
    public function __construct(
        TranslatorInterface $translator,
        array $locales
    ) {
        parent::__construct($translator, $locales);
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('html_content', CustomContentType::class, [
                'required' => false,
                'label' => false,
                'template' => '@Modules/is_favoriteproducts/views/templates/admin/render.html.twig',
                'data' => $options['data']['html_content'],
            ]);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'label' => $this->trans('Favorite products stats', 'Modules.Isfavoriteproducts.Admin'),
            ])
        ;
    }
}
