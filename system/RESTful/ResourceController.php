<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\RESTful;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * An extendable controller to provide a RESTful API for a resource.
 *
 * @see \CodeIgniter\RESTful\ResourceControllerTest
 */
class ResourceController extends BaseResource
{
    use ResponseTrait;

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return ResponseInterface|string|void
     */
    public function index()
    {
        return $this->fail(lang('RESTful.notImplemented', ['index']), 501);
    }

    /**
     * Return the properties of a resource object
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface|string|void
     */
    public function show($id = null)
    {
        return $this->fail(lang('RESTful.notImplemented', ['show']), 501);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return ResponseInterface|string|void
     */
    public function new()
    {
        return $this->fail(lang('RESTful.notImplemented', ['new']), 501);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return ResponseInterface|string|void
     */
    public function create()
    {
        return $this->fail(lang('RESTful.notImplemented', ['create']), 501);
    }

    /**
     * Return the editable properties of a resource object
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface|string|void
     */
    public function edit($id = null)
    {
        return $this->fail(lang('RESTful.notImplemented', ['edit']), 501);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface|string|void
     */
    public function update($id = null)
    {
        return $this->fail(lang('RESTful.notImplemented', ['update']), 501);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface|string|void
     */
    public function delete($id = null)
    {
        return $this->fail(lang('RESTful.notImplemented', ['delete']), 501);
    }

    /**
     * Set/change the expected response representation for returned objects
     *
     * @param 'json'|'xml' $format Response format
     *
     * @return void
     */
    public function setFormat(string $format = 'json')
    {
        if (in_array($format, ['json', 'xml'], true)) {
            $this->format = $format;
        }
    }
}
