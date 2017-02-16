<?php

require '../vendor/autoload.php';

$app       = new \Slim\App;
$container = $app->getContainer();

$container['res']                  = __DIR__ . "/../resources";
$container['ad_conn']              = function ($c) {
    $connectionParams = [
        'url' => "sqlite3:///{$c['res']}/data.db",
    ];

    return \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
};
$container['validator']            = function () {
    return \Symfony\Component\Validator\Validation::createValidator();
};
$container['ad_repo']              = function ($c) {
    return new \TnTest\Persistance\SqliteAdRepository($c['ad_conn']);
};
$container['automod_history_repo'] = function ($c) {
    return new \TnTest\Persistance\SqliteAutomodHistoryRepository($c['ad_conn']);
};
$container['automod_service']      = function ($c) {
    return new \TnTest\Domain\AutomodService(
        new \TnTest\App\MockSourceFactory(
            "{$c["res"]}/phone_blacklist.txt",
            "{$c["res"]}/stopwords.txt",
            $c['ad_repo']
        ),
        new \TnTest\Util\TransactionContext($c['ad_conn']),
        $c['ad_repo'],
        $c['automod_history_repo']);
};

$app->get('/',
          new \TnTest\View\IndexAction("{$container["res"]}/index.html"));

$app->post('/',
           new \TnTest\View\PostAction($container['validator'],
                                       $container['ad_repo'],
                                       $container['automod_service']));

$app->run();