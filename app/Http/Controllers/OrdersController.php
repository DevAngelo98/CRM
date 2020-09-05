<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Tag;
use App\Http\Requests\OrdersRequest;

class OrdersController extends Controller
{
    public function index()
    {
        try {
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
            return view('orders.index', compact('orders', 'deleteOrders'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('msgError', 'Fatal error, contact the administrator');
        }
    }

    public function create()
    {
        try {
            $customers = Customer::all();
            $tags = Tag::all();
            return view('orders.create', compact('customers', 'tags'))->withOrder(new Order);
        } catch (\Throwable $th) {
            return redirect()->back()->with('msgError', 'Fatal error, contact the administrator');
        }
    }

    public function store(OrdersRequest $request)
    {
        try {
            $request->validated();
            $order = Order::create($request->all());
            $order->customers()->attach([$request->customer_id]);
            $tags = $request->tags;
            if ($tags && count($tags)) {
                foreach ($tags as $value) {
                    $order->tags()->attach([$value]);
                }
            }
            return redirect()->route('order.edit', $order->id)->withMessage('Order created successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('msgError', 'Fatal error, contact the administrator');
        }
    }

    public function edit(Order $order)
    {
        try {
            $tags = Tag::all();
            $selectedTags = $order->tags;
            $customers = Customer::all();
            return view('orders.edit', compact('order', 'tags', 'selectedTags', 'customers'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('msgError', 'Fatal error, contact the administrator');
        }
    }

    public function update(OrdersRequest $request, Order $order)
    {
        try {
            $request->validated();
            // Se cambio cliente associato all'ordine elimino il contratto e lo creo associato al nuovo cliente
            if ($order->customer_id != $request->customer_id) {
                $order->customers()->detach();
                $order->customers()->attach([$request->customer_id]);
            }

            // POSSIBILE OTTIMIZZAZIONE CONTROLLO SE GIÀ PRESENTE UN VECCHIO TAG
            $tags = $request->tags;
            if ($tags && count($tags)) {
                $order->tags()->detach();
                foreach ($tags as $value) {
                    $order->tags()->attach([$value]);
                }
            }

            $order->update($request->all());

            return redirect()->back()->withMessage('Order updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('msgError', 'Fatal error, contact the administrator');
        }
    }

    public function delete(Order $order)
    {
        try {
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
        } catch (\Throwable $th) {
            return redirect()->route('orders.index')->with('msgError', 'Fatal error, contact the administrator');
        }
    }

    public function restore($ido)
    {
        try {
            $order = Order::withTrashed()
                ->where('id', $ido)
                ->restore();
            $order = Order::findOrFail($ido);

            return redirect()->route('orders.index')->withMessage('Order: ' . $order->title . ' restored successfully');
        } catch (\Throwable $th) {
            return redirect()->route('orders.index')->with('msgError', 'Fatal error, contact the administrator');
        }
    }
}
