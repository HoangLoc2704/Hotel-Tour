<?php

return [
    'image_max_kb' => max((int) env('IMAGE_UPLOAD_MAX_KB', 900), 1),
];
