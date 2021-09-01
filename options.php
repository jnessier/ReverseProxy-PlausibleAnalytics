<?php

// Set the relative URI when the reverse proxy is running in a folder.
// If your proxy runs under https://example.com/stats, then set /stats as relative URI and otherwise just leave the string blank.
$relativeUri = '/stats';

// Set all allowed URI which should be accessible trough the proxy
$whitelist = [
    '/js/script.js',
    '/js/plausible.outbound-links.js',
    '/api/event'
];

// Optional, map allowed URI to another for sanitizing any proofs of Plausible Analytics in the URI.
// If not needed then leave the array empty.
$mapping = [
    '/foo/bar.js' => '/js/plausible.outbound-links.js'
];

// Set URL of Plausible Analytics
$backendUrl = "https://plausible.io";

// Script when running under a folder:
// <script defer data-domain="example.com" data-api="/stats/api/event" src="/stats/js/script.js"></script>

// Script when running in the root:
// <script defer data-domain="example.com" src="/js/script.js"></script>

