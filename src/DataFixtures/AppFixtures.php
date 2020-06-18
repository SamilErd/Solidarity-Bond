<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encode;
 
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encode = $encoder;
    }
 


    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 3; $i++) {
            if($i == 1){
                
                $encoder = $this->encode;
                $product = new Product();
                $user = new User();
                $user->setFirstName("Fablab"); 
                $user->setLastName("Strasbourg"); 
                $user->setPhoneNum("+33 3 88 10 35 60");
                $user->setRoles(["ROLE_FABLAB"]);                
                $user->setEmail("Fablab@cesi.fr");          
                $user->setStreet("2 Allée des Foulons");                
                $user->setPostalCode("67380");                
                $user->setCountry("France");
                $user->setPassword($encoder->encodePassword($user, "FabLab67"));
                $product->setProductName("Porte clé COVID ");
                $product->setDescription("Un porte clé COVID imprimé en 3D grâce aux imprimantes du FABLAB du CESI Strasbourg. Ce porte clé vous servira à vous protéger et ouvrir des portes sans craintes, de même qu'ouvrir des tiroirs, sans avoir peur de toucher des zones sensiblement infectés.");
                $product->setPrice(3);
                $product->setImage("eda79486d6119e885fdf792e6d0dc8af.png");
                $product->setStock(30);
                $manager->persist($product);
                $manager->persist($user);
                $manager->flush();
            } else {
                $product = new Product();
                $user = new User();
                $user->setFirstName("Samil"); 
                $user->setLastName("Erdogan"); 
                $user->setPhoneNum("+33 7 83 65 06 61");
                $user->setRoles(["ROLE_USER"]);                   
                $user->setEmail("samil.erdohan@viacesi.fr");            
                $user->setStreet("1 rue de l'été");                
                $user->setPostalCode("68110");                
                $user->setCountry("France");
                $user->setPassword($encoder->encodePassword($user, "Illzach68"));
                $product->setProductName("Visière de protection COVID");
                $product->setDescription("Une visière de protection COVID imprimé en 3D grâce aux imprimantes du FABLAB du CESI Strasbourg. Les visières sont très demandés dans les commerces et hopitaux, mais sont aussi disponibles pour toutes personnes voulant se protéger ou ayant beaucoup de contact avec des personnes.");
                $product->setPrice(1);
                $product->setImage("98e9f6c5231bbafb80bb00fc211937ef.jpeg");
                $product->setStock(50);
                $manager->persist($product);
                $manager->persist($user);
                $manager->flush();
            }
            
        }

        $manager->flush();
    }
}





