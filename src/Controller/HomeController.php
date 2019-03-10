<?php
/**
 * This file is part of the application
 *
 * (c) Long Nguyen <hello@longnd.me>
 *
 * Date: 2019-03-10
 * Time: 11:44
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
