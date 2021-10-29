<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add("index",'detail')

            ;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud -> setDefaultSort(['id' => 'desc']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt'),
            TextField::new('user.getFullName','prenom et nom'),
            //si on nomme notre fonction getTotal ici on appelle alors total
            MoneyField::new('total')->setCurrency('EUR'),
            TextField::new('carierName',"Transporteur"),
            MoneyField::new('carierPrice','frais de port') -> setCurrency('EUR'),
            BooleanField::new('isPaid','Payée?'),
            ArrayField::new('orderDetails' , "produits")->hideOnIndex()
        ];
    }

}
