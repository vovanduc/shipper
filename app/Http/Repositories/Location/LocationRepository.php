<?php

namespace App\Http\Repositories\Location;

use App\Http\Models\Admin\Location;

class LocationRepository implements ILocationRepository
{
    public function all($paginate)
    {
        return Location::where('deleted', 0)->orderBy('id', 'DESC')->paginate($paginate);
    }

    public function firstOrFail($id)
    {
        $result = Location::where('uuid', $id)->where('deleted', 0)->firstOrFail();
        $result = Location::convert($result);
        return $result;
    }

    public function edit($id)
    {
        $result = Location::where('uuid', $id)->where('deleted', 0)->firstOrFail();
        return $result;
    }

    public function findBy($field, $value)
    {
        return Location::where($field, $value);
    }

    public function get()
    {
        return Location::get();
    }

    public function add($input)
    {
        $input = array_add($input, 'uuid', \Uuid::generate(4)->string);
        return Location::create($input);
    }

    public function update($id, $input)
    {
        return Location::where('uuid', $id)->update($input);
    }

    public function delete($id)
    {
        return Location::where('uuid', $id)->delete();
    }
}
