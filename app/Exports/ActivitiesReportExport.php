<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ActivitiesReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $reportData;

    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->reportData['activities'];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Type',
            'Student Name',
            'Department',
            'Status',
            'Activity Date',
            'Created Date',
            'Adviser',
            'Dean Note',
            'PSG Review',
            'Director Endorsement',
            'VP Approval'
        ];
    }

    /**
     * @param mixed $activity
     * @return array
     */
    public function map($activity): array
    {
        return [
            $activity->id,
            $activity->title,
            $activity->type ?? 'N/A',
            $activity->user->name,
            $activity->user->department ?? 'N/A',
            $this->getStatusLabel($activity->workflow_status),
            $activity->activity_date ? $activity->activity_date->format('Y-m-d') : 'N/A',
            $activity->created_at->format('Y-m-d H:i'),
            $activity->adviser_approved_at ? 'Approved' : 'Pending',
            $activity->dean_noted_at ? 'Noted' : 'Pending',
            $activity->psg_reviewed_at ? 'Reviewed' : 'Pending',
            $activity->director_endorsed_at ? 'Endorsed' : 'Pending',
            $activity->vp_approved_at ? 'Approved' : 'Pending',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Activities Report';
    }

    /**
     * Get human readable status label
     */
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
}
