<?php

namespace App\Modules\Operator\Http\Middleware;

use App\Modules\Operator\Models\Operator;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOperatorAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $operatorId = $request->session()->get('operator_id');

        $operator = $operatorId
            ? Operator::query()
                ->whereKey($operatorId)
                ->where('is_active', true)
                ->first()
            : null;

        if (!$operator) {
            $request->session()->forget('operator_id');

            return redirect()->route('operator.login');
        }

        $request->attributes->set('operator', $operator);

        return $next($request);
    }
}
