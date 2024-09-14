<?php

namespace Domain\Media\Models;

use Awcodes\Curator\Models\Media as BaseMedia;

/**
 * @property string $path
 * @property string $alt
 * @property string $type
 * @property string $ext
 * @property string $size
 * @property string $width
 * @property string $height
 * @property string $disk
 * @property string $url
 * @property string $thumbnail_url
 * @property string $medium_url
 * @property string $large_url
 * @property string $resizable
 * @property string $size_for_humans
 * @property string $pretty_name
 */
class Media extends BaseMedia
{
}
