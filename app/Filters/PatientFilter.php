<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class PatientFilter extends BaseFilter
{
    public function apply(Builder $query): Builder
    {
        // Filter by name (partial match)
        if ($name = $this->request->get('name')) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        // Filter by email (partial match, through user relation)
        if ($email = $this->request->get('email')) {
            $query->whereHas('user', function ($q) use ($email) {
                $q->where('email', 'like', '%' . $email . '%');
            });
        }

        // Filter by phone number
        if ($phone = $this->request->get('phone')) {
            $query->where('phone', 'like', '%' . $phone . '%');
        }

        return $query;
    }
}
