<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
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
        return $this->reportData['users'];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Role',
            'Department',
            'Status',
            'Total Activities',
            'Approved Activities',
            'Pending Activities',
            'Registration Date',
            'Last Login',
            'Email Verified'
        ];
    }

    /**
     * @param mixed $user
     * @return array
     */
    public function map($user): array
    {
        $totalActivities = $user->activities->count();
        $approvedActivities = $user->activities->where('workflow_status', 'approved_by_vp')->count();
        $pendingActivities = $user->activities->where('workflow_status', 'pending')->count();

        return [
            $user->id,
            $user->name,
            $user->email,
            $user->role === 'student' ? 'Student Officer' : ucfirst($user->role),
            $user->department ?? 'N/A',
            $user->email_verified_at ? 'Active' : 'Inactive',
            $totalActivities,
            $approvedActivities,
            $pendingActivities,
            $user->created_at->format('Y-m-d'),
            $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : 'Never',
            $user->email_verified_at ? 'Yes' : 'No',
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
        return 'Users Report';
    }
}
