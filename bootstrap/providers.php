<?php

return [
    App\Providers\AppServiceProvider::class,
    ...(! in_array(env('APP_ENV'), ['production'], true)
        ? [App\Providers\TelescopeServiceProvider::class]
        : []),
];
