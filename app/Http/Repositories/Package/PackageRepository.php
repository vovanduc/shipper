<?php

namespace App\Http\Repositories\Package;

class PackageRepository implements IPackageRepository
{
    public function all($paginate)
    {
        return \Package::where('deleted', 0)->orderBy('id', 'DESC')->paginate($paginate);
    }

    public function firstOrFail($id)
    {
        $result = \Package::where('uuid', $id)->where('deleted', 0)->firstOrFail();
        $result = \Package::convert($result);
        return $result;
    }

    public function edit($id)
    {
        $result = \Package::where('uuid', $id)->where('deleted', 0)->firstOrFail();
        return $result;
    }

    public function findBy($field, $value)
    {
        return \Package::where($field, $value);
    }

    public function get()
    {
        return \Package::get();
    }

    public function add($input)
    {
        $input = array_add($input, 'uuid', \Uuid::generate(4)->string);
        return \Package::create($input);
    }

    public function update($id, $input)
    {
        return \Package::where('uuid', $id)->update($input);
    }

    public function delete($id)
    {
        return \Package::where('uuid', $id)->delete();
    }
}