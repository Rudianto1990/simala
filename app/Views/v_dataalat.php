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
            <button type="button" onclick="window.location.href='<?= base_url('form/createalat'); ?>';" class="btn btn-success"> <i class="fas fa-fw fa-plus"></i> Add</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                   <!--<th>Foto</th>-->
                                    <th>Nomor Asset</th>
                                    <th>Nama Alat</th>
                                    <th>Kode Alat</th>
                                    <th>Merk</th>
                                    <th>Status</th>
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
                                        <td>
                                            <?php if ($i['status'] == 'Milik') { ?>
                                                <span class="badge badge-success"><?= $i['status']; ?></span>
                                            <?php } else { ?>
                                                <span class="badge badge-warning"><?= $i['status']; ?></span>
                                            <?php } ?>
                                        </td>
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
