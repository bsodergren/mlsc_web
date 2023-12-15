<?php
/**
 * CWP Media tool
 */

namespace MLSC\Traits;

use Symfony\Component\Process\Process;

trait Callables
{
    public function ProcessOutput($type, $buffer)
    {
        if (Process::ERR === $type)
        {
            echo 'ERR > '.$buffer;
        } else
        {
            echo 'OUT > '.$buffer;
        }
    }

    public function getIpfromNeighbor($type, $buffer)
    {
        $matched = preg_match('/(\d+.\d+.\d+.\d+).*(9c:9c:1f:47:d3:fa).*/', $buffer, $output_array);
        if ($matched == true)
        {
            $this->result = $output_array;
        }
    }
}
