<?php

namespace MyApp\Controller;

class MainImages extends MyApp\Controller {



public function getMainImages() {
    $images = new \MyApp\Model\Images();
    $mainImages = $images->getImages();
}



}