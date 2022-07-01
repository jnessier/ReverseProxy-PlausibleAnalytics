<?php

// Set the relative URI when the reverse proxy is running in a folder.
// If your proxy runs under https://example.com/stats, then set /stats as relative URI and otherwise just leave the string blank.
$relativeUri = '/stats';

// Set all allowed URI which should be accessible through the proxy
$whitelist = [
    '/js/script.js',
    '/js/script.outbound-links.js',
    '/api/event'
];

// Optional, map allowed URI to another for sanitizing any proofs of Plausible Analytics in the URI.
// If not needed then leave the array empty.
$mapping = [
    '/foo/bar.js' => '/js/script.outbound-links.js'
];

// Set visitor IP addresses as exclusion (e.g. for yourself or developers)
// If not needed then leave the array empty.
$excluded = [
    '127.0.0.1',
    '::1',
];

// Set URL of Plausible Analytics
$backendUrl = "https://plausible.io";

// Script when running under a folder:
// <script defer data-domain="example.com" data-api="/stats/api/event" src="/stats/js/script.js"></script>

// Script when running in the root:
// <script defer data-domain="example.com" src="/js/script.js"></script>

