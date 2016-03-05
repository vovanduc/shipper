<?php

namespace App\Http\Repositories;

interface BaseInterface {

	public function all($paginate);

	public function firstOrFail($id);

	public function findBy($field, $value);

	public function add($input);

	public function update($id, $input);

	public function delete($id);
}
