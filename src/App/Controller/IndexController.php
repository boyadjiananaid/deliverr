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
        
        $categories = $app['db']->fetchAll('SELECT * FROM view_name');

        return $app['twig']->render('index.html.twig', [
            'categories' => $categories
        ]);
    }
    
    public function categorieAction($libellecategorie, Application $app) {
        $souscategories = $app['idiorm.db']->for_table('view_name')
                                    ->where('LIBELLECATEGORIE', ucfirst($libellecategorie))
                                    ->find_result_set();

        return $app['twig']->render('souscategorie.html.twig', [
            'souscategories' => $souscategories
        ]);
    }
    
    public function sousCategorieAction($libellesouscategorie, Application $app) {
        $listeservices = $app['idiorm.db']->for_table('view_name')
                                    ->where('LIBELLESOUSCATEGORIE', ucfirst($libellesouscategorie))
                                    ->find_result_set();

        return $app['twig']->render('listeservice.html.twig', [
            'services' => $libellesouscategorie
        ]);
    }

    /*
        Affichage de la Page Article
        @return Symfony\Component\HttpFoundation\Response;    
    */  
    public function articleAction(Application $app, $libellecategorie, $slugarticle, $idarticle) {
        $article = $app['idiorm.db']->for_table('view_articles')->find_one($idarticle);

        $suggestions = $app['idiorm.db']->for_table('view_articles')->where('LIBELLECATEGORIE', ucfirst($libellecategorie))->where_not_equal('IDARTICLE', $idarticle)->limit(3)->order_by_desc('IDARTICLE')->find_result_set();; #Je récupère les articles de la même catégorie que mon article.

        return $app['twig']->render('article.html.twig',['article'=>$article, 'suggestions'=>$suggestions]);
    }
    
    public function menu(Application $app) {
        $categories = $app['idiorm.db']->for_table('categorie')->find_result_set();
        return $app['twig']->render('menu.html.twig',['categories' => $categories]);
    }
    
    
     public function connexionAction(Application $app, Request $request) {
        return $app['twig']->render('connexion.html.twig', [
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username')
        ]);
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