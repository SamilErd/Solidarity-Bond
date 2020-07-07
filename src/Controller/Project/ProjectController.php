<?php

namespace App\Controller\Project;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Cart\CartService;
use App\Repository\ProjectRepository;
use App\Repository\CommentRepository;
use App\Entity\Project;
use App\Entity\Comment;
use App\Form\NewProjectType;
use Symfony\Component\Filesystem\Filesystem;

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
     * @Route("/comment/like", name="like_comment")
     */
    public function like_comment(CommentRepository $commentrepo)
    {
        $user = $this->getUser();
        $comment = $commentrepo->find($_POST['id']);
        $liked = false;        
        $users = [];
        foreach($comment->getLikes() as $like){
            array_push($users, $like);
        }
        if(in_array($user, $users)){
            $comment->removeLike($user);
            $liked = false;
        } else {
            $comment->addLike($user);
            $liked = true;
        }
        //getting the instance of the entity manager and 
        $entityManager = $this->getDoctrine()->getManager();
        //tells the entity manager to manage the product
        $entityManager->persist($comment);
        //inserting the product in the database
        $entityManager->flush();
        
        
        $response = new Response(json_encode(array(
            'clikes' => sizeof($comment->getLikes()),
            'cliked' => $liked,

        )
            ));
          $response->headers->set('Content-Type', 'application/json');
          return $response;
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
        $ids = [];
        $cids = [];
        $ulikes = [];
        $commentlike = [];
        $likenum = [];
        foreach($project->getComments() as $comment){
            $author = $comment->getIdUser()->getFirstName()." ".$comment->getIdUser()->getLastName();
            array_push($authors, $author);
            array_push($comments , $comment->getComment());
            array_push($ids , $comment->getIdUser()->getId());
            array_push($cids , $comment->getId());
            array_push($likenum, count($comment->getLikes()));
            foreach($comment->getLikes() as $like){
                array_push($ulikes, $like->getId());
                array_push($commentlike, $comment->getId());
            }
        }




        $response = new Response(json_encode(array(
            'comments' => $comments,
            'authors' => $authors,
            'id_user' => $ids,
            'id_comment' => $cids,
            'ulikes' => $ulikes,
            'cl' => $commentlike,
            'ln' => $likenum


            
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("/project/comment/delete", name="delete_comment")
     */
    public function delete_comments(CommentRepository $commentrepo)
    {

        $comment = $commentrepo->find($_POST['id']);
        $project = $comment->getIdProject();
        //getting the instance of the entity manager and 
        $entityManager = $this->getDoctrine()->getManager();
        //tells the entity manager to manage the product
        $entityManager->remove($comment);
        //inserting the product in the database
        $entityManager->flush();
        $response = new Response(json_encode(array(
            'res' => 'OK',
            'comments' => sizeof($project->getComments())
            
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("/project/delete", name="delete_project")
     */
    public function delete_project(ProjectRepository $projectrepo)
    {
        $project = $projectrepo->find($_POST['id']);
        //getting the instance of the entity manager and 
        $entityManager = $this->getDoctrine()->getManager();
        foreach($project->getImages() as $image){
            $filename = $image;
            $filesystem = new Filesystem();
            $filesystem->remove($this->getParameter('upload_directory_photos_project')."/".$filename);
        }
        //tells the entity manager to manage the product
        $entityManager->remove($project);
        //inserting the product in the database
        $entityManager->flush();
        $response = new Response(json_encode(array(
            'res' => 'OK'
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("/project/delete/photo", name="delete_photo")
     */
    public function delete_photo(ProjectRepository $projectrepo)
    {
        $project = $projectrepo->find($_POST['pid']);
        //getting the instance of the entity manager and 
        $entityManager = $this->getDoctrine()->getManager();
        $images = [];
        $filename = $project->getImages()[$_POST['key']];
        foreach($project->getImages() as $key=>$image){
            array_push($images, $image);
            if($image == $filename){
                
                $filesystem = new Filesystem();
                $filesystem->remove($this->getParameter('upload_directory_photos_project')."/".$filename);
                unset($images[$key]);
            }
        }
        $project->setImages($images);
        //tells the entity manager to manage the product
        $entityManager->persist($project);
        //inserting the product in the database
        $entityManager->flush();
        $response = new Response(json_encode(array(
            'res' => $images
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
