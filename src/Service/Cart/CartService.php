<?php 

namespace App\Service\Cart;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;

class CartService {

    protected $session;
    protected $prepo;

    public function __construct(SessionInterface $session, ProductRepository $prepo){
        $this->session = $session;
        $this->prepo = $prepo;
    }
    

    public function add(int $id, int $quantity){
        $cart = $this->session->get('cart', []);

        if(!empty($cart[$id])){
            $cart[$id]+= $quantity;
        } else {
            $cart[$id] = $quantity;
        }


        $this->session->set('cart', $cart);
    }

    public function remove(int $id){
        $cart = $this->session->get('cart', []);
        if(!empty($cart[$id])){
            unset($cart[$id]);
        }


        $this->session->set('cart', $cart);
    }

    public function getFullCart() : array {
        $cart = $this->session->get('cart', []);


        $cartWData = [];
        foreach($cart as $id => $quantity){
            $cartWData[] = [
                'product' => $this->prepo->find($id),
                'quantity' => $quantity
            ];
        }
        return $cartWData;
    }
        
    public function getTotal() : float{
        $total = 0;
        foreach($this->getFullCart() as $item){
            $total+= $item['product']->getPrice() * $item['quantity'];
        }
        return $total;
    }

    
}