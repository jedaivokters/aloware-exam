<?php

namespace App\Http\Middleware;

use App\Facility;

use Closure;

class FacilityAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auth = $request->user()->id;

        if (!empty($request->facility_id)) {
            $facility_id = $request->facility_id;
        }

        if (!empty($request->facility_optional_question)) {
            $facility_id = $request->facility_optional_question;
        }

        $user_id = Facility::whereId($facility_id)->value('user_id');

        if ($user_id != $auth) {
            return response()->json([
                'success' => false,
                'message' => 'Not allowed.',
            ], 401);
        }

        return $next($request);
    }
}
