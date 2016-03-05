<?php

namespace App\Http\Repositories\Shipper;

use App\Http\Models\Admin\Shipper;

class ShipperRepository implements IShipperRepository
{
    public function all($paginate)
    {
        return Shipper::where('deleted', 0)->orderBy('id', 'DESC')->paginate($paginate);
    }

    public function firstOrFail($id)
    {
        $result = Shipper::where('uuid', $id)->where('deleted', 0)->firstOrFail();
        $result = Shipper::convert($result);
        return $result;
    }

    public function findBy($field, $value)
    {
        return Shipper::where($field, $value);
    }

    public function get()
    {
        return Shipper::get();
    }

    public function add($input)
    {
        $input = array_add($input, 'uuid', \Uuid::generate(4)->string);
        return Shipper::create($input);
    }

    public function update($id, $input)
    {
        return Shipper::where('uuid', $id)->update($input);
    }

    public function delete($id)
    {
        return Shipper::where('uuid', $id)->delete();
    }
}
