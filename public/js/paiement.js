window.onload = () => {
    // On instancie Stripe et on lui passe notre clé publique
let stripe = Stripe('pk_test_51GzL7lEJkHBBWUtVK0Tyb1K7ycRtUGMy6vCm9rLaDcVTHu5FmExIg95Eq00rI8I7QtQ9zlEKSRVYqQ7TP8uIw33D00TDpCoWca');

// Initialise les éléments du formulaire
let elements = stripe.elements();

// Définit la redirection en cas de succès du paiement
let redirect = 'shop/order_product';


// Récupère l'élément qui contiendra le nom du titulaire de la carte
let cardName = document.getElementById('card-name');


// Récupère l'élément qui contiendra le code postal
let cardPostal = document.getElementById('postal-code');

// Récupère l'élement button
let cardButton = document.getElementById('card-button');

// Récupère l'attribut data-secret du bouton
let clientSecret = cardButton.dataset.secret;

var elementStyles = {
    base: {
        fontWeight: 600,
        fontFamily: 'Roboto, sans-serif',
        '::placeholder': {
            color: '#989b9e',
        },
        ':focus::placeholder': {
            color: '#b5b5b5',
        },
    }
  };

  var elementClasses = {
    focus: 'focus',
    empty: 'empty',
  };

// Crée les éléments de carte et les stocke dans la variable card

  let cardNumber = elements.create('cardNumber', {
    style: elementStyles,
    classes: elementClasses,
  });
  cardNumber.mount('#card-number');

  let cardExpiry = elements.create('cardExpiry', {
    style: elementStyles,
    classes: elementClasses,
  });
  cardExpiry.mount('#card-expiry');

  let cardCvc = elements.create('cardCvc', {
    style: elementStyles,
    classes: elementClasses,
  });
  cardCvc.mount('#card-cvc');


cardNumber.addEventListener('change', function(event) {
    // On récupère l'élément qui permet d'afficher les erreurs de saisie
    let displayError = document.getElementById('card-errors');

    // Si il y a une erreur
    if (event.error) {
        // On l'affiche
        displayError.textContent = event.error.message;
    } else {
        // Sinon on l'efface
        displayError.textContent = '';
    }
});

cardExpiry.addEventListener('change', function(event) {
    // On récupère l'élément qui permet d'afficher les erreurs de saisie
    let displayError = document.getElementById('card-errors');

    // Si il y a une erreur
    if (event.error) {
        // On l'affiche
        displayError.textContent = event.error.message;
    } else {
        // Sinon on l'efface
        displayError.textContent = '';
    }
});

cardCvc.addEventListener('change', function(event) { 
    // On récupère l'élément qui permet d'afficher les erreurs de saisie
    let displayError = document.getElementById('card-errors');

    // Si il y a une erreur
    if (event.error) {
        // On l'affiche
        displayError.textContent = event.error.message;
    } else {
        // Sinon on l'efface
        displayError.textContent = '';
    }
});

cardButton.addEventListener('click', () => {
    // On envoie la promesse contenant le code de l'intention, l'objet "card" contenant les informations de carte et le nom du client
    stripe.handleCardPayment(
        clientSecret, cardNumber, {
            payment_method_data: {
                billing_details: {
                name: cardName.value,
                address: {
                    postal_code: cardPostal.value
                }}
            }
        }
    ).then(function(result) {
        // Quand on reçoit une réponse
        if (result.error) {
            // Si on a une erreur, on l'affiche
            document.getElementById("errors").innerText = result.error.message;
        } else {
            // Sinon on redirige l'utilisateur
            document.location.href = redirect;
        }
    });
});



}