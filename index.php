<?php

/**
 * Forward requests to the public directory.
 * This file is created to satisfy hosting health checks that require an index.php
 * in the root directory.
 */
require_once __DIR__.'/public/index.php';
