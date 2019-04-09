<?php

function getImage($xmlItem) {
    $image = false;
    $media = $xmlItem->getNameSpaces('media');
    $image = $xmlItem->children($media['media']);
    $imageUrl = $image->content->attributes()['url']??"";
    if(substr($imageUrl,-1)=="/") {
        return null;
    }
    return $imageUrl;
}