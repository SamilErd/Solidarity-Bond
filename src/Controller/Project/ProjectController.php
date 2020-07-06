<?php

namespace App\Controller\Project;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Cart\CartService;
use App\Repository\ProjectRepository;
use App\Entity\Project;
use App\Entity\Comment;
use App\Form\NewProjectType;

class ProjectController extends AbstractController
{

    
    /**
     * @Route("/projects", name="show_projects")
     */
    public function projects(CartService $cartService, ProjectRepository $projectrepo)
    {
        $projects = $projectrepo->findAll();
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        //redering the homepage
        return $this->render('project/projects.html.twig', [
            'projects' => $projects,
            'num' => $num
        ]);
    }
   
    /**
     * @Route("/project/like", name="like_project")
     */
    public function like_project(ProjectRepository $projectrepo)
    {
        $user = $this->getUser();
        $project = $projectrepo->find($_POST['id']);
        $liked = false;        
        $users = [];
        foreach($project->getLikes() as $like){
            array_push($users, $like);
        }
        if(in_array($user, $users)){
            $project->removeLike($user);
            $liked = false;
        } else {
            $project->addLike($user);
            $liked = true;
        }
        //getting the instance of the entity manager and 
        $entityManager = $this->getDoctrine()->getManager();
        //tells the entity manager to manage the product
        $entityManager->persist($project);
        //inserting the product in the database
        $entityManager->flush();
        
        
        $response = new Response(json_encode(array(
            'likes' => sizeof($project->getLikes()),
            'liked' => $liked,
            'id' => $_POST['id']

        )
            ));
          $response->headers->set('Content-Type', 'application/json');
          return $response;
    }
    /**
     * @Route("/project/comment", name="comment_project")
     */
    public function comment_project(ProjectRepository $projectrepo)
    {
        $user = $this->getUser();
        $commentstr = $_POST['comment'];
        $project = $projectrepo->find($_POST['id']);
        
        if(!empty($commentstr)){
            $comment = new Comment();
            $comment->setIdUser($user);
            $comment->setComment($commentstr);
            $project->addComment($comment);
            //getting the instance of the entity manager and 
            $entityManager = $this->getDoctrine()->getManager();
            //tells the entity manager to manage the product
            $entityManager->persist($comment);
            $entityManager->persist($project);
            //inserting the product in the database
            $entityManager->flush();
        }
        $response = new Response(json_encode(array(
            'comments' => sizeof($project->getComments()),
        )
            ));
          $response->headers->set('Content-Type', 'application/json');
          return $response;
    }
    /**
     * @Route("/project/comments", name="show_comments")
     */
    public function show_comments(ProjectRepository $projectrepo)
    {
        $user = $this->getUser();
        $project = $projectrepo->find($_POST['id']);
        $comments = [];
        $authors = [];
        foreach($project->getComments() as $comment){
            $author = $comment->getIdUser()->getFirstName()." ".$comment->getIdUser()->getLastName();
            array_push($authors, $author);
            array_push($comments , $comment->getComment());
        }
        $response = new Response(json_encode(array(
            'comments' => $comments,
            'authors' => $authors
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/projects/new", name="new_project")
     */
    public function new_project(Request $request,CartService $cartService)
    {
        $images = [];
        //getting the number of cart items
        $num = $cartService->getCartItemNum();
        //creating an instance of the product entity
        $project = new Project() ;
        //creating a form with the product instance and the NewProductType template
        $formnp = $this->createForm(NewProjectType::class, $project);
        //handles the answer from the new product's form
        $formnp->handleRequest($request);

        //if the form is submitted without errors
        if ($formnp->isSubmitted() && $formnp->isValid()) {

            //The file is taken for thhe image
            $files = $formnp['images']->getData();
            foreach($files as $file){
                //Setting a new unique name for the image
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                //moving the image in a specific folder set in config/services.yml
                $file->move($this->getParameter('upload_directory_photos_project'), $fileName);
                array_push($images, $fileName);
            }
            //setting the image field of the product with the filename
            $project->setImages($images);
            //getting the instance of the entity manager and 
            $entityManager = $this->getDoctrine()->getManager();
            //tells the entity manager to manage the product
            $entityManager->persist($project);
            //inserting the product in the database
            $entityManager->flush();
            
            //redirecting to the products page
            return $this->redirectToRoute('show_projects');
        }
        //rendering the product creating page
        return $this->render('admin/project/new_project.html.twig', [
            //giving this page the form value
            'formnp' => $formnp->createView(),
            'num' => $num
        ]);
    }

}
