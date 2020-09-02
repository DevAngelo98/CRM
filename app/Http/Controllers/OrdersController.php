<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Tag;
use Validator;

class OrdersController extends Controller
{
    public function index(){
        $orders = Order::paginate(10);
        $deleteOrders = Order::onlyTrashed()->get();
        
        foreach ($orders as $order) {
            $order->getTagsName();
            $order->getContractName();

        }

        foreach ($deleteOrders as $deleteOrder) {
            $deleteOrder->getTagsName();
            $deleteOrder->getContractName();
        }

        return view('orders.index', compact('orders','deleteOrders'));
    }

    public function create()
    {
        $customers = Customer::all();
        $tags = Tag::all();
        return view('orders.create', compact('customers','tags'))->withOrder(new Order);; 
    }

    public function store(Request $request)
    {
        try {
            $data = Validator::make($request->all(), [
                'title' => 'required|string',
                'description' => 'required|string',
                'cost' => 'required|numeric',
                'customer_id' => 'required|numeric',
                'tags.*' => 'numeric',
            ]);
            if ($data->fails()) {
                return redirect()->back()->with('msg', 'Fields not filled in correctly, try again!');
            }else{
                $order = Order::create($request->all());
                $order -> customers() -> attach([$request->customer_id]);
                $tags = $request->tags;
                if($tags && count($tags)){
                    foreach ($tags as $value) {
                        $order -> tags() -> attach([$value]);
                    }
                }
                return redirect()->route('order.edit', $order->id)->withMessage('Order created successfully.');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('msg', 'Fatal error, contact the administrator');
        }
    }

    public function edit($ido){
        $order = Order::findOrFail($ido);
        $tags = Tag::all();
        $selectedTags = $order->tags;
        $customers = Customer::all();
        return view('orders.edit', compact('order','tags','selectedTags','customers')); 
    }

    public function update(Request $request, $ido){
        try {
            $data = Validator::make($request->all(), [
                'title' => 'required|string',
                'description' => 'required|string',
                'cost' => 'required|numeric',
                'customer_id' => 'required|numeric',
                'tags.*' => 'numeric',
            ]);
            if ($data->fails()) {
                return redirect()->back()->with('msg', 'Fields not filled in correctly, try again!');
            }else{
                $order = Order::findOrFail($ido);

                // Se cambio cliente associato all'ordine elimino il contratto e lo creo associato al nuovo cliente
                if($order->customer_id != $request->customer_id){
                    $order->customers()->detach();
                    $order->customers()->attach([$request->customer_id]);
                }

                // POSSIBILE OTTIMIZZAZIONE CONTROLLO SE GIÀ PRESENTE UN VECCHIO TAG
                $tags = $request->tags;
                if($tags && count($tags)){
                    $order->tags()->detach();                    
                    foreach ($tags as $value) {
                        $order -> tags() -> attach([$value]);
                    }
                }

                $order->update($request->all());

                return redirect()->back()->withMessage('Order updated successfully.');;
            }

        } catch (\Throwable $th) {
            return redirect()->back()->with('msg', 'Fatal error, contact the administrator');
        }
    }

    public function delete($ido)
    {
        $order = Order::findOrFail($ido);
        // if(count($order->customers()->get())){
        //     $contractNumber = $order->customers()->first()->pivot->contract_id;

        //     # Commento perché è in softdelete
        //     # rimuovo il contratto tra ordine e customer dalla tabella pivot
        //     # $order->customers()->detach();
        // }

        # Commento perché è in softdelete
        # Controllo se l'ordine ha tags associati
        # if(count($order->tags()->get())){
        #     Dissocio i tags dall'ordine
        #     $order->tags()->detach();
        #}

        $order->delete();

        return redirect()->route('orders.index')->withMessage('Order deleted successfully');
    }
    
    public function restore($ido){
        $order = Order::withTrashed()
        ->where('id', $ido)
        ->restore();
        $order = Order::findOrFail($ido);
        
        return redirect()->route('orders.index')->withMessage('Order: '.$order->title .' restored successfully');
    }
}
