<?php require_once __DIR__ . '/vendor/autoload.php';

function dump($value) {
    echo '<pre>' . print_r($value, true) . '</pre>';
}

$converter = new \MincerTest\Stubs\Converter\BaseConverter();

$model = new \MincerTest\Stubs\Messages\User(
    1,
    'admin@admin.com',
    new DateTime(),
    new \MincerTest\Stubs\Messages\Profile(
        'Admin',
        'Adminov',
        'Adminovich'
    )
);


$model->setComments(new \MincerTest\Stubs\Messages\CommentCollection([
    new \MincerTest\Stubs\Messages\Comment('Hello', 1),
    new \MincerTest\Stubs\Messages\Comment('Hi', 2),
]));

$dynamic = $converter->serialize($model);

dump($dynamic);

$strict = $converter->deserialize($dynamic, \MincerTest\Stubs\Messages\User::class);

dump($strict);
