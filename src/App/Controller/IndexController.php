<?php
namespace App\Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
class IndexController
{
    /**
     * Affichage de la Page d'Accueil
     * @return Symfony\Component\HttpFoundation\Response;
     */
    public function indexAction(Application $app) {
        

        return $app['twig']->render('index.html.twig');
    }
    
    public function categorieAction($libellecategorie, Application $app) {
        $souscategories = $app['idiorm.db']->for_table('view_souscat')
                                    ->where('LIBELLECATEGORIE', ucfirst($libellecategorie))
                                    ->find_result_set();

        return $app['twig']->render('souscategorie.html.twig', [
            'souscategories' => $souscategories,
            'categorie' => ucfirst($libellecategorie)
        ]);
    }
    
    public function sousCategorieAction($libellesouscategorie, Application $app) {
        $listeservices = $app['idiorm.db']->for_table('view_name')
                                    ->where('LIBELLESOUSCATEGORIE', ucfirst($libellesouscategorie))
                                    ->find_result_set();
        
        $descriptionsouscategorie = $app['idiorm.db']->for_table('view_souscat')
                                    ->where('LIBELLESOUSCATEGORIE', ucfirst($libellesouscategorie))
                                    ->find_one();

        return $app['twig']->render('listeservice.html.twig', [
            'services' => $listeservices,
            'souscategorie' => ucfirst($libellesouscategorie),
            'descriptionsouscategorie' => $descriptionsouscategorie
        ]);
    }

    /*
        Affichage de la Page Article
        @return Symfony\Component\HttpFoundation\Response;    
    */  
    public function serviceAction(Application $app, $libellesouscategorie, $idservice) {
        $article = $app['idiorm.db']->for_table('view_name')
                                    ->where('IDSERVICE', $idservice)
                                    ->find_result_set();

        return $app['twig']->render('service.html.twig',['services'=>$article]);
    }
    
    public function menu(Application $app) {
        $categories = $app['idiorm.db']->for_table('categorie')->find_result_set();
        return $app['twig']->render('menu.html.twig',['categories' => $categories]);
    }
    
    
    public function contactAction(Application $app, Request $request) {
   
        
        if($request->isMethod('POST')) :
        
        $message = (new \Swift_Message('My important subject here'))
            ->setFrom(array('deliverr.gac@yahoo.com'))
            ->setTo(array('deliverr.gac@yahoo.com'))
            ->setBody($request->get('message'), 'text/html');
    
        
            return $app['mailer']->send($message);
        
        
        
        endif;


        return $app['twig']->render('contact.html.twig');

    }
    
    
    public function panierAction(Application $app) {
        return $app['twig']->render('panier.html.twig');
    }
    
    
    
    public function faqAction(Application $app) {
        return $app['twig']->render('faq.html.twig');
    }
    
    public function mentionsAction(Application $app) {
        return $app['twig']->render('mentions.html.twig');
    }
    
     public function connexionAction(Application $app) {
        return $app['twig']->render('connexion.html.twig');
    }
    
    public function inscriptionAction(Application $app) {
        return $app['twig']->render('inscription.html.twig');
    }
    
    public function inscriptionPost(Application $app, Request $request){
        #Verification et sécurisation des données POST
        #...
        
        # Connection à la base de données
        $auteur = $app['idiorm.db']->for_table('auteur')->create();
        
        # Affectation des valeurs
        $auteur->PRENOMAUTEUR    = $request->get('PRENOMAUTEUR');
        $auteur->NOMAUTEUR    = $request->get('NOMAUTEUR');
        $auteur->EMAILAUTEUR    = $request->get('EMAILAUTEUR');
        $auteur->MDPAUTEUR    = $app['security.encoder.digest']->encodePassword($request->get('MDPAUTEUR'), '');
        $auteur->ROLESAUTEUR    = $request->get('ROLEAUTEUR');
        
        $auteur->save();
        
        # On envoie un email de confirmation ou de bienvenue, une notification à l'admin, et on redirige sur page accueil
        #...
        
        return $app->redirect('connexion?inscription=success');
    }
    
    public function deconnexionAction(Application $app) {
        $app['session']->clear();
        return $app->redirect($app['url_generator']->generate('technews_home'));
    }
    
    public function newsletterAjax(Application $app, Request $request) {
        if($request->isMethod('POST')) :
            $isMailInDb = $app['idiorm.db']->for_table('newsletter')
                                            ->where('EMAILNEWSLETTER', $request->get('EMAILNEWSLETTER'))
                                            ->count();
        if(!$isMailInDb) :
            $news = $app['idiorm.db']->for_table('newsletter')->create();
            $news->EMAILNEWSLETTER = $request->get('EMAILNEWSLETTER');
            $news->CONTACTNEWSLETTER = $request->get('CONTACTNEWSLETTER');
            $news->save();
        
        $result = ['response' => true];
        else :
        $result = ['response' => false];
        endif;
        
        return $app->json($result);
        
        endif;
    }
    
}