<?php


// src/Menu/Builder.php
namespace App\Menu;

use App\Entity\JobCategory;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

final class Builder
{

    private $em;

    /**
     * Add any other dependency you need...
     * @param FactoryInterface $factory
     */
    private $factory;

    public function __construct(FactoryInterface $factory, EntityManagerInterface $entityManager)
    {
        $this->factory = $factory;
        $this->em = $entityManager;
    }

    public function mainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root',[
            'childrenAttributes'    => array(
                'class'             => 'list-unstyled',
            ),
        ]);

        $menu->addChild('Home', ['route' => 'home']);

        // access services from the container!

        // findMostRecent and Blog are just imaginary examples
        $blog = $this->em->getRepository(JobCategory::class)->find(1);

        $menu->addChild('Latest Blog Post', [
            'route' => 'job_category_show',
            'routeParameters' => ['id' => $blog->getId()]
        ]);

        // create another menu item
        //$menu->addChild('About Me', ['route' => 'about']);
        // you can also add sub levels to your menus as follows
        //$menu['About Me']->addChild('Edit profile', ['route' => 'edit_profile']);

        // ... add more children

        return $menu;
    }
}
