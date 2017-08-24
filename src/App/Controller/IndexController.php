<?php
namespace App\Controller;
use Silex\Application;
class IndexController
{
    /**
     * Affichage de la Page d'Accueil
     * @return Symfony\Component\HttpFoundation\Response;
     */
    public function indexAction(Application $app) {
        
        # Déclaration d'un Message
        $message = 'Mon Application Silex !';
       
        # Affichage dans la Vue
        return $app['twig']->render('index.html.twig',[
            'message'  => $message
        ]);
    }
    
    public function menu(Application $app, $active) {
        $categories = $app['idiorm.db']->for_table('categorie')->find_result_set();
        return $app['twig']->render('menu.html.twig',['categories' => $categories, 'active' => $active]);
    }
    
}