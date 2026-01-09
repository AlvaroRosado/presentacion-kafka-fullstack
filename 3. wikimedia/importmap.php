<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
    '@symfony/ux-live-component' => [
        'path' => './vendor/symfony/ux-live-component/assets/dist/live_controller.js',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.7',
        'type' => 'css',
    ],
    'bootstrap-icons/font/bootstrap-icons.css' => [
        'version' => '1.13.1',
        'type' => 'css',
    ],
    'boxicons/css/boxicons.min.css' => [
        'version' => '2.1.4',
        'type' => 'css',
    ],
    'quill/dist/quill.snow.css' => [
        'version' => '2.0.3',
        'type' => 'css',
    ],
    'quill/dist/quill.bubble.css' => [
        'version' => '2.0.3',
        'type' => 'css',
    ],
    'remixicon/fonts/remixicon.css' => [
        'version' => '4.6.0',
        'type' => 'css',
    ],
    'simple-datatables/dist/style.css' => [
        'version' => '10.0.0',
        'type' => 'css',
    ],
    'quill' => [
        'version' => '2.0.3',
    ],
    'lodash-es' => [
        'version' => '4.17.21',
    ],
    'parchment' => [
        'version' => '3.0.0',
    ],
    'quill-delta' => [
        'version' => '5.1.0',
    ],
    'eventemitter3' => [
        'version' => '5.0.1',
    ],
    'fast-diff' => [
        'version' => '1.3.0',
    ],
    'lodash.clonedeep' => [
        'version' => '4.5.0',
    ],
    'lodash.isequal' => [
        'version' => '4.5.0',
    ],
    'simple-datatables' => [
        'version' => '10.0.0',
    ],
    'simple-datatables/dist/style.min.css' => [
        'version' => '10.0.0',
        'type' => 'css',
    ],
    'apexcharts' => [
        'version' => '4.7.0',
    ],
    'bootstrap' => [
        'version' => '5.3.7',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    '@kurkle/color' => [
        'version' => '0.3.4',
    ],
    'echarts' => [
        'version' => '5.6.0',
    ],
    'tslib' => [
        'version' => '2.3.0',
    ],
    'zrender/lib/zrender.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/util.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/env.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/timsort.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/Eventful.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/Text.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/tool/color.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/Path.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/tool/path.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/matrix.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/vector.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/Transformable.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/Image.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/Group.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/shape/Circle.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/shape/Ellipse.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/shape/Sector.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/shape/Ring.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/shape/Polygon.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/shape/Polyline.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/shape/Rect.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/shape/Line.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/shape/BezierCurve.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/shape/Arc.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/CompoundPath.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/LinearGradient.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/RadialGradient.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/BoundingRect.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/OrientedBoundingRect.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/Point.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/IncrementalDisplayable.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/helper/subPixelOptimize.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/dom.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/helper/parseText.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/WeakMap.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/LRU.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/contain/text.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/canvas/graphic.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/platform.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/contain/polygon.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/PathProxy.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/contain/util.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/curve.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/svg/Painter.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/canvas/Painter.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/event.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/tool/parseSVG.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/tool/parseXML.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/graphic/Displayable.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/core/bbox.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/contain/line.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/contain/quadratic.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/animation/Animator.js' => [
        'version' => '5.6.1',
    ],
    'zrender/lib/tool/morphPath.js' => [
        'version' => '5.6.1',
    ],
    'tinymce' => [
        'version' => '7.9.1',
    ],
    'bootstrap/dist/js/bootstrap.bundle.min.js' => [
        'version' => '5.3.7',
    ],
    'filepond' => [
        'version' => '4.32.9',
    ],
    'filepond-plugin-image-preview' => [
        'version' => '4.6.12',
    ],
    'simplebar' => [
        'version' => '6.3.2',
    ],
    'simplebar-core' => [
        'version' => '1.3.2',
    ],
    'simplebar/dist/simplebar.min.css' => [
        'version' => '6.3.2',
        'type' => 'css',
    ],
    'lodash-es/debounce.js' => [
        'version' => '4.17.21',
    ],
    'lodash-es/throttle.js' => [
        'version' => '4.17.21',
    ],
    'simplebar-core/dist/simplebar.min.css' => [
        'version' => '1.3.2',
        'type' => 'css',
    ],
    'typed.js' => [
        'version' => '2.1.0',
    ],
    'daisyui' => [
        'version' => '5.0.51',
    ],
    'daisyui/daisyui.min.css' => [
        'version' => '5.0.51',
        'type' => 'css',
    ],
    'daisyui/theme' => [
        'version' => '5.0.51',
    ],
    '@stimulus-components/checkbox-select-all' => [
        'version' => '6.1.0',
    ],
    'chartjs-color' => [
        'version' => '2.0.0',
    ],
    'moment' => [
        'version' => '2.17.1',
    ],
    'color-convert' => [
        'version' => '0.5.3',
    ],
    'chartjs-color-string' => [
        'version' => '0.4.0',
    ],
    'color-name' => [
        'version' => '1.1.1',
    ],
    'chart.js' => [
        'version' => '4.5.1',
    ],
];
