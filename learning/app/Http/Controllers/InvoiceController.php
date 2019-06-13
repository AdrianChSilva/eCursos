<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class InvoiceController extends Controller
{
    public function admin () {
        $invoices = new Collection;
        if (auth()->user()->stripe_id) {
            $invoices = auth()->user()->invoices();
        }
        return view('invoices.admin', compact('invoices'));
    }

    public function download ($id) {
        return request()->user()->downloadInvoice($id, [
            "vendor" => "Aperture Science S.L",
            "product" => __("Suscripción")
        ]);
    }
}
