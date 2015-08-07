<?php

$zre = new ZRayExtension('yiiehttpclient');

$zre->setEnabled();

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
    if ($result instanceof EHttpResponse) {
        $result = $result->getBody();
    }

    $storage['RequestsYiiEHttp'][] = array(
        'method' => $context['functionArgs'][0],
        'url' => $uri,
//        'headers' => json_encode($client->getRequest()->getHeaders()->toArray()),
        'params' => ($params),
        'responseRawBody' => $result,
//        'responseCode' => $statusCode,
        'duration' => $context['durationInclusive']

    );
});