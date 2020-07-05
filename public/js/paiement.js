window.onload = () => {
    // We instantiate Stripe and hand him our public key...
let stripe = Stripe('pk_test_51GzL7lEJkHBBWUtVK0Tyb1K7ycRtUGMy6vCm9rLaDcVTHu5FmExIg95Eq00rI8I7QtQ9zlEKSRVYqQ7TP8uIw33D00TDpCoWca');

// Initializes the elements of the form
let elements = stripe.elements();

// Defines the redirection in case of successful payment
let redirect = 'shop/order_product';


// Retrieves the element that will contain the cardholder's name
let cardName = document.getElementById('card-name');


// Retrieves the element that will contain the postal code
let cardPostal = document.getElementById('postal-code');

// Retrieves the button element
let cardButton = document.getElementById('card-button');

// Retrieves the data-secret attribute of the button
let clientSecret = cardButton.dataset.secret;

//
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

// Creates the credit card elements

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
    // We retrieve the element that displays the input errors
    let displayError = document.getElementById('card-errors');

    // If there's a mistake
    if (event.error) {
        // Let's put it up
        displayError.textContent = event.error.message;
    } else {
        // Otherwise it's erased.
        displayError.textContent = '';
    }
});

cardExpiry.addEventListener('change', function(event) {
    // We retrieve the element that displays the input errors
    let displayError = document.getElementById('card-errors');

    // If there's a mistake
    if (event.error) {
        // Let's put it up
        displayError.textContent = event.error.message;
    } else {
        // Otherwise it's erased.
        displayError.textContent = '';
    }
});

cardCvc.addEventListener('change', function(event) { 
    // We retrieve the element that displays the input errors
    let displayError = document.getElementById('card-errors');

    // If there's a mistake
    if (event.error) {
        // Let's put it up
        displayError.textContent = event.error.message;
    } else {
        // Otherwise it's erased.
        displayError.textContent = '';
    }
});

cardButton.addEventListener('click', () => {
    // We send the promise containing the code of intent, the object containing the card information and the name of the client.
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
        // When we get an answer
        if (result.error) {
            // If we have an error, we display it.
            document.getElementById("errors").innerText = result.error.message;
        } else {
            // Otherwise the user is redirected
            document.location.href = redirect;
        }
    });
});



}