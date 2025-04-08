<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['message' => '用戶驗證失敗'], 401);
            }
        } catch (TokenBlacklistedException $e) {
            return response()->json(['message' => 'Token 已被列入黑名單'], 401);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Token 無效或未提供'], 401);
        } catch (\Exception $e) {
            return response()->json(['message' => '驗證過程發生錯誤'], 401);
        }

        return $next($request);
    }
}
