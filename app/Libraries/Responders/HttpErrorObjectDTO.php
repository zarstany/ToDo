<?php

namespace App\Libraries\Responders;

class HttpErrorObjectDTO
{
    /**
     * the HTTP status code applicable to this problem, expressed as a string value.
     */
    private int $status = 200;

    /**
     * an application-specific error code, expressed as a string value
     */
    private string $code = '';

    /**
     *  a short, human-readable summary of the problem that SHOULD NOT change from occurrence to occurrence
     * of the problem, except for purposes of localization.
     */
    private string $title = '';

    /**
     *  a human-readable explanation specific to this occurrence of the problem. Like title,
     * this field’s value can be localized.
     */
    private string $detail = '';

    /**
     * an object containing references to the source of the error, optionally including any of the following members
     * - pointer: a JSON Pointer [RFC6901] to the associated entity in the request document
     * [e.g. "/data" for a primary data object, or "/data/attributes/title" for a specific attribute].
     * - parameter: a string indicating which URI query parameter caused the error.
     */
    private array $source = [];

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): HttpErrorObjectDTO
    {
        $this->status = $status;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): HttpErrorObjectDTO
    {
        $this->code = $code;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): HttpErrorObjectDTO
    {
        $this->title = $title;

        return $this;
    }

    public function getDetail(): string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): HttpErrorObjectDTO
    {
        $this->detail = $detail;

        return $this;
    }

    public function getSource(): array
    {
        return $this->source;
    }

    public function setSource(array $source): HttpErrorObjectDTO
    {
        $this->source = $source;

        return $this;
    }

    public function get(): array
    {
        $error = [
            'status' => $this->getStatus(),
            'title' => $this->getTitle(),
            'detail' => $this->getDetail(),
        ];

        if ($this->getCode() !== '') {
            $error['code'] = $this->getCode();
        }

        if (count($this->getSource()) > 0) {
            $error['source'] = $this->getSource();
        }

        return $error;
    }
}
