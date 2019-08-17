<?php
require __DIR__.'/vendor/autoload.php';

$rc = new RedisCluster(NULL, Array("redis-cluster:7000"), 1.5, 1.5);
$ra = new RedisArray(array("redis0", "redis1", "redis2"));

$length = 1000;

for($i = 0; $i < 10; ++$i) {
    $ra->setOption(Redis::OPT_PREFIX, 'response-cache:');
    $ra->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_MSGPACK);
    $rc->setOption(Redis::OPT_PREFIX, 'response-cache:');
    $rc->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_MSGPACK);

    $ra->get("user1:name");
    $ra->setex("user1:name", 900, substr(bin2hex(random_bytes($length)), 0, $length));
    $rc->get("user1:name");
    $rc->setex("user1:name", 900, substr(bin2hex(random_bytes($length)), 0, $length));

    $ra->setOption(Redis::OPT_PREFIX, 'test:');
    $ra->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY);
    $rc->setOption(Redis::OPT_PREFIX, 'test:');
    $rc->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY);
}