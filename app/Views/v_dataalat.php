<?= $this->extend('template/BaseView'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $heading; ?></h1>
    </div>

    <?= $this->include('template/statusBar'); ?>

    <div class="row">
        <div class="col">
            <?php if (session()->getFlashdata('pesan')) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> <?= session()->getFlashdata('pesan'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <button type="button" onclick="window.location.href='<?= base_url('form/createalat'); ?>';" class="btn btn-success"><i class="fas fa-fw fa-plus"></i> Add</button>
                    <?php $query = http_build_query(array_filter($filters)); ?>
                    <a href="<?= base_url('form/dataalat/export/excel') . ($query ? '?' . $query : ''); ?>" class="btn btn-outline-success"><i class="fas fa-file-excel"></i> Excel</a>
                    <a href="<?= base_url('form/dataalat/export/pdf') . ($query ? '?' . $query : ''); ?>" class="btn btn-outline-danger"><i class="fas fa-file-pdf"></i> PDF</a>
                </div>
                <div class="card-body">
                    <form method="get" action="<?= base_url('form/dataalat'); ?>" class="mb-4">
                        <div class="form-row align-items-end">
                            <div class="col-md-3 mb-2">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">All</option>
                                    <?php foreach (['Milik', 'Sewa'] as $option) { ?>
                                        <option value="<?= $option; ?>" <?= $filters['status'] === $option ? 'selected' : ''; ?>><?= $option; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="keterangan">Keterangan</label>
                                <select name="keterangan" id="keterangan" class="form-control">
                                    <option value="">All</option>
                                    <?php foreach (['Elektrifikasi', 'Non Elektrifikasi'] as $option) { ?>
                                        <option value="<?= $option; ?>" <?= $filters['keterangan'] === $option ? 'selected' : ''; ?>><?= $option; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="tahun">Tahun</label>
                                <select name="tahun" id="tahun" class="form-control">
                                    <option value="">All</option>
                                    <?php foreach ($tahunOptions as $option) { ?>
                                        <option value="<?= esc($option); ?>" <?= $filters['tahun'] === $option ? 'selected' : ''; ?>><?= esc($option); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="negara">Negara</label>
                                <select name="negara" id="negara" class="form-control">
                                    <option value="">All</option>
                                    <?php foreach ($negaraOptions as $option) { ?>
                                        <option value="<?= esc($option); ?>" <?= $filters['negara'] === $option ? 'selected' : ''; ?>><?= esc($option); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <button type="submit" class="btn btn-primary mr-1"><i class="fas fa-filter"></i> Filter</button>
                                <a href="<?= base_url('form/dataalat'); ?>" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                    
                      <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                   <!--<th>Foto</th>-->
                                    <th>Nomor Asset</th>
                                    <th>Nama Alat</th>
                                    <th>Kode Alat</th>
                                    <th>Merk</th>
                                    <th>Model</th>
                                    <th>Kap Swal Ton</th>
                                    <th>Span M</th>
                                    <th>Outreach M</th>
                                    <th>Status</th>
                                    <th>Tahun</th>
                                    <th>Negara</th>
                                    <th>Keterangan</th>
                                    <th>Lokasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Looping alat coy -->
                                <?php foreach ($data as $i) { ?>
                                    <tr>
                                        <!--<td>
                                            <img src="<?//= base_url('img/alat/' . $i['foto_alat']); ?>" width="80px" height="80px" class="img-fluid" alt="<?//= $i['nama_alat']; ?>">
                                        </td>-->
                                        <td><a href="<?= base_url('alat/' . $i['nomor_asset']); ?>" target="_blank"><?= $i['nomor_asset']; ?></a></td>
                                        <td><?= $i['nama_alat']; ?></a></td>
                                        <td><?= $i['kode_alat']; ?></td>
                                        <td><?= $i['merk']; ?></td>
                                        <td><?= $i['model']; ?></td>
                                        <td><?= $i['kap_swal_ton']; ?></td>
                                        <td><?= $i['span_m']; ?></td>
                                        <td><?= $i['outreach_m']; ?></td>
                                        <td>
                                            <?php if ($i['status'] == 'Milik') { ?>
                                                <span class="badge badge-success"><?= $i['status']; ?></span>
                                            <?php } else { ?>
                                                <span class="badge badge-warning"><?= $i['status']; ?></span>
                                            <?php } ?>
                                        </td>
                                        <td><?= esc($i['tahun']); ?></td>
                                        <td><?= esc($i['negara']); ?></td>
                                        <td><?= esc($i['keterangan'] ?: 'Non Elektrifikasi'); ?></td>
                                        <td><?= $i['lokasi']; ?></td>
                                        <td>
                                            <a href="<?= base_url('form/update/' . $i['id']); ?>" class="btn btn-warning btn-sm mb-1">Edit</a>
                                            <a href="<?= base_url('form/hapus/' . $i['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data alat ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
