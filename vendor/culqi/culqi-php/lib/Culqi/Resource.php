<?php

namespace Culqi;

/**
 * Class Resource
 *
 * @package Culqi
 */
#[\AllowDynamicProperties]
class Resource extends Client {

    /**
     * Constructor.
     */
    public function __construct($culqi)
    {
        $this->culqi = $culqi;
    }

}
