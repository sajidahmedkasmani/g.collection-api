<?php
return ['paths'=>['api/*','sanctum/csrf-cookie'],'allowed_methods'=>['GET','POST','OPTIONS'],'allowed_origins'=>array_filter(array_map('trim',explode(',',env('FRONTEND_ORIGINS','https://gcollection.com,https://www.gcollection.com')))),'allowed_origins_patterns'=>[],'allowed_headers'=>['*'],'exposed_headers'=>[],'max_age'=>0,'supports_credentials'=>false];
