<?php

namespace App\Model;

/**
 * Interface for all models
 */
interface CModelInterface
{

    /**
     * Convert the request to associated model
     *
     * @param Request $request
     * @return Model
     */
    public function convert($request);
}
