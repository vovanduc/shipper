<?php

namespace App\Http\Repositories\Customer;

use App\Http\Models\Admin\Customer;

class CustomerRepository implements ICustomerRepository
{
    public function all($paginate)
    {
        return Customer::where('deleted', 0)->orderBy('id', 'DESC')->paginate($paginate);
    }

    public function firstOrFail($id)
    {
        return Customer::where('uuid', $id)->firstOrFail();
    }

    public function findBy($field, $value)
    {
        return Customer::where($field, $value);
    }

    public function add($input)
    {
        $input = array_add($input, 'uuid', \Uuid::generate(4)->string);
        return Customer::create($input);
    }

    public function update($id, $input)
    {
        return Customer::where('uuid', $id)->update($input);
    }

    public function delete($id)
    {
        return Customer::where('uuid', $id)->delete();
    }
}
