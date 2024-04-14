{
    "name": "{{ $o->vendorName }}/{{ $o->packageName }}",
    "description": "{{ $o->packageName }}",
    "keywords": [
        "{{ $o->vendorName }}",
        "{{ $o->packageName }}"
    ],
    "homepage": "https://github.com/{{ $o->vendorName }}/{{ $o->packageName }}",
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
                "{{ str_replace('\\', '\\\\', $o->packageNamespace) }}\\{{ $o->moduleName }}ServiceProvider"
            ]
        }
    }
}