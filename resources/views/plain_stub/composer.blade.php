{
    "name": "{vendorName}/{packageName}",
    "description": "{packageName}",
    "keywords": [
        "{vendorName}",
        "{packageName}"
    ],
    "homepage": "https://github.com/{vendorName}/{packageName}",
    "license": "MIT",
    "type": "library",
    "autoload": {
        "psr-4": {
            "{packageNamespaceForComposer}\\": "src"
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "{packageNamespaceForComposer}\\{serviceProviderClassName}"
            ]
        }
    }
}