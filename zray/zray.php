<?php

$zre = new ZRayExtension('yiiehttpclient');

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
    if ($result instanceof EHttpResponse) {
        $body = $result->getBody();
    }

    $jsonResult = json_decode($body);

    $storage['RequestsYiiEHttp'][] = array(
        'method' => $context['functionArgs'][0],
        'url' => $uri,
        'headers' => $result->getHeaders(),
        'params' => ($params),
        'responseRawBody' => $body,
        'responsePayload' => $jsonResult ? $jsonResult : $body,
        'responseCode' => $result->getStatus(),
        'duration' => $context['durationInclusive']

    );
});