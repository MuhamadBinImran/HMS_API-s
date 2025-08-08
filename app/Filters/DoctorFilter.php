<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class DoctorFilter
{
    protected Builder $query;
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
        $this->query = $query;

        if ($this->request->filled('name')) {
            $name = $this->request->input('name');
            // Filter doctors by related user name
            $this->query->whereHas('user', function ($q) use ($name) {
                $q->where('name', 'like', "%{$name}%");
            });
        }

        if ($this->request->filled('email')) {
            $email = $this->request->input('email');
            // Filter doctors by related user email
            $this->query->whereHas('user', function ($q) use ($email) {
                $q->where('email', 'like', "%{$email}%");
            });
        }

        if ($this->request->filled('specialization')) {
            $specialization = $this->request->input('specialization');
            $this->query->where('specialization', 'like', "%{$specialization}%");
        }

        if ($this->request->filled('phone')) {
            $phone = $this->request->input('phone');
            $this->query->where('phone', 'like', "%{$phone}%");
        }

        return $this->query;
    }
}
