<?php

namespace App\Services\Guards;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VkUserGuard {
    public function __invoke(Request $request) {
        $params = $this->validate($request);

        $this->checkSign($params);

        return $this->getUser($params);
    }

    private function validate($request) {
        $params = $request->header('vk-params');
        if (!$params) {
            abort(403, 'required Vk-Params header');
        }

        $params = json_decode(base64_decode($params), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            abort(403, 'invalid json');
        }

        Validator::make($params, [
            'vk_user_id' => 'required|integer',
            'utc_offset' => 'required|integer',
            'vk_are_notifications_enabled' => 'required|boolean',
            'sign' => 'required|string'
        ])->validate();

        return $params;
    }

    private function collectUsefulParams($params) {
        return collect($params)->map(function($param) {
            return $param ?? '';
        })->filter(function($param, $key) {
            return Str::startsWith($key, 'vk_');
        })->sortKeys();
    }

    private function checkSign($params) {
        if (config('app.env') === 'local') {
            return;
        }

        $usefulParams = $this->collectUsefulParams($params);

        /* Формируем строку вида "param_name1=value&param_name2=value"*/
        $sign_params_query = http_build_query($usefulParams->toArray());
        /* Получаем хеш-код от строки, используя защищеный ключ приложения. Генерация на основе метода HMAC. */
        $sign = rtrim(strtr(base64_encode(hash_hmac(
            'sha256', $sign_params_query, config('services.vk.app.secret'), true
        )), '+/', '-_'), '=');

        if (!($sign === $params['sign'])) {
            abort(403, 'Bad sign');
        }
    }


    private function getUser($params) {
        return User::updateOrCreate([
            'vk_user_id' => $params['vk_user_id'],
        ], [
            'utc_offset' => $params['utc_offset'],
            'notifications_are_enabled' => $params['vk_are_notifications_enabled'],
            'visited_at' => now()
        ]);
    }
}
