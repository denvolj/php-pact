<?php

namespace Pact\Utils;

class UrlFormatter
{
    public static function format($path, ...$ids)
    {
        foreach ($ids as $id) {
            if (null === $id || '' === trim($id)) {
                $msg = 'The resource ID cannot be null or whitespace.';

                throw new \InvalidArgumentException($msg);
            }
        }

        return sprintf($path, ...array_map('urlencode', $ids));
    }
}
