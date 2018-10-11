@extends('layouts.main', ['grey'=>true])
@section('title', 'Carrello')
@section('content')


    <page-clip-loader :loading="true"
                    :color="'#f4812e'"
                    :size="'100px'"
                    :text="'Stiamo validando il tuo pagamento'">
    </page-clip-loader>

    <cart-tabs inline-template v-cloak>
        <form method="POST" data-vv-scope="payment-form" action="{{route('postBuy')}}"
              id="payment-form" class="">
            <div class="card payment-tabs">
                <div id="" class="payment-tabs__card-header tabs"><!---->
                    <div class="card-header">
                        <ul role="tablist" tabindex="0" id="" class="nav nav-tabs card-header-tabs">
                            <li role="presentation" class="nav-item"><a role="tab" data-toggle="tab" href="#tab1"
                                                                        tabindex="1" id="tab1-a"
                                                                        class="nav-link active"><span
                                            class="tab-title">carrello</span></a></li>
                            <li role="presentation" class="nav-item"><a role="tab" data-toggle="tab" href="#tab2"
                                                                        tabindex="2" id="tab2-a"
                                                                        class="nav-link disabled" disabled><span
                                            class="tab-title">dati sportivo</span></a></li>
                            <li role="presentation" class="nav-item"><a role="tab" href="#tab3"
                                                                        data-toggle="tab"
                                                                        id="tab3-a"
                                                                        tabindex="3"
                                                                        class="nav-link disabled" disabled>
                            <span
                                    class="tab-title">dati pagamento</span></a></li>
                        </ul>
                    </div>


                    <div class="tab-content p-t-50 p-b-40">

                        {{csrf_field()}}
                        <div role="tabpanel" id="tab1" aria-hidden="false" aria-expanded="true"
                             class="tab-pane fade show active"
                             style="">
                            @include('cart.partials.cart')
                        </div>
                        <div role="tabpanel" id="tab2" aria-hidden="true" aria-expanded="false"
                             class="tab-pane fade"
                        >
                            @include('cart.partials.sports_man')
                        </div>
                        <div role="tabpanel" id="tab3" aria-hidden="true" aria-expanded="false"
                             class="tab-pane fade"
                        >
                            @include('cart.partials.payment')
                        </div>

                    </div>


                </div>

            </div>
        </form>
    </cart-tabs>
@endsection
