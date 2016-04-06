<?php

namespace App\Http\Repositories\Shipment;

class ShipmentRepository implements IShipmentRepository
{
    public function all($paginate)
    {
        return \Shipment::where('deleted', 0)->orderBy('id', 'DESC')->paginate($paginate);
    }

    public function firstOrFail($id)
    {
        $result = \Shipment::where('uuid', $id)->where('deleted', 0)->firstOrFail();
        $result = \Shipment::convert($result);
        return $result;
    }

    public function edit($id)
    {
        $result = \Shipment::where('uuid', $id)->where('deleted', 0)->firstOrFail();
        return $result;
    }

    public function findBy($field, $value)
    {
        return \Shipment::where($field, $value);
    }

    public function get()
    {
        return \Shipment::get();
    }

    public function add($input)
    {
        $input = array_add($input, 'uuid', \Uuid::generate(4)->string);

        return \Shipment::create($input);
    }

    public function update($id, $input)
    {
        return \Shipment::where('uuid', $id)->update($input);
    }

    public function delete($id)
    {
        return \Shipment::where('uuid', $id)->delete();
    }
}
