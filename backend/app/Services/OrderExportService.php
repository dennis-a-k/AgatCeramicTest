<?php

namespace App\Services;

use App\Models\Order;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderExportService
{
    const EXPORT_HEADERS = [
        '№ Заказа',
        'Дата',
        'Клиент',
        'Email',
        'Телефон',
        'Адрес',
        'Комментарий',
        'Количество товаров в заказе',
        'Статус',
        'Общая сумма',
    ];

    const HEADER_FILL_COLOR = '95B3D7';
    const COLUMN_WIDTH_FACTOR = 1.2;

    public function export(?string $month = null): StreamedResponse
    {
        $query = Order::with('items')->orderBy('created_at', 'desc');

        if ($month) {
            $startOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $endOfMonth = $startOfMonth->copy()->endOfMonth();
            $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        }

        $orders = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $this->setHeaders($sheet, self::EXPORT_HEADERS);
        $this->styleHeaders($sheet, self::HEADER_FILL_COLOR);
        $this->setColumnWidths($sheet, self::EXPORT_HEADERS);

        $this->populateExportData($sheet, $orders);
        $this->formatCurrencyColumn($sheet, $orders);

        return $this->createResponse($spreadsheet, 'orders_export_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    protected function setHeaders($sheet, array $headers): void
    {
        $sheet->fromArray($headers, null, 'A1');
    }

    protected function styleHeaders($sheet, string $fillColor): void
    {
        $highestColumn = $sheet->getHighestColumn();
        $headerRange = 'A1:' . $highestColumn . '1';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($fillColor);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    protected function setColumnWidths($sheet, array $headers): void
    {
        $col = 0;
        foreach ($headers as $header) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
            $width = strlen($header) * self::COLUMN_WIDTH_FACTOR;
            $sheet->getColumnDimension($columnLetter)->setWidth($width);
            $col++;
        }
    }

    protected function populateExportData($sheet, $orders): void
    {
        $row = 2;
        foreach ($orders as $order) {
            $statusText = $this->getStatusText($order->status);
            $itemsCount = $order->items ? $order->items->sum('quantity') : 0;

            $orderData = [
                $order->order,
                $order->created_at->format('d.m.Y H:i'),
                $order->name,
                $order->email,
                $order->phone,
                $order->address,
                $order->comment,
                $itemsCount,
                $statusText,
                $order->total_amount,
            ];

            $sheet->fromArray($orderData, null, 'A' . $row);
            $row++;
        }
    }

    protected function formatCurrencyColumn($sheet, $orders): void
    {
        $rowCount = count($orders) + 1; // +1 для заголовка
        $range = 'J2:J' . $rowCount;
        $sheet->getStyle($range)->getNumberFormat()->setFormatCode('# ##0.00 ₽');
    }

    protected function getStatusText($status): string
    {
        $statuses = [
            'pending' => 'Новый',
            'processing' => 'Выполнение',
            'shipped' => 'Отправлен',
            'return' => 'Возврат',
            'cancelled' => 'Отменён',
        ];
        return $statuses[$status] ?? $status;
    }

    protected function createResponse(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
        $writer = new Xlsx($spreadsheet);

        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
