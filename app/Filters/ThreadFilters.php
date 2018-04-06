<?php 

namespace App\Filters;

use App\Models\User;

class ThreadFilters extends Filters
{
    protected $filters = ['by','popular'];

    protected function by($username) {
        $user = User::whereName($username)->firstOrFail();
        $this->builder->whereUserId($user->id);
    }

    protected function popular() {
        $this->builder->orderBy('replies_count', 'desc');
    }
}