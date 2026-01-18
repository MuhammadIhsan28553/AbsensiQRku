<?php

/*
 * This file is part of the Simple QrCode package.
 *
 * (c) SimpleSoftWareIO
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    /**
     * The default driver to use.
     * Ensure this is set to 'gd' if Imagick is not installed.
     */
    'defaults' => [
        'driver' => 'gd', // <-- UBAH ATAU PASTIKAN INI ADALAH 'gd'
    ],

    /**
     * The list of supported drivers.
     */
    'drivers' => [
        'gd' => [
            'renderer' => 'image',

            // The list of supported back ends.
            'supported_backends' => [
                'image'
            ],
        ],
        'imagick' => [
            'renderer' => 'image',

            // The list of supported back ends.
            'supported_backends' => [
                'imagick'
            ],
        ],
    ],

    /**
     * The list of renderers.
     */
    'renderers' => [
        'image' => [
            'class' => \SimpleSoftwareIO\QrCode\Renderer\ImageRenderer::class,

            // The list of supported back ends.
            'supported_backends' => [
                \SimpleSoftwareIO\QrCode\Renderer\Image\GdImageBackEnd::class,
                \SimpleSoftwareIO\QrCode\Renderer\Image\ImagickImageBackEnd::class,
            ],
        ],
        'svg' => [
            'class' => \SimpleSoftwareIO\QrCode\Renderer\SvgRenderer::class,

            // The list of supported back ends.
            'supported_backends' => [
                \SimpleSoftwareIO\QrCode\Renderer\Svg\SvgImageBackEnd::class,
            ],
        ],
        'eps' => [
            'class' => \SimpleSoftwareIO\QrCode\Renderer\EpsRenderer::class,

            // The list of supported back ends.
            'supported_backends' => [
                \SimpleSoftwareIO\QrCode\Renderer\Eps\EpsImageBackEnd::class
            ],
        ],
    ],

    'writer' => \BaconQrCode\Writer::class,

    'writer_options' => [],

    'error_correction' => 'M', // Level koreksi error (L, M, Q, H)

    'round_block_size' => true, // Membulatkan ukuran blok
];
