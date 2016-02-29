<?php

namespace App\Http\Repositories\User;

use App\Http\Models\Admin\User;

class UserRepository implements IUserRepository
{
    public function all($paginate)
    {
        return User::where('deleted', 0)->paginate($paginate);
    }

    public function firstOrFail($id)
    {
        return User::where('uuid', $id)->firstOrFail();
    }

    public function findBy($field, $value)
    {
        return User::where($field, $value);
    }

    public function add($input)
    {
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