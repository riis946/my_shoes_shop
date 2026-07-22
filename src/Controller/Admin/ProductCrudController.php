<?php

namespace App\Controller\Admin;

use App\Entity\Product;
//  Correction du namespace EasyAdmin ci-dessous
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Nom de la chaussure');
        yield TextField::new('brand', 'Marque');
        yield MoneyField::new('price', 'Prix')->setCurrency('EUR');
        yield TextEditorField::new('description', 'Description');
        yield AssociationField::new('category', 'Catégorie');

        yield ImageField::new('image', 'Photo de la chaussure')
            ->setBasePath('uploads/products/')
            ->setUploadDir('public/uploads/products/')
            ->setUploadedFileNamePattern('[slug]-[uuid].[extension]')
            ->setRequired(false);
    }
}
