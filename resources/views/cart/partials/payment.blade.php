<payment-form inline-template v-cloak>
    <div class="app">
        <div class="payment-tabs__payment-form-container">
            <b-form-radio-group>
                <div role="tablist" class="payment-tabs__payment-form-container__tab-list">
                    <b-card no-body class="mb-1">
                        <b-card-header header-tag="header" class="p-1" role="tab">
                            <b-row>
                                <span v-b-toggle.credit-card class="clickable "><p style="font-size: 21px">Carta di credito</p></span>
                                {{--<b-form-radio value="credit-card" checked>

                                </b-form-radio>--}}
                            </b-row>
                            <b-row>
                                <b-col cols="8">
                                    <p>Trasferimento sicuro di denaro con il tuo conto bancario
                                        VISA, Mastercard, Discover, Amex</p>
                                </b-col>
                                <b-col cols="4">
                                    <img src="{{resource_path('assets/images/credit-card.svg')}}" alt="">
                                </b-col>
                            </b-row>
                        </b-card-header>
                        <b-collapse id="credit-card" visible accordion="accordion" role="tabpanel">
                            <b-card-body>
                                <b-row>
                                    <b-col cols="6">
                                        <b-form-group>
                                            <label for="" class="text-uppercase">nome intestatario</label>
                                            <b-form-input
                                                    class="form-input m-t-10"
                                                    type="text"
                                                    placeholder="Intestatario della carta">
                                            </b-form-input>
                                        </b-form-group>
                                        <b-form-group class="m-t-15">
                                            <label for="" class="text-uppercase">numero carta</label>
                                            <div id="card-element">
                                                <!-- A Stripe Element will be inserted here. -->
                                            </div>
                                            <div id="card-errors" role="alert"></div>
                                        </b-form-group>

                                    </b-col>
                                    <b-col cols="6" class="aligned-items-bottom text-center m-b-30">
                                        <button @click="triggerSpinner()" type="submit" class="orange-gradient-button">continua</button>
                                    </b-col>
                                </b-row>
                            </b-card-body>
                        </b-collapse>
                    </b-card>
                </div>
            </b-form-radio-group>
        </div>
    </div>
</payment-form>

@section('scripts')

    <style>
        .StripeElement {
            display: block;
            width: 100%;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            line-height: 1.6;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #17BEBB;
            border-radius: 0.25rem;
        }
    </style>

    <script src="https://js.stripe.com/v3/"></script>

    <script>

        var stripe = Stripe('pk_test_WAsPLX3jG4F2dtXJb3cFetm0');
        var elements = stripe.elements();

        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Montserrat", sans-serif',
                fontSmoothing: 'antialiased',
                '::placeholder': {
                    color: '#aab7c4'
                },
                ':focus': {},
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        card.addEventListener('change', function (event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    // Inform the customer that there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            //App.blockUI("Stiamo validando il tuo pagamento");
            form.submit();
        }
    </script>
@endsection

