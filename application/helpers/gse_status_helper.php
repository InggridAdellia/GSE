<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function status_tahap_gabungan($items, $tahap_kode) {
    if (empty($items)) {
        return ['value' => null, 'waiting' => false];
    }

    $first = reset($items);
    $is_keluar = isset($first->id_permohonan_keluar) || (property_exists($first, 'id_permohonan_keluar') && !property_exists($first, 'id_permohonan_masuk'));
    $is_sparepart = (($first->jenis_item ?? '') === 'Sparepart' || strpos(($first->jenis_item ?? ''), 'Sparepart') !== false);

    if ($is_keluar) {
        $urutan = ['sales', 'operasi', 'security'];
    } elseif ($is_sparepart) {
        $urutan = ['operasi', 'security'];
    } else {
        $urutan = ['operasi', 'equipment', 'sales', 'security'];
    }

    $idx_tahap = array_search($tahap_kode, $urutan);
    if ($idx_tahap === false) {
        return ['value' => null, 'waiting' => false];
    }

    $semua_lewat        = true;
    $ada_ditolak_disini = false;
    $ada_proses_disini  = false;

    foreach ($items as $it) {
        $tahap_item = $it->tahap_saat_ini ?? ($is_keluar ? 'sales' : 'operasi');
        $idx_item   = ($tahap_item === 'selesai') ? 999 : array_search($tahap_item, $urutan);
        if ($idx_item === false) $idx_item = 0;

        if ($idx_item <= $idx_tahap) {
            $semua_lewat = false;
            if ($idx_item === $idx_tahap) {
                if (($it->status_item ?? null) === 'Ditolak') {
                    $ada_ditolak_disini = true;
                } else {
                    $ada_proses_disini = true;
                }
            }
        }
    }

    if ($semua_lewat)        return ['value' => 'Disetujui', 'waiting' => false];
    if ($ada_ditolak_disini) return ['value' => 'Ditolak',    'waiting' => false];
    if ($ada_proses_disini)  return ['value' => null,         'waiting' => true];
    return ['value' => null, 'waiting' => false];
}