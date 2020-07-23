<?php

class Image
{
    public static function parseUrl($image_url)
    {
        preg_match('/(.+)\?.*hjw=(\d+)&hjh=(\d+)/', $image_url, $m);

        return [
            'url' => isset($m[1]) ? $m[1] : $image_url,
            'width' => isset($m[2]) ? (int) $m[2] : null,
            'height' => isset($m[3]) ? (int) $m[3] : null,
        ];
    }

    /**
      +----------------------------------------------------------
     * 格式化图片地址
      +----------------------------------------------------------
     * @static
     * @access public
      +----------------------------------------------------------
     * @param string $image_url  原图地址
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    public static function formatUrl($image_url, $w = null, $h = null, $secure = null)
    {
        if (empty($image_url)) {
            return null;
        }

        if ($w) {
            $info = self::parseUrl($image_url);

            $w = $w ? min($w, 800) : 400;
            $h = $h ? min($h, 1600) : $w;

            $w = min($w, $info['width'] ?: $w);
            $h = min($h, $info['height'] ?: $h);

            $image_url = $info['url'] . "?imageView2/1/w/{$w}/h/{$h}/q!";
        }

        $parsed_url = parse_url($image_url);
        $parsed_url['host'] = !empty($parsed_url['host']) ? $parsed_url['host'] : null;
        $parsed_url['path'] = !empty($parsed_url['path']) ? $parsed_url['path'] : null;
        $parsed_url['query'] = !empty($parsed_url['query']) ? $parsed_url['query'] : null;

        return ($secure ?  'https' : 'http') . '://' . $parsed_url['host'] . rtrim($parsed_url['path'] . '?' . $parsed_url['query'], '?');
    }

    public static function formatSecureUrl($image_url, $w = null, $h = null)
    {
        return self::formatUrl($image_url, $w, $h, true);
    }


    public static function formatCustomUrl($image_url, $secure = false)
    {
        return self::formatUrl($image_url, null, null, $secure);
    }
}
