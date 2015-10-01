<?php

$zre = new ZRayExtension('Requests');

$zre->setEnabledAfter('EHttpClient::request');

$zre->traceFunction('EHttpClient::request', function($context, &$storage){}, function($context, &$storage){
    /* @var EHttpClient */
    $httpClient = $context['this'];
    $uri = $httpClient->getUri(true);


    /// all http information is in private properties
    $methodProperty = new ReflectionProperty($httpClient, 'method');
    $methodProperty->setAccessible(true);
    $method = $methodProperty->getValue($httpClient);

    $paramsGetProperty = new ReflectionProperty($httpClient, 'paramsGet');
    $paramsGetProperty->setAccessible(true);
    $paramsPostProperty = new ReflectionProperty($httpClient, 'paramsPost');
    $paramsPostProperty->setAccessible(true);

    $params = $method == 'GET' ? $paramsGetProperty->getValue($httpClient) : $paramsPostProperty->getValue($httpClient);
    $result = $context['returnValue'];
    $body = '{}';
    $headers = array();
    $code = 0;
    if ($result instanceof EHttpResponse) {
        $body = $result->getBody();
        $headers = $result->getHeaders();
        $code = $result->getStatus();
    }

    $jsonResult = json_decode($body);

    $storage['RequestsYiiEHttp'][] = array(
        'method' => $context['functionArgs'][0],
        'url' => $uri,
        'headers' => $headers,
        'params' => ($params),
        'responseRawBody' => $body,
        'responsePayload' => $jsonResult ? $jsonResult : $body,
        'responseCode' => $code,
        'duration' => $context['durationInclusive']

    );
});