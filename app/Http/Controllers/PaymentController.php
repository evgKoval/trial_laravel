<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\MadeOrders;
use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
/** All Paypal Details class **/
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Redirect;
use Session;
use URL;
use Auth;
use FedEx\RateService\Request as FedExRequest;
use FedEx\RateService\ComplexType;
use FedEx\RateService\SimpleType;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;

class PaymentController extends Controller
{
    public function __construct()
    {
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function index()
    {
        $user = Auth::user();

        $orders = Order::leftJoin('products', 'orders.product_id', '=', 'products.id')->where('user_id', Auth::user()->id)->get();
        
        $amount = 0;

        foreach ($orders as $order) {            
            $amount += $order->price;
        }

        return view('checkout', compact('amount', 'user'));
    }

    public function payWithpaypal(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'country' => 'required',
            'code' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'adress' => 'required',
            'amount' => 'required'
        ]);

        $orders = Order::leftJoin('products', 'orders.product_id', '=', 'products.id')->where('user_id', Auth::user()->id)->get();
        
        $amount = 0;

        foreach ($orders as $order) {  
            $amount += $order->price;
        }

        $madeOrder = MadeOrders::add([
            'user_id' => Auth::user()->id,
            'name' => $request->input('name'), 
            'email' => $request->input('name'), 
            'country' => $request->input('country'), 
            'state_province' => $request->input('code'), 
            'city' => $request->input('city'), 
            'zip' => $request->input('zip'), 
            'adress' => $request->input('adress'), 
            'phone' => $request->input('phone') || '', 
            'paid' => $amount
        ]);

        // $distanceSF = \DB::select(\DB::raw('
        //     select ST_Distance_Sphere(point(:lonA, :latA), point(:lonB,:latB)) * 0.00621371192
        // '), [
        //     'lonA' => -122.447836,
        //     'latA' => 37.783401,
        //     'lonB' => $result->first()->getCoordinates()->getLongitude(),
        //     'latB' => $result->first()->getCoordinates()->getLatitude(),
        // ]);

        // $distanceOG = \DB::select(\DB::raw('
        //     select ST_Distance_Sphere(point(:lonA, :latA), point(:lonB,:latB)) * 0.00621371192
        // '), [
        //     'lonA' => -82.956527,
        //     'latA' => 39.948801,
        //     'lonB' => $result->first()->getCoordinates()->getLongitude(),
        //     'latB' => $result->first()->getCoordinates()->getLatitude(),
        // ]);

        // $milesFromSF = array_values((array) $distanceSF[0])[0];
        // $milesFromOG = array_values((array) $distanceOG[0])[0];

        // if($milesFromSF < $milesFromOG) {
        //     dd('Your purchase will come from San Francisco');
        // } else {
        //     dd('Your purchase will come from Ogayo');
        // }

        // dd($milesFromSF, $milesFromOG);

        $rateRequest = new ComplexType\RateRequest();

        //authentication & client details
        $rateRequest->WebAuthenticationDetail->UserCredential->Key = 'bo3TugnNqNmgz64z';
        $rateRequest->WebAuthenticationDetail->UserCredential->Password = 'LNmg1kKeQdOsb923ZflXMVqFH';
        $rateRequest->ClientDetail->AccountNumber = 510087500;
        $rateRequest->ClientDetail->MeterNumber = 114018013;

        $rateRequest->TransactionDetail->CustomerTransactionId = 'testing rate service request';

        $rateRequest->Version->ServiceId = 'crs';
        $rateRequest->Version->Major = 24;
        $rateRequest->Version->Minor = 0;
        $rateRequest->Version->Intermediate = 0;

        $rateRequest->ReturnTransitAndCommit = true;

        $rateRequest->RequestedShipment->PreferredCurrency = 'USD';
        $rateRequest->RequestedShipment->Shipper->Address->StreetLines = ['10 Fed Ex Pkwy'];
        $rateRequest->RequestedShipment->Shipper->Address->City = 'Memphis';
        $rateRequest->RequestedShipment->Shipper->Address->StateOrProvinceCode = 'TN';
        $rateRequest->RequestedShipment->Shipper->Address->PostalCode = 38115;
        $rateRequest->RequestedShipment->Shipper->Address->CountryCode = 'US';

        //recipient
        $rateRequest->RequestedShipment->Recipient->Address->StreetLines = [$request->input('adress')];
        $rateRequest->RequestedShipment->Recipient->Address->City = $request->input('city');
        $rateRequest->RequestedShipment->Recipient->Address->StateOrProvinceCode = $request->input('code');
        $rateRequest->RequestedShipment->Recipient->Address->PostalCode = $request->input('zip');
        $rateRequest->RequestedShipment->Recipient->Address->CountryCode = $request->input('country');

        $rateRequest->RequestedShipment->ShippingChargesPayment->PaymentType = SimpleType\PaymentType::_SENDER;

        $rateRequest->RequestedShipment->RateRequestTypes = [SimpleType\RateRequestType::_PREFERRED, SimpleType\RateRequestType::_LIST];

        $rateRequest->RequestedShipment->PackageCount = count($orders);

        $rateRequest->RequestedShipment->RequestedPackageLineItems = [];
        for ($i = 0; $i < count($orders); $i++) {
            $rateRequest->RequestedShipment->RequestedPackageLineItems[] = new ComplexType\RequestedPackageLineItem();
        }

        for ($i = 0; $i < count($orders); $i++) {
            $rateRequest->RequestedShipment->RequestedPackageLineItems[$i]->Weight->Value = 2;
            $rateRequest->RequestedShipment->RequestedPackageLineItems[$i]->Weight->Units = SimpleType\WeightUnits::_LB;
            $rateRequest->RequestedShipment->RequestedPackageLineItems[$i]->Dimensions->Length = 10;
            $rateRequest->RequestedShipment->RequestedPackageLineItems[$i]->Dimensions->Width = 10;
            $rateRequest->RequestedShipment->RequestedPackageLineItems[$i]->Dimensions->Height = 3;
            $rateRequest->RequestedShipment->RequestedPackageLineItems[$i]->Dimensions->Units = SimpleType\LinearUnits::_IN;
            $rateRequest->RequestedShipment->RequestedPackageLineItems[$i]->GroupPackageCount = 1;
        }

        $rateServiceRequest = new FedExRequest();

        $rateReply = $rateServiceRequest->getGetRatesReply($rateRequest, true);

        $shipPrice = $rateReply->RateReplyDetails[5]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetChargeWithDutiesAndTaxes->Amount;

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
 
        $item_1 = new Item();

        $priceWithShip = floatval($shipPrice) + $amount;

        $item_1->setName('Item 1') /** item name **/
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($priceWithShip); /** unit price **/
 
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
 
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($priceWithShip);
 
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Your transaction description');
 
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('status')) /** Specify return URL **/
            ->setCancelUrl(URL::route('status'));
 
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        /** dd($payment->create($this->_api_context));exit; **/
        try {
 
            $payment->create($this->_api_context);
 
        } catch (\PayPal\Exception\PPConnectionException $ex) {
 
            if (\Config::get('app.debug')) {
 
                \Session::put('error', 'Connection timeout');
                return Redirect::route('paywithpaypal');
 
            } else {
 
                \Session::put('error', 'Some error occur, sorry for inconvenient');
                return Redirect::route('paywithpaypal');
 
            }
        }
 
        foreach ($payment->getLinks() as $link) {
 
            if ($link->getRel() == 'approval_url') {
 
                $redirect_url = $link->getHref();
                break;
 
            }
 
        }
 
        /** add payment ID to session **/
        Session::put('paypal_payment_id', $payment->getId());
 
        if (isset($redirect_url)) {
 
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
 
        }
 
        \Session::put('error', 'Unknown error occurred');
        return Redirect::route('paywithpaypal');
    }

    public function getPaymentStatus(Request $request)
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
 
        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        if (empty($request->get('PayerID')) || empty($request->get('token'))) {
 
            \Session::put('error', 'Payment failed');
            return Redirect::route('/');
 
        }
 
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));
 
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
 
        if ($result->getState() == 'approved') {
            $order = MadeOrders::where('user_id', '=', Auth::user()->id)->get()->last();
            $order->status = 1;
            $order->save();

            $orders = Order::leftJoin('products', 'orders.product_id', '=', 'products.id')->where('user_id', Auth::user()->id)->get();

            $geocoder = new \Geocoder\ProviderAggregator();
            $adapter  = new \Http\Adapter\Guzzle6\Client();

            $chain = new \Geocoder\Provider\Chain\Chain([
                new \Geocoder\Provider\BingMaps\BingMaps($adapter, 'Ai4GWZ8bZ6SOGjNoLu6wcxdT5srwatjhkOZQV-UuUUcDZFCmdWhJ89fFu3kAvNdB')
            ]);

            $geocoder->registerProvider($chain);

            $result = $geocoder->geocodeQuery(GeocodeQuery::create($order->adress));

            $productsSession = [];

            foreach ($orders as $order) {  
                $productCoordinates = explode(',', $order->stock);

                $distance = \DB::select(\DB::raw('
                    select ST_Distance_Sphere(point(:lonA, :latA), point(:lonB,:latB)) * 0.00621371192
                '), [
                    'lonA' => $productCoordinates[1],
                    'latA' => $productCoordinates[2],
                    'lonB' => $result->first()->getCoordinates()->getLongitude(),
                    'latB' => $result->first()->getCoordinates()->getLatitude()
                ]);

                $productsSession[] = $order->name . ' will come from ' . $productCoordinates[0] . '. Miles: ' . array_values((array) $distance[0])[0];
            }

            Order::where('user_id', Auth::user()->id)->delete();

            \Session::put('success', 'Payment success');

            //$fromOrders = collect($productsSession);
            \Session::put('cart', $productsSession);
            
            return Redirect::route('cart');
        }
 
        \Session::put('error', 'Payment failed');
        return Redirect::route('/');
 
    }
}
