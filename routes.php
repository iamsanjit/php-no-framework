<?php declare(strict_types=1);

return [
    [
        'GET',
        '/',
        'App\FrontPage\Presentation\FrontPageController#show'
    ],
    [
        'GET',
        '/submit',
        'App\Submissions\Presentation\SubmissionsController#create'
    ],
    [
        'POST',
        '/submit',
        'App\Submissions\Presentation\SubmissionsController#store'
    ]
];
