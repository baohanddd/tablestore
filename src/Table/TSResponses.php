<?php
namespace Baohan\Tablestore\Table;

class TSResponses
{
    /**
     * @var TSResponse[]
     */
    protected array $TSResponses = [];

    /**
     * TSResponses constructor.
     * @param array $responses
     */
    public function __construct(array $responses)
    {
        foreach ($responses['tables'] as $table) {
            foreach ($table['rows'] as $row)
            $this->TSResponses[] = new TSResponse($row);
        }
    }
    
    /**
     * @return bool
     */
    public function allOK(): bool
    {
        foreach ($this->TSResponses as $res) {
            if ($res->isOk() === false) return false;
        }
        return true;
    }

    /**
     * @return TSResponse[]
     */
    public function getTSResponses(): array
    {
        return $this->TSResponses;
    }
}