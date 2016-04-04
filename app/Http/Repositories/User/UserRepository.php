<?php

namespace App\Http\Repositories\User;

use App\Http\Models\Admin\User;

class UserRepository implements IUserRepository
{
    public function all($paginate)
    {
        return User::where('deleted', 0)->orderBy('id', 'DESC')->paginate($paginate);
    }

    public function firstOrFail($id)
    {
        return User::where('uuid', $id)->firstOrFail();
    }

    public function edit($id)
    {
        $result = \Package::where('uuid', $id)->where('deleted', 0)->firstOrFail();
        return $result;
    }

    public function findBy($field, $value)
    {
        return User::where($field, $value);
    }

    public function add($input)
    {
        $input = array_add($input, 'uuid', \Uuid::generate(4)->string);
        return User::create($input);
    }

    public function update($id, $input)
    {
        return User::where('uuid', $id)->update($input);
    }

    public function delete($id)
    {
        return User::where('uuid', $id)->delete();
    }
}
