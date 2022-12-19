<?php

namespace Database\Seeders;

use App\Models\Access;
use App\Models\AccessSample;
use App\Models\AccessType;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Client::create(['name' => 'ООО ВОТЕЛ']);
        AccessType::create([
            'name' => 'ssh',
            'data' => json_encode([
                ['name' => 'host', 'type' => 'ifstring', 'condition' => '/((\d{1,3}\.){3}\d{1,3}|(\w+\.)*\w):\d{1,5}/'],
                ['name' => 'user', 'type' => 'string'],
                [
                    'name' => 'key',
                    'type' => 'object',
                    'contained' => [
                        ['name' => 'type', 'type' => 'enum', 'variants' => ['rsa', 'id545']],
                        ['name' => 'value', 'type' => 'string']
                    ]
                ]
            ]),
        ]);
        AccessType::create([
            "name" => "web",
            "data" => json_encode([
                [
                    "name" => "url",
                    "type" => "ifstring",
                    "condition" => '/^(https?:\/\/)((\w{2,255}\.)*\w{2,255})([-a-zA-Z0-9@:%_\+.~#?&\/=]*)$/'
                ],
                [
                    "name" => "steps",
                    "type" => "array",
                    "variants" => [
                        [
                            ["name" => "type", "type" => "ifstring", "condition" => '/^fillField$/'],
                            ["name" => "selector", "type" => "string"],
                            ["name" => "value", "type" => "string"]
                        ],
                        [
                            ["name" => "type", "type" => "ifstring", "condition" => '/^click$/'],
                            ["name" => "selector", "type" => "string"]
                        ]
                    ]
                ]
            ])
        ]);
        AccessSample::create([
            'name' => 'server csl',
            'type_id' => 1,
            'data' => json_encode([
                'host' => 'vsl.com:444',
                'user' => '%user%',
                'key' => [
                    'type' => 'rsa',
                    'value' => '%key%'
                ]
            ])
        ]);
        AccessSample::create([
            'name' => 'bilain',
            'type_id' => 2,
            'data' => json_encode([
                'url' => 'https://bilain.com',
                "steps" => [
                    [
                        "type" => "fillField",
                        "value" => "%login%",
                        "selector" => ".login"
                    ],
                    [
                        "type" => "fillField",
                        "value" => "%password%",
                        "selector" => ".pass"
                    ],
                    [
                        "type" => "click",
                        "selector" => ".btn"
                    ]
                ]
            ])
        ]);
        Access::create([
            'name' => 'admin_server',
            'sample_id' => 1,
            'client_id' => 1,
            'data' => json_encode([
                'user' => 'user',
                'key' => '*some key*'
            ])
        ]);
        Access::create([
            'name' => 'bisness-bilain',
            'sample_id' => 2,
            'client_id' => 1,
            'data' => json_encode([
                'login' => 'admin',
                'password' => '1334'
            ])
        ]);
        User::create([
            'name' => 'ilya',
            'email' => 'miassilya5@gmail.com',
            'password' => '$2y$10$VGH//AyboN8/p5oYcPOxruNyOx7dg.yjmpWleErbSujRIKvOPdLuS'
        ]);
    }
}