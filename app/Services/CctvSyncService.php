<?php

namespace App\Services;

use App\Models\CctvModel;
use RuntimeException;

class CctvSyncService
{
    private CctvModel $cctvModel;

    public function __construct(?CctvModel $cctvModel = null)
    {
        $this->cctvModel = $cctvModel ?? new CctvModel();
    }

    /**
     * Pull the remote inventory without deleting local-only cameras.
     *
     * @return array{inserted:int, updated:int, skipped:int}
     */
    public function sync(): array
    {
        $source = db_connect('cctvSource');
        $rows = $source->table('productsCCTV')
            ->select([
                'id', 'name', 'inventory_code', 'sub_division', 'category',
                'jenis_kategori', 'Merk', 'serial_number', 'status', 'reg_date',
                'location', 'ip', 'nvr', 'nomor_urut', 'link_img', 'rtsp_url',
                'model', 'lattitude', 'longtitude', 'created_at', 'updated_at',
            ])
            ->get()
            ->getResultArray();

        $stats = ['inserted' => 0, 'updated' => 0, 'skipped' => 0];
        $localDatabase = $this->cctvModel->db;
        $localDatabase->transStart();

        foreach ($rows as $row) {
            $data = $this->mapRow($row);
            $existing = null;

            if ($data['inventory_code'] !== '') {
                $existing = $this->cctvModel
                    ->where('inventory_code', $data['inventory_code'])
                    ->first();
            }

            if (!$existing) {
                $existing = $this->cctvModel
                    ->where('source_id', $data['source_id'])
                    ->first();
            }

            if ($existing) {
                $this->cctvModel->update($existing['id'], $data);
                $stats['updated']++;
            } else {
                $this->cctvModel->insert($data);
                $stats['inserted']++;
            }
        }

        $localDatabase->transComplete();

        if (!$localDatabase->transStatus()) {
            throw new RuntimeException('Transaksi sinkronisasi CCTV gagal disimpan.');
        }

        return $stats;
    }

    private function mapRow(array $row): array
    {
        $sourceId = (int) ($row['id'] ?? 0);
        $name = trim((string) ($row['name'] ?? ''));
        $category = trim((string) ($row['category'] ?? ''));
        $jenisKategori = trim((string) ($row['jenis_kategori'] ?? ''));

        return [
            'source_id' => $sourceId,
            'inventory_code' => trim((string) ($row['inventory_code'] ?? '')) ?: null,
            'nama_camera' => $name !== '' ? $name : 'CCTV #' . $sourceId,
            'location' => trim((string) ($row['location'] ?? '')) ?: 'Tidak diketahui',
            'ip_address' => trim((string) ($row['ip'] ?? '')) ?: '0.0.0.0',
            'type_camera' => $category !== '' ? $category : ($jenisKategori !== '' ? $jenisKategori : 'CCTV'),
            'rtsp_url' => trim((string) ($row['rtsp_url'] ?? '')),
            'latitude' => $this->decimalOrDefault($row['lattitude'] ?? null),
            'longitude' => $this->decimalOrDefault($row['longtitude'] ?? null),
            'source_name' => $name !== '' ? $name : null,
            'sub_division' => $this->nullableString($row['sub_division'] ?? null),
            'category' => $this->nullableString($row['category'] ?? null),
            'jenis_kategori' => $this->nullableString($row['jenis_kategori'] ?? null),
            'merk' => $this->nullableString($row['Merk'] ?? null),
            'serial_number' => $this->nullableString($row['serial_number'] ?? null),
            'source_status' => $this->nullableString($row['status'] ?? null),
            'reg_date' => $this->dateOrNull($row['reg_date'] ?? null),
            'nvr' => $this->nullableString($row['nvr'] ?? null),
            'nomor_urut' => $this->nullableString($row['nomor_urut'] ?? null),
            'link_img' => $this->nullableString($row['link_img'] ?? null),
            'source_model' => $this->nullableString($row['model'] ?? null),
            'source_created_at' => $this->dateTimeOrNull($row['created_at'] ?? null),
            'source_updated_at' => $this->dateTimeOrNull($row['updated_at'] ?? null),
        ];
    }

    private function nullableString($value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    private function decimalOrDefault($value): string
    {
        $value = trim((string) $value);
        return is_numeric($value) ? $value : '0';
    }

    private function dateOrNull($value): ?string
    {
        $value = trim((string) $value);
        return $value !== '' && $value !== '0000-00-00' ? $value : null;
    }

    private function dateTimeOrNull($value): ?string
    {
        $value = trim((string) $value);
        return $value !== '' && $value !== '0000-00-00 00:00:00' ? $value : null;
    }
}
