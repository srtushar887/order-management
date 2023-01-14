@extends('layouts.user')
@section('css')

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


    <style>



        .card {
            max-width: 500px;
            margin: auto;
            color: black;
            border-radius: 20 px;
        }

        p {
            margin: 0px;
        }

        .container .h8 {
            font-size: 30px;
            font-weight: 800;
            text-align: center;
        }

        .btn.btn-primary {
            width: 100%;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
            background-image: linear-gradient(to right, #77A1D3 0%, #79CBCA 51%, #77A1D3 100%);
            border: none;
            transition: 0.5s;
            background-size: 200% auto;

        }


        .btn.btn.btn-primary:hover {
            background-position: right center;
            color: #fff;
            text-decoration: none;
        }



        .btn.btn-primary:hover .fas.fa-arrow-right {
            transform: translate(15px);
            transition: transform 0.2s ease-in;
        }

        .form-control {
            color: white;
            background-color: #223C60;
            border: 2px solid transparent;
            height: 60px;
            padding-left: 20px;
            vertical-align: middle;
        }

        .form-control:focus {
            color: white;
            background-color: #0C4160;
            border: 2px solid #2d4dda;
            box-shadow: none;
        }

        .text {
            font-size: 14px;
            font-weight: 600;
        }

        ::placeholder {
            font-size: 14px;
            font-weight: 600;
        }
    </style>
@endsection
@section('user')

    <div class="row">

        <div class="col-md-6">

            <div class="container p-0">
                <div class="card px-4">
                    <p class="h8 py-3">Payment Details</p>
                    <form class="" action="{{route('user.payment.stripe.submit')}}"   id="payment-form">
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class="d-flex flex-column">
                                <p class="text mb-1">CARD NAME</p>

                                <input
                                    type="text"
                                    class="form-control mb-3"
                                    name="name"
                                    placeholder="Card Name"
                                    autocomplete="off" autofocus
                                />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex flex-column">
                                <p class="text mb-1">Card Number</p>

                                <input
                                    type="tel"
                                    class="form-control mb-3"
                                    name="cardNumber"
                                    placeholder="Valid Card Number"
                                    autocomplete="off"
                                    required autofocus
                                />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex flex-column">
                                <p class="text mb-1">Expiry</p>

                                <input
                                    type="tel"
                                    class="form-control mb-3"
                                    name="cardExpiry"
                                    placeholder="MM / YYYY"
                                    autocomplete="off"
                                    required
                                />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex flex-column">
                                <p class="text mb-1">CVV/CVC</p>

                                <input
                                    type="tel"
                                    class="form-control mb-3 pt-2 "
                                    name="cardCVC"
                                    placeholder="CVC"
                                    autocomplete="off"
                                    required
                                />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="btn btn-primary mb-3">
                                <span class="ps-3" >Pay $243</span>
                                <span class="fas fa-arrow-right"></span>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

        </div>




    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('assets/stripe/payvalid.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/stripe/paymin.js') }}"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script type="text/javascript" src="{{ asset('assets/stripe/payform.js') }}"></script>
    <script type="text/javascript" src="https://rawgit.com/jessepollak/card/master/dist/card.js"></script>
    <script>
        $(document).ready(function () {
            var card = new Card({
                form: '#payment-form',
                container: '.card-wrapper',
                formSelectors: {
                    numberInput: 'input[name="cardNumber"]',
                    expiryInput: 'input[name="cardExpiry"]',
                    cvcInput: 'input[name="cardCVC"]',
                    nameInput: 'input[name="name"]'
                }
            });
        });
    </script>
@endsection


