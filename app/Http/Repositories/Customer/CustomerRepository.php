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
        $result = Customer::where('uuid', $id)->where('deleted', 0)->firstOrFail();
        $result = Customer::convert($result);
        return $result;
    }

    public function edit($id)
    {
        $result = \Package::where('uuid', $id)->where('deleted', 0)->firstOrFail();
        return $result;
    }

    public function findBy($field, $value)
    {
        return Customer::where($field, $value);
    }

    public function get()
    {
        return Customer::get();
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
