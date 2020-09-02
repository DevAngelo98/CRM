<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'cost', 'customer_id'];

    public function customer()
    {
        return $this->belongsTo(Order::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class)->withPivot('contract_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getTagsName(){
        $tags = $this->tags;
        if(count($tags)){
            $this->attributes['tagsName'] = '';
            for ($i=0; $i < count($tags); $i++) { 
                if($i==0){
                    $this->attributes['tagsName'] = $this->attributes['tagsName'] . '' .$tags[$i]->name;
                }else{
                    $this->attributes['tagsName'] = $this->attributes['tagsName'] . ', ' .$tags[$i]->name;
                }
            }
        } else{
            $this->attributes['tagsName'] = 'NO TAGS';
        }
    }

    public function getContractName(){
        if($this->customers()->first()){
            $contract = 'Customer: ' . $this->customers->first()->first_name;
            $this->attributes['contractName'] = $contract;
        }else{
            $customerId = $this->customer_id;

            if($customerId){
                $customer = Customer::withTrashed()
                ->where('id', $customerId)
                ->where('deleted_at', '!=', null)
                ->first();

                $this->attributes['contractNameDeleted'] = $customer->first_name;
                $this->attributes['customerIdDeleted'] = $customer->id;
            }
        }
        return $this;
    }

}
