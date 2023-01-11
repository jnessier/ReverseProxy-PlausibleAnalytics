<?php

require_once 'options.php';

$requestUri = str_replace($relativeUri, '', $_SERVER['REQUEST_URI']);
$requestUriPath = parse_url($requestUri, PHP_URL_PATH);

$code = 404;
$content = 'Not found';
$response_headers = [];

if (in_array($requestUriPath, $whitelist)) {

    $code = 403;
    $content = 'Forbidden';

    $url = $backendUrl . $requestUri;

    if (array_key_exists($requestUriPath, $mapping)) {
        $url = $backendUrl . str_replace(
                $requestUriPath,
                $mapping[$requestUriPath],
                $requestUri
            );
    }

    $ch = curl_init($url);

    foreach (getallheaders() as $key => $value) {
        if (!in_array(strtolower($key), ['host', 'accept-encoding', 'x-forwarded-for', 'client-ip'])) {
            $headers[$key] = $key . ': ' . $value;
        }
    }

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (!in_array($ip, $excluded)) {

        $headers['X-Forwarded-For'] = 'X-Forwarded-For: ' . $ip;

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if (strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents('php://input'));
        }

        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        foreach(explode("\r\n", substr($response, 0, $header_size)) as $header) {
            $header = explode(":", $header, 2);
            if (count($header) == 2) {
                $response_headers[strtolower(rtrim($header[0]))] = ltrim($header[1]);
            }
        }

        $content = substr($response, $header_size);

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    }
}

http_response_code($code);

foreach($response_headers as $key => $value) {
    if (in_array($key, ['content-type', 'content-length', 'vary', 'access-control-allow-origin', 'access-control-allow-credentials', 'cache-control', 'application','cross-origin-resource-policy', 'permissions-policy', 'x-content-type-options'])) {
        header($key . ': ' . $value);
    }
}

echo $content;
