{
    "name": "{{ $o->vendorName }}/{{ $o->packageName }}",
    "description": "{{ $o->packageName }}",
    "keywords": [
        "{{ $o->vendorName }}",
        "{{ $o->packageName }}"
    ],
    "homepage": "https://www.yaseen.dev/",
    "license": "MIT",
    "type": "library",
    "autoload": {
        "psr-4": {
            "{{ str_replace('\\', '\\\\', $o->packageNamespace) }}\\": "src"
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "{{ str_replace('\\', '\\\\', $o->packageNamespace) }}\\{{ $o->serviceProviderClassName }}"
            ]
        }
    }
}