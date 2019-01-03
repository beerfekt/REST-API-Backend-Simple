<?php

namespace App\Controller\Rest;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Article;
use FOS\RestBundle\View\View;



class ArticleController extends FOSRestController {




    // Test via terminal:
    // curl -i -X PUT -H 'Content-Type: application/json' -d '{"title": "Updated Title", "content": "updated Content"}' http://rest-tutorial.test/api/articles
    /**
     * Creates an Article resource
     * @Rest\Post("/articles")
     * @param Request $request
     * @return View
     */

    public function postArticle(Request $request): View
    {
        //Article aus Request befüllen:
        $article = new Article();
        $article->setTitle($request->get('title'));
        $article->setContent($request->get('content'));

    //In DB schreiben:
        $entityManager = $this->getDoctrine()->getManager();
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($article);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        // In case our POST was a success we need to return a 201 HTTP CREATED response
        return View::create($article, Response::HTTP_CREATED);
    }


    //Test getArticles with Browser-URL:
    // http://rest-tutorial.test/api/articles/3

    /**
     * Retrieves an Article resource
     * @Rest\Get("/articles/{articleId}")
     */
    public function getArticle(int $articleId): View
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($articleId);
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($article, Response::HTTP_OK);
    }


    //Test getArticles with Browser-URL:
    // http://rest-tutorial.test/api/articles

    /**
     * Retrieves a collection of Article resource
     * @Rest\Get("/articles")
     */
    public function getArticles(): View
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        // In case our GET was a success we need to return a 200 HTTP OK response with the collection of article object
        return View::create($articles, Response::HTTP_OK);
    }


    // Test - via Terminal:
    // curl -i -X PUT -H 'Content-Type: application/json' -d '{"title": "Updated Title", "content": "updated Content"}' http://rest-tutorial.test/api/articles/3
    /**
     * Replaces Article resource
     * @Rest\Put("/articles/{articleId}")
     */
    public function putArticle(int $articleId, Request $request): View
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($articleId);
        if ($article) {
            $article->setTitle($request->get('title'));
            $article->setContent($request->get('content'));
        //In DB schreiben:
            $entityManager = $this->getDoctrine()->getManager();
            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($article);
            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();
        }
        // In case our PUT was a success we need to return a 200 HTTP OK response with the object as a result of PUT
        return View::create($article, Response::HTTP_OK);
    }


    //Test Delete -> Terminal:
    // curl -i -X DELETE http://rest-tutorial.test/api/articles/3
    /**
     * Deletes Article resource
     * @Rest\Delete("/articles/{articleId}")
     */
    public function deleteArticle(int $articleId): View
    {
        //grep the article of db
        $article = $this->getDoctrine()->getRepository(Article::class)->find($articleId);
        //if there's a article token...
        if ($article) {
            //fetch the "doctrine db-manager"
            $entityManager = $this->getDoctrine()->getManager();
            // tell Doctrine you want to delete the article
            $entityManager->remove($article);
            // actually executes the delete query
            $entityManager->flush();
        }
        // In case our DELETE was a success we need to return a 204 HTTP NO_CONTENT response with the object as a result of PUT
        return View::create($article, Response::HTTP_NO_CONTENT);
    }

}
