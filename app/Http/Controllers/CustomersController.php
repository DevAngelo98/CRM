<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomersRequest;
use App\Models\Customer;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deleteCustomers = Customer::onlyTrashed()->get();
        // dd($deleteCustomers);
        return view('customers.index', compact('deleteCustomers'))->withCustomers(Customer::paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.create')->withCustomer(new Customer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomersRequest $request)
    {
        try {
            $request->validated();
            $customer = Customer::create($request->all());
            return redirect()->route('customers.edit', $customer)->withMessage('Customer created successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('msgError', 'Fatal error, contact the administrator');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(CustomersRequest $request, Customer $customer)
    {
        try {
            $request->validated();
            $customer->update($request->all());
            return redirect()->back()->withMessage('Customer updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('msgError', 'Fatal error, contact the administrator');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        try {
            // Recupero gli ordini collegati ai clienti (contratto) (N to N relationship)
            $orders = $customer->orders()->get();

            if ($orders) {
                $contractNumber = 0;
                foreach ($orders as $order) {
                    if (count($order->customers()->get())) {
                        $contractNumber = $order->customers()->first()->pivot->contract_id;

                        # Commento perché è in softdelete
                        // rimuovo il contratto tra ordine e customer dalla tabella pivot
                        // $order->customers()->detach();
                    }

                    # Commento perché è in softdelete
                    // // Controllo se l'ordine ha tags associati
                    // if(count($order->tags()->get())){
                    //     // Dissocio i tags dall'ordine
                    //     $order->tags()->detach();
                    // }
                }
                // Elimino gli ordini associati ai clienti
                $customer->orders()->delete();
            }

            // Elimino il cliente
            $customer->delete();

            if ($contractNumber) {
                return redirect()->route('customers.index')->withMessage('Customer deleted successfully. (Delete contract n° ' . $contractNumber . ')');
            } else {
                return redirect()->route('customers.index')->withMessage('Customer deleted successfully. (No orders)');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('msgError', 'Fatal error, contact the administrator');
        }
    }

    public function restore($idc)
    {
        try {
            $customer = Customer::withTrashed()
                ->where('id', $idc)
                ->restore();
            $customer = Customer::findOrFail($idc);

            return redirect()->route('customers.index')->withMessage('Customer: ' . $customer->first_name . ' restored successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('msgError', 'Fatal error, contact the administrator');
        }
    }
}
