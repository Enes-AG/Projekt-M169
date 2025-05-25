<?php
require_once(__DIR__ . '/config.php');
require_once($CFG->dirroot . '/course/lib.php');

redirect(new moodle_url('/my/'));

