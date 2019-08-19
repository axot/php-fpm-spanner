<?php
require __DIR__.'/vendor/autoload.php';

$ra = new RedisArray(array("redis0:6379", "redis1:6379", "redis2:6379"));

for($i = 0; $i < 2; ++$i) {
    $ra->setOption(Redis::OPT_PREFIX, 'test:');
    $ra->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_MSGPACK);

    $ra->get("hello");
    $ra->get("world");

    $ra->set("user1:name", 60);
    $ra->set("user2:name", 60);

    $ra->getOption(Redis::OPT_PREFIX);
    $ra->getOption(Redis::OPT_SERIALIZER);
}
