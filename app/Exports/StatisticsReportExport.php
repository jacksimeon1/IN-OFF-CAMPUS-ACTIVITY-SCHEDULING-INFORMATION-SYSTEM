<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class StatisticsReportExport implements WithMultipleSheets
{
    protected $reportData;

    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        // Activities by Status Sheet
        $sheets[] = new class($this->reportData['statistics']['activities_by_status']) implements FromArray, WithTitle {
            protected $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                $result = [['Status', 'Count']];
                foreach ($this->data as $item) {
                    $result[] = [$this->getStatusLabel($item->workflow_status), $item->count];
                }
                return $result;
            }

            public function title(): string
            {
                return 'Activities by Status';
            }

            private function getStatusLabel($status)
            {
                $labels = [
                    'pending' => 'Pending',
                    'approved_by_adviser' => 'Approved by Adviser',
                    'noted_by_dean' => 'Noted by Dean',
                    'reviewed_by_psg' => 'Reviewed by PSG',
                    'endorsed_by_director' => 'Endorsed by Director',
                    'approved_by_vp' => 'Approved by VP',
                    'rejected' => 'Rejected'
                ];
                return $labels[$status] ?? $status;
            }
        };

        // Activities by Department Sheet
        $sheets[] = new class($this->reportData['statistics']['activities_by_department']) implements FromArray, WithTitle {
            protected $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                $result = [['Department', 'Count']];
                foreach ($this->data as $item) {
                    $result[] = [$item->department ?? 'N/A', $item->count];
                }
                return $result;
            }

            public function title(): string
            {
                return 'Activities by Department';
            }
        };

        // Activities by Type Sheet
        $sheets[] = new class($this->reportData['statistics']['activities_by_type']) implements FromArray, WithTitle {
            protected $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                $result = [['Type', 'Count']];
                foreach ($this->data as $item) {
                    $result[] = [$item->type ?? 'N/A', $item->count];
                }
                return $result;
            }

            public function title(): string
            {
                return 'Activities by Type';
            }
        };

        // Monthly Trends Sheet
        $sheets[] = new class($this->reportData['statistics']['monthly_trends']) implements FromArray, WithTitle {
            protected $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                $result = [['Year', 'Month', 'Count']];
                foreach ($this->data as $item) {
                    $monthName = date('F', mktime(0, 0, 0, $item->month, 1));
                    $result[] = [$item->year, $monthName, $item->count];
                }
                return $result;
            }

            public function title(): string
            {
                return 'Monthly Trends';
            }
        };

        return $sheets;
    }
}
