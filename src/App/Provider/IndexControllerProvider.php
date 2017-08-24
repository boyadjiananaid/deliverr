<?php
namespace App\Provider;
use Silex\Api\ControllerProviderInterface;
class IndexControllerProvider implements ControllerProviderInterface {

    /**
     * {@inheritDoc}
     * @see \Silex\Api\ControllerProviderInterface::connect()
     */
    public function connect(\Silex\Application $app)
    {

        # : Créer une instance de Silex\ControllerCollection
        # : https://silex.symfony.com/api/master/Silex/ControllerCollection.html
        $controllers = $app['controllers_factory'];

        # Page d'Accueil
        $controllers
            # On associe une Route à un Controller et une Action
            ->get('/', 'App\Controller\IndexController::indexAction')
            ->assert('libellecategorie', '[^/]+')
            # En option je peux donner un nom à la route, qui servira plus tard
            # pour la créations de lien : "controller_action"
            ->bind('deliverr_home');

        # Page categories
        $controllers
            ->get('/{libellecategorie}', 'App\Controller\IndexController::categorieAction')
            ->assert('libellecategorie', '[^/]+')
            ->bind('deliverr_categorie');
        
        # Page sous-categories
        $controllers
            ->get('/{libellesouscategorie}', 'App\Controller\IndexController::sousCategorieAction')
            ->assert('libellesouscategorie', '[^/]+')
            ->bind('deliverr_souscategorie');

        # Page services
        $controllers
            ->get('/{libellecategorie}/{slugservice}_{idservice}.html', 'App\Controller\IndexController::serviceAction')
            ->assert('idservice', '\d+')
            ->bind('deliverr_service');


        $controllers
            # On associe une Route à un Controller et une action
            ->get('/connexion', 'App\Controller\IndexController::connexionAction')
            # En option, je peux donner un nom à la route qui servira plus tard pour la création de lien
            ->bind('deliverr_connexion');

        # DeConnexion à l'Administration
        $controllers
            ->get('/deconnexion', 'App\Controller\IndexController::deconnexionAction')
            ->bind('deliverr_deconnexion');


        $controllers
            ->get('/inscription', 'App\Controller\IndexController::inscriptionAction')
            ->bind('deliverr_inscription');



        $controllers
            ->post('/inscription', 'App\Controller\IndexController::inscriptionPost')
            ->bind('deliverr_inscription_post');

        $controllers
            ->post('/newsletter', 'App\Controller\IndexController::newsletterAjax')
            ->bind('newsletter_ajax');
        # On retourne la liste des controllers (ControllerCollection)
        return $controllers;

    }
}