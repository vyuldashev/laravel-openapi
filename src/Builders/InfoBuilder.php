<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Contact;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Info;
use Illuminate\Support\Arr;

class InfoBuilder
{
    public function build(array $config): Info
    {
        $info = Info::create()
            ->title(Arr::get($config, 'title'))
            ->description(Arr::get($config, 'description'))
            ->version(Arr::get($config, 'version'));

        if (Arr::has($config, 'contact') &&
            (
                array_key_exists('name', $config['contact']) ||
                array_key_exists('email', $config['contact']) ||
                array_key_exists('url', $config['contact'])
            )
        ) {
            $info = $info->contact($this->buildContact($config['contact']));
        }

        $extensions = $config['extensions'] ?? [];

        foreach ($extensions as $key => $value) {
            $info->x($key, $value);
        }

        return $info;
    }

    protected function buildContact(array $config): Contact
    {
        return Contact::create()
            ->name(Arr::get($config, 'name'))
            ->email(Arr::get($config, 'email'))
            ->url(Arr::get($config, 'url'));
    }
}
