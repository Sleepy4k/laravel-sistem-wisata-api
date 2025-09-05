<?php

return [

    'headers' => [
        /**
         * Server
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Server
         *
         * Note: When server is empty string, it will not be added to the response header.
         */
        'server' => env('APP_NAME', 'Laravel'),

        /**
         * X-Content-Type-Options
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options
         *
         * Available Value: 'nosniff'
         */
        'x-content-type-options' => 'nosniff',

        /**
         * X-DNS-Prefetch-Control
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-DNS-Prefetch-Control
         *
         * Available Value: 'on', 'off'
         */
        'x-dns-prefetch-control' => 'on',

        /**
         * X-Download-Options
         *
         * @see https://msdn.microsoft.com/en-us/library/jj542450(v=vs.85).aspx
         *
         * Available Value: 'noopen'
         */
        'x-download-options' => 'noopen',

        /**
         * X-Frame-Options
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options
         * @deprecated The X-Frame-Options is no longer recommended for use; please use Content-Security-Policy (CSP) instead.
         *
         * Available Value: 'deny', 'sameorigin', 'allow-from <uri>'
         */
        'x-frame-options' => 'deny',

        /**
         * X-Permitted-Cross-Domain-Policies
         *
         * @see https://www.adobe.com/devnet-docs/acrobatetk/tools/AppSec/xdomain.html
         *
         * Available Value: 'all', 'none', 'master-only', 'by-content-type', 'by-ftp-filename'
         */
        'x-permitted-cross-domain-policies' => 'none',

        /**
         * X-Powered-By
         *
         * Note: it will not add to response header if the value is empty string.
         *
         * Also, verify that expose_php is turned Off in php.ini.
         * Otherwise, the header will still be included in the response.
         *
         * @see https://github.com/bepsvpt/secure-headers/issues/58#issuecomment-782332442
         */
        'x-powered-by' => '',

        /**
         * X-XSS-Protection
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-XSS-Protection
         * @deprecated The X-XSS-Protection is no longer recommended for use; please use Content-Security-Policy (CSP) instead.
         *
         * Available Value: '1', '0', '1; mode=block'
         */
        'x-xss-protection' => '1; mode=block',

        /**
         * Referrer-Policy
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Referrer-Policy
         *
         * Available Value: 'no-referrer', 'no-referrer-when-downgrade', 'origin', 'origin-when-cross-origin',
         *                  'same-origin', 'strict-origin', 'strict-origin-when-cross-origin', 'unsafe-url'
         */
        'referrer-policy' => 'no-referrer',

        /**
         * Cross-Origin-Embedder-Policy
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cross-Origin-Embedder-Policy
         *
         * Available Value: 'unsafe-none', 'require-corp', 'credentialless'
         */
        'cross-origin-embedder-policy' => 'unsafe-none',

        /**
         * Cross-Origin-Opener-Policy
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cross-Origin-Opener-Policy
         *
         * Available Value: 'unsafe-none', 'same-origin-allow-popups', 'same-origin'
         */
        'cross-origin-opener-policy' => 'unsafe-none',

        /**
         * Cross-Origin-Resource-Policy
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cross-Origin-Resource-Policy
         *
         * Available Value: 'same-site', 'same-origin', 'cross-origin'
         */
        'cross-origin-resource-policy' => 'cross-origin',
    ],

    /**
     * Permissions Policy
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy
     */
    'permissions-policy' => [
        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/accelerometer
        'accelerometer' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/attribution-reporting
        'attribution-reporting' => [
            'none' => false,

            '*' => true,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/autoplay
        'autoplay' => [
            'none' => false,

            '*' => false,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/bluetooth
        'bluetooth' => [
            'none' => false,

            '*' => false,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/browsing-topics
        'browsing-topics' => [
            'none' => false,

            '*' => true,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/camera
        'camera' => [
            'none' => false,

            '*' => false,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/compute-pressure
        'compute-pressure' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/compute-pressure
        'cross-origin-isolated' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/display-capture
        'display-capture' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/encrypted-media
        'encrypted-media' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/fullscreen
        'fullscreen' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/gamepad
        'gamepad' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/geolocation
        'geolocation' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/gyroscope
        'gyroscope' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/hid
        'hid' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/identity-credentials-get
        'identity-credentials-get' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/idle-detection
        'idle-detection' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/local-fonts
        'local-fonts' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/magnetometer
        'magnetometer' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/microphone
        'microphone' => [
            'none' => false,

            '*' => false,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/midi
        'midi' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/otp-credentials
        'otp-credentials' => [
            'none' => false,

            '*' => false,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/payment
        'payment' => [
            'none' => false,

            '*' => false,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/picture-in-picture
        'picture-in-picture' => [
            'none' => false,

            '*' => true,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/publickey-credentials-create
        'publickey-credentials-create' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/publickey-credentials-get
        'publickey-credentials-get' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/screen-wake-lock
        'screen-wake-lock' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/serial
        'serial' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/storage-access
        'storage-access' => [
            'none' => false,

            '*' => true,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/usb
        'usb' => [
            'none' => false,

            '*' => false,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/web-share
        'web-share' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/window-management
        'window-management' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/xr-spatial-tracking
        'xr-spatial-tracking' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],
    ],

];
