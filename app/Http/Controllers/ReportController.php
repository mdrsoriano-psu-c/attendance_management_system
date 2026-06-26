<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function index()
    {
        $attendanceRecords = Attendance::with(['student', 'classModel'])->latest('date')->get();
        $summary = $this->summary();

        return view('reports.index', compact('attendanceRecords', 'summary'));
    }

    public function export(string $format)
    {
        $records = Attendance::with(['student', 'classModel'])->latest('date')->get();
        $rows = $this->rows($records);
        $fileName = 'attendance-report-' . now()->format('Y-m-d-His');

        return match (strtolower($format)) {
            'json' => response()->json([
                'generated_at' => now()->toDateTimeString(),
                'summary' => $this->summary(),
                'data' => $rows,
            ]),
            'csv' => $this->csv($rows, $fileName . '.csv'),
            'xlsx' => $this->xlsx($rows, $fileName . '.xlsx'),
            'pdf' => $this->pdf($rows, $fileName . '.pdf'),
            default => abort(404),
        };
    }

    public function importForm()
    {
        $classes = ClassModel::latest()->get();
        return view('reports.import', compact('classes'));
    }

    public function importStudents(Request $request)
    {
       $request->validate([
    'class_id' => 'nullable|exists:class_models,id',
    'file' => 'required|file|max:4096',
]);

        $uploadedFile = $request->file('file');

if (!$uploadedFile || !$uploadedFile->isValid()) {
    return back()->withErrors(['file' => 'The uploaded file is invalid. Please try again.']);
}

$handle = fopen($uploadedFile->getPathname(), 'r');
        $header = fgetcsv($handle);
        $count = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            if (!$data || empty($data['name']) || empty($data['email'])) {
                continue;
            }

            Student::updateOrCreate(
                ['email' => trim($data['email'])],
                [
                    'name' => trim($data['name']),
                    'class_id' => $request->class_id ?: ($data['class_id'] ?? null),
                    'qr_code' => $data['qr_code'] ?? (string) Str::uuid(),
                ]
            );
            $count++;
        }

        fclose($handle);

        return redirect()->route('reports.index')->with('success', $count . ' student(s) imported successfully.');
    }

    private function rows($records): array
    {
        return $records->map(fn ($record) => [
            'ID' => $record->id,
            'Date' => $record->date,
            'Class' => $record->classModel->name ?? 'N/A',
            'Student' => $record->student->name ?? 'N/A',
            'Email' => $record->student->email ?? 'N/A',
            'Status' => ucfirst($record->present),
        ])->toArray();
    }

    private function summary(): array
    {
        $total = Attendance::count();
        $present = Attendance::where('present', 'present')->count();

        return [
            'total_classes' => ClassModel::count(),
            'total_students' => Student::count(),
            'total_attendance' => $total,
            'present' => $present,
            'absent' => Attendance::where('present', 'absent')->count(),
            'late' => Attendance::where('present', 'late')->count(),
            'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
        ];
    }

    private function csv(array $rows, string $fileName)
    {
        $callback = function () use ($rows) {
            $output = fopen('php://output', 'w');
            if (!empty($rows)) {
                fputcsv($output, array_keys($rows[0]));
                foreach ($rows as $row) {
                    fputcsv($output, $row);
                }
            }
            fclose($output);
        };

        return response()->streamDownload($callback, $fileName, ['Content-Type' => 'text/csv']);
    }

    private function xlsx(array $rows, string $fileName)
    {
        $tmp = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip = new \ZipArchive();
        $zip->open($tmp, \ZipArchive::OVERWRITE);

        $sheetRows = [];
        $headers = $rows ? array_keys($rows[0]) : ['ID', 'Date', 'Class', 'Student', 'Email', 'Status'];
        $sheetRows[] = $headers;
        foreach ($rows as $row) {
            $sheetRows[] = array_values($row);
        }

        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';
        foreach ($sheetRows as $r => $row) {
            $rowNo = $r + 1;
            $sheetXml .= '<row r="' . $rowNo . '">';
            foreach (array_values($row) as $c => $value) {
                $cell = chr(65 + $c) . $rowNo;
                $sheetXml .= '<c r="' . $cell . '" t="inlineStr"><is><t>' . htmlspecialchars((string) $value, ENT_XML1) . '</t></is></c>';
            }
            $sheetXml .= '</row>';
        }
        $sheetXml .= '</sheetData></worksheet>';

        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>');
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Attendance Report" sheetId="1" r:id="rId1"/></sheets></workbook>');
        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>');
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
        $zip->close();

        return response()->download($tmp, $fileName, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])->deleteFileAfterSend(true);
    }

    private function pdf(array $rows, string $fileName)
    {
        $lines = [
            'Attendance Report',
            'Generated: ' . now()->toDateTimeString(),
            '',
            'ID | Date | Class | Student | Email | Status',
        ];

        foreach ($rows as $row) {
            $lines[] = implode(' | ', $row);
        }

        $content = $this->simplePdf($lines);

        return Response::make($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    private function simplePdf(array $lines): string
    {
        $stream = "BT /F1 12 Tf 50 790 Td 14 TL";
        foreach ($lines as $index => $line) {
            $escaped = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], substr($line, 0, 100));
            $stream .= ($index === 0 ? '' : ' T*') . " ($escaped) Tj";
        }
        $stream .= " ET";

        $objects = [];
        $objects[] = '1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj';
        $objects[] = '2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj';
        $objects[] = '3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >> endobj';
        $objects[] = '4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj';
        $objects[] = '5 0 obj << /Length ' . strlen($stream) . " >> stream\n" . $stream . "\nendstream endobj";

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object . "\n";
        }
        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";
        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= str_pad((string) $offset, 10, '0', STR_PAD_LEFT) . " 00000 n \n";
        }
        $pdf .= "trailer << /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n$xref\n%%EOF";

        return $pdf;
    }
}
