<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Project;
use App\Entity\Machine;
use App\Entity\Comment;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encode;
 
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encode = $encoder;
    }
 


    public function load(ObjectManager $manager)
    {
        
                $encoder = $this->encode;
                
                
                $user1 = new User();
                $user1->setFirstName("Fablab"); 
                $user1->setLastName("Strasbourg"); 
                $user1->setPhoneNum("+33 3 88 10 35 60");
                $user1->setRoles(["ROLE_FABLAB"]);                
                $user1->setEmail("Fablab@cesi.fr");          
                $user1->setStreet("2 Allée des Foulons");                
                $user1->setPostalCode("67380");                
                $user1->setCountry("France");
                $user1->setPassword($encoder->encodePassword($user1, "FabLab67"));
                $user2 = new User();
                $user2->setFirstName("Samil"); 
                $user2->setLastName("Erdogan"); 
                $user2->setPhoneNum("+33 7 83 65 06 61");
                $user2->setRoles(["ROLE_USER"]);                   
                $user2->setEmail("samil.erdogan@viacesi.fr");            
                $user2->setStreet("1 rue de l'été");                
                $user2->setPostalCode("68110");                
                $user2->setCountry("France");
                $user2->setPassword($encoder->encodePassword($user2, "Illzach68"));
                
                
                
                $product1 = new Product();
                $product1->setProductName("Porte clé COVID ");
                $product1->setDescription("Un porte clé COVID imprimé en 3D grâce aux imprimantes du FABLAB du CESI Strasbourg. Ce porte clé vous servira à vous protéger et ouvrir des portes sans craintes, de même qu'ouvrir des tiroirs, sans avoir peur de toucher des zones sensiblement infectés.");
                $product1->setPrice(3);
                $product1->setImage("eda79486d6119e885fdf792e6d0dc8af.png");
                $product1->setStock(30);
                $product2 = new Product();
                $product2->setProductName("Visière de protection COVID");
                $product2->setDescription("Une visière de protection COVID imprimé en 3D grâce aux imprimantes du FABLAB du CESI Strasbourg. Les visières sont très demandés dans les commerces et hopitaux, mais sont aussi disponibles pour toutes personnes voulant se protéger ou ayant beaucoup de contact avec des personnes.");
                $product2->setPrice(1);
                $product2->setImage("98e9f6c5231bbafb80bb00fc211937ef.jpeg");
                $product2->setStock(50);



                $project1 = new Project();
                $project1->setName("Construction d'un robot !");
                $project1->setDescription("Au fablab nous nous amusons a construire des robots des fois, oui oui, des robots !");
                $images1= [];
                array_push($images1, "70b00bf036a8b9642c6f15f1a269fb8c.jpeg");
                array_push($images1, "fc1eedac89cc8c99de604751a8a97ad6.jpg");
                $project1->setImages($images1);
                $project2 = new Project();
                $project2->setName("Production de visière de protection.");
                $project2->setDescription("Nous produisons des visières de protection pour l'initiative Solidarity-bond, projet mis en place par le CESI de strasbourg.");
                $images2= [];
                array_push($images2, "fg581fac89cc8c99de604751a8a97ad6.jpg");
                array_push($images2, "70bhj55kl6a8b9642c6f15f1a269fb8c.jpeg");
                array_push($images2, "fg581fac89cc8c99de60h45rts8a97ad6.jpg");
                array_push($images2, "70bhj55kl6a8b9642c6f15f1g25erb8c.jpeg");
                $project2->setImages($images2);




                $comment1 = new Comment();
                $comment1->setIdUser($user1);
                $comment1->setIdProject($project1);
                $comment1->setComment("Nous y mettons beaucoup d'efforts !");
                $comment1->addLike($user2);
                $comment2 = new Comment();
                $comment2->setIdUser($user2);
                $comment2->setIdProject($project1);
                $comment2->setComment("j'adore les robots ! continuez comme ça!");
                $comment2->addLike($user1);
                $project1->addComment($comment1);
                $project1->addComment($comment2);
                $project1->addLike($user1);
                $project1->addLike($user2);

                $comment3 = new Comment();
                $comment3->setIdUser($user2);
                $comment3->setIdProject($project2);
                $comment3->setComment("J'espère que ça prends pas trop de temps a produire!");
                $comment3->addLike($user1);
                $comment4 = new Comment();
                $comment4->setIdUser($user1);
                $comment4->setIdProject($project2);
                $comment4->setComment("Si on les empile ça va! et puis bon c'est pour solidarity-bond!");
                $comment4->addLike($user2);
                $comment4->addLike($user1);
                $project2->addComment($comment3);
                $project2->addComment($comment4);
                $project2->addLike($user2);

                $machine1 = new Machine();
                $machine1->setName("Imprimante 3D n°1");
                $machine2 = new Machine();
                $machine2->setName("Imprimante 3D n°2");
                $machine3 = new Machine();
                $machine3->setName("Decoupeuse laser");
                $machine4 = new Machine();
                $machine4->setName("Fraiseuse");

                $manager->persist($user1);
                $manager->persist($user2);
                $manager->persist($product1);
                $manager->persist($product2);
                $manager->persist($project1);
                $manager->persist($project2);
                $manager->persist($comment1);
                $manager->persist($comment2);
                $manager->persist($comment3);
                $manager->persist($comment4);
                $manager->persist($machine1);
                $manager->persist($machine2);
                $manager->persist($machine3);
                $manager->persist($machine4);
                $manager->flush();
    }
}





