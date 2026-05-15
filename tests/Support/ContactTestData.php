<?php

namespace Tests\Support;

trait ContactTestData
{
    protected function validContactPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Maria de Lourdes',
            'email' => 'maria@gmail.com',
            'phone' => '87996236447',
        ], $overrides);
    }
}
