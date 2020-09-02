<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Validator;


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
        return view('customers.index',compact('deleteCustomers'))->withCustomers(Customer::paginate(10));
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
    public function store(Request $request)
    {
        $data = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
            'company' => 'required|string',
        ]);
        if ($data->fails()) {
            return redirect()->back()->with('msg', 'Fields not filled in correctly, try again!');
        }else{
            $customer = Customer::create($request->all());
            return redirect()->route('customers.edit', $customer)->withMessage('Customer created successfully.');
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
    public function update(Request $request, Customer $customer)
    {

        $data = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
            'company' => 'required|string',
        ]);
        if ($data->fails()) {
            return redirect()->back()->with('msg', 'Fields not filled in correctly, try again!');
        }else{
            $customer->update($request->all());
            return redirect()->back()->withMessage('Customer updated successfully.');
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
        // Recupero gli ordini collegati ai clienti (contratto) (N to N relationship)
        $orders = $customer->orders()->get();

        if($orders){
            $contractNumber = 0;
            foreach ($orders as $order ) {
                if(count($order->customers()->get())){
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

        if($contractNumber){
            return redirect()->route('customers.index')->withMessage('Customer deleted successfully. (Delete contract n° '.$contractNumber.')');
        }else{
            return redirect()->route('customers.index')->withMessage('Customer deleted successfully. (No orders)');
        }
    }

    public function restore($idc){
        $customer = Customer::withTrashed()
        ->where('id', $idc)
        ->restore();
        $customer = Customer::findOrFail($idc);
        
        return redirect()->route('customers.index')->withMessage('Customer: '.$customer->first_name .' restored successfully');
    }
}
