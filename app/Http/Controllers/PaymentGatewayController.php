<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{

    public function generateTokenSnap(Request $request)
    {
        // var_dump(env('ENDPOINT'));die;

        $data = [
            "transaction_details" => [
                "order_id" => 12454509909099090909,
                "gross_amount" => 100000,
            ],
            "enabled_payments" => [
                "gopay", "shopeepay", "permata_va",
                "other_va", "bca_va", "bni_va", "bri_va",
            ],
            "customer_details" => [
                "first_name" => "Abdul Hanan",
                "last_name" => ".",
                "email" => "abdulhanan@email.com",
                "phone" => "02201234567",
            ],
        ];
        $client = new \GuzzleHttp\Client();
        $res = $client->post(
            env('ENDPOINT'),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode('SB-Mid-server-BGlONQoci42aSKbtjtb2y1p_' . ':'),
                ],
                'body' => json_encode($data),
            ]
        );
        return \response()->json(['data' => json_decode($res->getBody())], 200);
    }

    public function notification()
    {

        \Midtrans\Config::$isProduction = true;
        \Midtrans\Config::$serverKey = env('SERVER_KEY');

        $notif = new \Midtrans\Notification();
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;
        $gross_amount = $notif->gross_amount;

        // if()
        // var_dump();die;
        if ($transaction == 'capture') {
            // For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') { // TODO set payment status in merchant's database to 'Challenge by FDS'
                    // TODO merchant should decide whether this transaction is authorized or not in MAP
                    echo "Transaction order_id: " . $order_id . " is challenged by FDS";
                } else { // TODO set payment status in merchant's database to 'Success'
                    echo "Transaction order_id: " . $order_id . " successfully captured using " . $type;}
            }
        } else if ($transaction == 'settlement') {
            // TODO set payment status in merchant's database to 'Settlement'

            // }
            // echo "Transaction order_id: " . $order_id . " successfully transfered using " . $type;
        } else if ($transaction == 'pending') {
            // TODO set payment status in merchant's database to 'Pending'
            echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
        } else if ($transaction == 'deny') { // TODO set payment status in merchant's database to 'Denied'
            echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
        } else if ($transaction == 'expire') {
            echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
        } else if ($transaction == 'cancel') {
            echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";}

    }

}

// if ($request->hasFile('file')) {

//     $image      = $request->file('file');

//     $fileName   = time() . '.' . $image->getClientOriginalExtension();

//     $img = \Image::make($image->getRealPath());
//     $img->resize(120, 120, function ($constraint) {
//         $constraint->aspectRatio();
//     });

//     $img->stream();
//     $path = \Storage::disk('local')->put('images'.'/'.$fileName, $img, 'public');

//     $image->move(public_path().'/images/', $fileName);

//     $file = new FileModel;

//     $file->name = $fileName;
//     $file->path = \public_path().'/images/';
//     $file->full_url = \Config::get('app.url').'/images/'.$fileName;
//     $file->file_id = time();
//     $file->save();

//     return \response()->json(['data' => $file,],200);
