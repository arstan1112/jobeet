<?php


namespace App\Utils;

final class HashTagsNormalizer
{
    public static function normalize($hashTag)
    {
        $hashes = str_replace(' ', '', $hashTag);
        $hashes = explode('#', $hashes);
        return array_values(array_filter($hashes));
    }

    public static function normalizeArray($hashTags)
    {
        $normalized = [];
        foreach ($hashTags as $hashTag) {
            $hashes = str_replace(' ', '', $hashTag);
            $normalized[] = $hashes;
        }
        return $normalized;
    }
}
